#coding:utf-8
'''比赛信息获取流程
每隔3小时执行以下任务
    1.从espn获取最新的赛程表。
    2.若发现存在新的比赛安排或者原来的比赛安排出现改动，则入库(update_schedule=>hoopMatches)
    3.若需要入库，则根据lehecai的API获得lehecai的相关信息入库(sync_schedule=>hoopMatches)
    4.检查近期是否有比赛要比，如果有，加入定时任务，定时触发

定时任务的流程：
    1.在赛程表规定的时间开始监听比赛
    2.每隔5秒钟检查比赛是否完结，如果没有结束，则做以下操作：
        a.从ESPN的boxscore页面获取统计数据，分别更新入库(update_boxscore=>hoopMatchStats,hoopPlayerMatchStats)
        b.从ESPN的playbyplay页面获取文字直播数据，分别更新入库(update_livedb=>hoopMatchLive)
            i. 其中update_livedb会调用lehecai_updater的update_match_info方法，主要用于更新hoopMatches的数据

积分榜获取流程
 * 定时任务结束后30分钟获取一次积分榜
 * 每天北京时间下午2点获取一次积分榜
'''
import settings
import logging
logger = logging.getLogger(__name__)
logger.setLevel(settings.LOG_LEVEL)

from apscheduler.scheduler import Scheduler
from espnnba_updater import EspnNbaUpdater
from lehecai_updater import LehecaiUpdater
from datetime import datetime,timedelta
from server import serve_forever
import SocketServer
import threading
import database
import daemon
import socket
import urllib
import utils
import json
import task
import time

def post_json(j):
    ''' post json to server '''
    url = "http://localhost:%s/"%settings.PORT
    data = json.dumps(j)
    ret = urllib.urlopen(url,data).read()

class NbaScheduler:
    def __init__(self,*args,**kargs):
        self.matchesdb = database.MatchesDB()
        self.settingsdb = database.SettingsDB()
        self.espnnba = EspnNbaUpdater()
        self.lehecai = LehecaiUpdater()
        self.easttime = utils.EastTime()
        self.watched_match_ids = set([])
        self.sched = Scheduler()
        self.logger = logging.getLogger(__name__+"|NbaScheduler")
        self.logger.setLevel(settings.LOG_LEVEL)

    def start(self):
        self.add_schedule_task(seconds=60*60)
        self.task_nba_stats(seconds=6*60*60)
        self.sched.start()

    def task_nba_stats(self,seconds):
        from playerstat_updater import PlayerStatUpdater
        self.sched.add_interval_job(PlayerStatUpdater().update_all,seconds=seconds,start_date=datetime.now()+timedelta(seconds=5))
    
        from teamstat_updater import update_all
        self.sched.add_interval_job(update_all,seconds=seconds,start_date=datetime.now()+timedelta(seconds=5))

    def task_nba_schedule(self):
        #self.espnnba.update_schedule(range(0,14))
        #self.lehecai.sync_schedule(range(0,14))
        post_json({"op":"sched","days":range(0,14)})

        # check whether we have match to trigger in 24hours, if yes, add task to schedule
        id_dict = self.matchesdb.get_recent_match_info(hours=24)
        for d in id_dict:
            match_id = d.keys()[0]
            china_time, usa_time = d.values()[0]

            # to avoid fetching all-stars matches, etc.
            if not self.settingsdb.need_fetch(usa_time):
                continue

            if china_time < datetime.now()-timedelta(hours=4): 
                post_json({"op":"boxscore","ids":[match_id],"sleep":0})
            else:
                if (not self.espnnba.is_finished(match_id)) and (match_id not in self.watched_match_ids):
                    seconds = (china_time-datetime.now()).total_seconds()
                    #self.add_live_task(match_id,china_time)
                    seconds = seconds if seconds>0 else 0
                    post_json({"op":"live","ids":[match_id],"sleep":seconds})
                    self.watched_match_ids.add(match_id)

    def add_schedule_task(self,seconds=1):
        self.sched.add_interval_job(self.task_nba_schedule,seconds=seconds,start_date=datetime.now()+timedelta(seconds=5))

    def add_live_task(self,match_id,date):
        self.sched.add_date_job(self.task_nba_live,date,[match_id])

    def task_nba_live(self,match_id,interval=5):
        while not self.espnnba.is_finished(match_id):
            self.logger.info("[%s]Updating match_id %s"%(datetime.now().strftime("%Y-%m-%d %H:%M:%S"),match_id))
            self.espnnba.update_boxscore(match_id)
            self.espnnba.update_livedb(match_id)
            time.sleep(interval)

def cmd():
    import optparse
    import sys
    import os

    p = optparse.OptionParser()
    p.add_option('-l','--log-level',action='store',type='string',dest='loglevel',metavar="INFO",help="set log level, default is [INFO], which will make a lot of noise",default='INFO')
    p.add_option('-f','--log-file',action='store',type='string',dest='logfile',metavar="FILE",help="write output to FILE",default='')
    #p.add_option('-d','--daemon',action='store_true',dest='daemon',help="run as daemon, default is [False]")
    options, arguments = p.parse_args()

    NbaScheduler().start()

    if options.logfile:
        logging.basicConfig(filename=os.path.join(os.path.dirname(os.path.abspath(__file__)),options.logfile))
        logging.info("inited")
        logging.info("inited")
        logging.info("inited")
    else:
        logging.info("output to screen")

    try:
        settings.LOG_LEVEL = getattr(logging,options.loglevel)
    except:
        logging.error("no proper logging level detected, using [INFO] as default")
        settings.LOG_LEVEL = logging.INFO

    #if not options.daemon:
    serve_forever()
    
    #with daemon.DaemonContext():
    #    serve_forever()

if __name__ == "__main__":
    cmd()
