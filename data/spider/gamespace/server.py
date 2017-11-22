#coding:utf-8
import settings
import logging
logger = logging.getLogger(__name__)
logger.setLevel(settings.LOG_LEVEL)

from espnnba_updater import EspnNbaUpdater
from lehecai_updater import LehecaiUpdater
from apscheduler.scheduler import Scheduler
from datetime import datetime,timedelta
import SocketServer
import settings
import threading
import socket
import json
import database
import task
import daemon
import utils
import time

import tornado.ioloop
import tornado.web

tm = task.TaskManager()

class MainHandler(tornado.web.RequestHandler):
    '''Request API:
    
    * All data recieved must be of JSON format

                     |  client to server               |   server's response   |   description
    --------------------------------------------------------------------------------------------------
    Update schedule  |  {"op":"sched","days":[1,2,3]}  |   {"status":0}        |  赛程表更新
                     |                                 |   {"status":1,        |  days是一个List，表示
                     |                                 |    "reason":"xxxx"}   |  距离当前时间的天数
    --------------------------------------------------------------------------------------------------
    Update boxscore  | {"op":"boxscore","ids":[19725]} |   as above            |  统计信息更新
                     |                                 |                       |  ids为match_id的列表
    --------------------------------------------------------------------------------------------------
    Update livematch | {"op":"live","ids":[19725]}     |   as above            |  直播任务，实时更新
                     |                                 |   as above            |  ids为match_id的列表 
    --------------------------------------------------------------------------------------------------
    Check Status     | {"op":"jobs"}                   | [{"job":1,"op":"live",|  显示当前运行的任务列表
                     |                                 |   args:23452}, ...]   |  
    -------------------------------------------------------------------------------------------------
    Stop job         | {"op":"stop","job":1},          | as `Update schedule`  |  停止指定的任务
                     | {"op":"stop","ids":[19725]}     |                       |  参数可以是比赛ID或者任务ID
    -------------------------------------------------------------------------------------------------
    XXXXX            | asdf;kjwperj                    | {"status":1,"reason": |  参数解析不能
                     |                                 |  "parse error"}       |
    -------------------------------------------------------------------------------------------------
    status code:     1            unrecognized command
                     2            network error
                     3            page parse error
                     4            database error
   '''
    def __init__(self,*arg,**args):
        super(type(self),self).__init__(*arg,**args)
        self.logger = logging.getLogger("ThreadServer")
        self.logger.setLevel(settings.LOG_LEVEL)
        self.logger.info("Handler %s initiated"%threading.current_thread())

    def post(self):
        data = self.request.body
        data = data.strip()
        self.logger.info("recv %s : [%s]"%(len(data),data))

        try:
            j = json.loads(data)
        except:
            self.write(json.dumps({"status":1,"reason":"Command must be of JSON format"})+"\n\0")
            return

        if not isinstance(j,dict):
            self.write(json.dumps({"status":1,"reason":"Command must be a dict"})+"\n\0")
            return 

        ret = {"status":2,"reason":"Command not valid"}
        if j.get("op") == "sched":
            ret = tm.add_task(op=j.get("op"),para={"days":j.get("days",[])})
        elif j.get("op") == "boxscore":
            ret = tm.add_task(op=j.get("op"),para={"ids":j.get("ids",[])})
        elif j.get("op") == "live":
            ret = tm.add_task(op=j.get("op"),para={"ids":j.get("ids",[])},sleep=j.get("sleep",0))
        elif j.get("op") == "jobs":
            ret = tm.status()
        elif j.get("op") == "stop":
            if j.has_key("job"):
                ret = tm.stop_task(j.get("job",0))
            if j.has_key("ids"):
                ret = tm.stop_match(j.get("ids",[]),j.get("type",None))
      
        self.write(json.dumps(ret)+"\n\0")

application = tornado.web.Application([
    (r"/",MainHandler),
])

def serve_forever():
    application.listen(settings.PORT)
    tornado.ioloop.IOLoop.instance().start()

if __name__ == "__main__":
    serve_forever()
