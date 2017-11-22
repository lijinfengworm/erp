#coding: utf-8
import re
import sys
import time
import random
import gevent
import gevent.queue
import mechanize
import threading
import httplib
import logging
logging.info("haha")
from gevent import monkey
from BeautifulSoup import BeautifulSoup as soup
monkey.patch_all()

from parent.espnnba_updater import EspnNbaUpdater
from urllib2 import HTTPError

queue = gevent.queue.Queue()
stat = []
errs = []

class ServerSimulator(threading.Thread):
    def run(self):
        match_id = 19847
        u = EspnNbaUpdater()
        lastseq = u.matchlivedb.get_last_sequence(match_id)
        seq = 1
        while True:
            u.update_livedb(match_id)
            u.matchlivedb.reset_sequence(match_id,seq)
            seq += random.choice([0,0,0,0,1,2,3,4,5,6])
            time.sleep(5)

def monitor():
    while True:
        try:
            print "Requests %s/%s, min [%4.2f s], avg [%4.2f s]" % (len(stat),len(stat)+len(errs),min(stat),sum(stat)/len(stat))
        except:
            print "Request %s/%s" % (len(stat),len(errs))
        time.sleep(1)

def user_simulate(tid=1):
    thread_id = tid
    match_id = 19847
    br = mechanize.Browser()
    time_interval = 0.05
    baseurl = "http://www.hoopchina.com/gamespace/"
    #baseurl = "http://dulei.dev.gamespace.hc.sf/gamespace_dev.php/"
        
    url = baseurl+"playbyplay_%s.html"%match_id
    try:
        t = time.time()
        br.open(url,timeout=30)
        s = soup(br.response().read())
        #print thread_id,url,"%sms"%(time.time()-t)
        stat.append( time.time()-t )
    except httplib.IncompleteRead:
        stat.append( time.time()-t )
    except Exception,e:
        print repr(e)
        #print thread_id,url,"EXCEPTION"
        errs.append( -1 )
    try:
        sid = s.find('tr',{'sid':re.compile('\d+')}).get('sid')
    except:
        sid = "1"
    while True:
        url = baseurl+"playbyplay/getAjaxLives?sid=%s&match_id=%s"%(sid,match_id)
        try:
            t = time.time()
            br.open(url,timeout=30)
            s = soup(br.response().read())
            #print thread_id,url,"%sms"%(time.time()-t)
            stat.append( time.time()-t )
        except httplib.IncompleteRead:
            stat.append( time.time()-t )
        except Exception,e:
            print repr(e)
            #print thread_id,url,"EXCEPTION"
            errs.append( -1 )
        try:
            sid = s.find('tr',{'sid':re.compile('\d+')}).get('sid')
        except:
            sid = "1"
        time.sleep(random.randint(0,5))
    
if __name__ == "__main__":
    import sys
    if len(sys.argv) == 2:
        if sys.argv[1] == "server":
            server = ServerSimulator()
            server.start()
            server.join()
    if len(sys.argv) == 3:
        c = int(sys.argv[2])
        jobs = [gevent.spawn(user_simulate,tid) for tid in range(c)]
        threading.Thread(target=monitor).start()
        gevent.joinall(jobs)
