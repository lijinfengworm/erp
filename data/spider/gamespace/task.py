#coding:utf-8
import settings
import logging
logger = logging.getLogger(__name__)
logger.setLevel(settings.LOG_LEVEL)

from espnnba_updater import EspnNbaUpdater
from lehecai_updater import LehecaiUpdater
from datetime import datetime,timedelta
import threading
import json
import time

TLOCK = threading.Lock()

class Task(threading.Thread):
    last_id = 0
    def __init__(self,op="sched",para={"days":[1,2]},sleep=0,manager=None,*args,**kwargs):
        super(Task,self).__init__(*args,**kwargs)
        self.date_add = datetime.now()
        self.sleep = sleep
        self.op = op
        self.para = para
        self.ids_live = set([])
        self.ids_boxscore = set([])
        self.status = "PENDING"
        self.interval = settings.INTERVAL
        self.manager = manager
        self.__stop_task = threading.Event()
        with TLOCK:
            self.job_id = Task.last_id + 1
            Task.last_id += 1
        self.logger = logging.getLogger("task")
        self.logger.setLevel(settings.LOG_LEVEL)
        self.logger.info("Task %s initiated"%threading.current_thread())

    def get_status(self):
        return {"job":self.job_id,"op":self.op,"args":self.para,"status":self.status,"date_add":str(self.date_add),"sleep":self.sleep,"ids_live":list(self.ids_live),"ids_boxscore":list(self.ids_boxscore)}

    def stop(self):
        with TLOCK:
            self.__stop_task.set()
            self.status = "STOPPED"

    def stop_match(self,match_id,type):
        with TLOCK:
            if type == "live" and (match_id in self.ids_live):
                self.ids_live.remove(match_id)
            elif type == "boxscore" and (match_id in self.ids_boxscore):
                self.ids_boxscore.remove(match_id)
            else:
                if match_id in self.ids_live:
                    self.ids_live.remove(match_id)
                if match_id in self.ids_boxscore:
                    self.ids_boxscore.remove(match_id)
            self.logger.info("Match id [%s] is removed from type [%s]"%(match_id,type))

    def stopped(self):
        return self.__stop_task.isSet()

    def run(self):
        time.sleep(self.sleep)
        self.logger.info("job %s started"%self.job_id)
        try:
            # only PENDING jobs can be executed
            if self.status != "PENDING":
                return
            self.status = "RUNNING"
            if self.op == "sched":
                EspnNbaUpdater().update_schedule(self.para.get("days",[0]))
                LehecaiUpdater().sync_schedule(self.para.get("days",[0]))
            elif self.op == "boxscore":
                espn = EspnNbaUpdater()
                for match_id in self.para.get("ids",[]):
                    espn.update_livedb(match_id)
                    espn.update_boxscore(match_id)
                    espn.update_leads(match_id)
                    espn.update_standings()
            elif self.op == "live":
                espn = EspnNbaUpdater()
                import copy
                self.ids_live = set(self.para.get("ids"))
                self.ids_boxscore = copy.copy(self.ids_live)
                ids = copy.copy(self.ids_live)
                #for match_id in self.ids_live:
                    #espn.matchlivedb.reset_match(match_id)
                    #espn.matchesdb.set_match_status(match_id,"IN_PROGRESS")
                while self.ids_live and self.ids_boxscore and (not self.stopped()):
                    ids_boxscore_new = set([])
                    ids_live_new = set([])
                    for match_id in self.ids_live:
                        espn.update_livedb(match_id)
                        if not espn.is_finished(match_id):
                            ids_live_new.add(match_id)
                        #ids_live_new.add(match_id)
                    for match_id in self.ids_boxscore:
                        espn.update_boxscore(match_id)
                        if not espn.is_finished(match_id):
                            ids_boxscore_new.add(match_id)
                        #ids_boxscore_new.add(match_id)
                    time.sleep(self.interval)
                    with TLOCK:
                        # remove ids that has been "STOPPED"
                        self.ids_live = self.ids_live.intersection(ids_live_new)
                        self.ids_boxscore = self.ids_boxscore.intersection(ids_boxscore_new)
                for match_id in self.ids_live:
                    espn.update_livedb(match_id)
                    espn.update_leads(match_id)
                for match_id in self.ids_boxscore:
                    espn.update_boxscore(match_id)
                espn.update_standings()
                # we kept fetching after game ends, because of many strange issues caused by not doing that
                counter = 0
                while counter<10:
                    counter += 1
                    time.sleep(60*counter)
                    for match_id in ids:
                        espn.update_livedb(match_id)
                        espn.update_boxscore(match_id)
                        espn.update_standings()
            self.status = "FINISHED"
        except Exception,e:
            self.status = "ERROR"
            self.logger.error("Task [%s->%s] error: %s"%(self.op,self.para,e))
        self.logger.info("job %s endded"%self.job_id)
        time.sleep(86400*7)
        if self.manager:
            self.manager.remove_task(self.job_id)
        
class TaskManager:
    def __init__(self):
        self.tasks = {}

    def add_task(self,op,para,sleep=0):
        task = Task(op=op,para=para,sleep=sleep,manager=self)
        self.tasks[task.job_id] = task
        task.start()
        return {"status":0}

    def stop_task(self,job_id):
        task = self.tasks.get(job_id)
        if task:
            task.stop()
            return {"status":0}
        else:
            return {"status":2,"reason":"Job id %s does not exist"%job_id}

    def stop_match(self,match_ids,type=None):
        try:
            for job_id,task in self.tasks.items():
                for match_id in match_ids:
                    task.stop_match(match_id,type)
        except Exception,e:
            return {"status":3,"reason":repr(e)}
        return {"status":0}

    def status(self):
        ret = []
        for job_id,task in self.tasks.items():
            ret.append( task.get_status() )
        return ret

    def remove(self,job_id):
        if self.tasks.has_key(job_id):
            self.tasks.pop(job_id)
