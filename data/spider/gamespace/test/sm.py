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
import urllib2
import logging
logging.info("haha")
from gevent import monkey
from BeautifulSoup import BeautifulSoup as soup
monkey.patch_all()

from urllib2 import HTTPError

threading.stack_size(1024*1024)

stat = []
errs = []

def monitor():
    while True:
        try:
            print "Requests %s/%s, min [%4.2f s], max [%4.2f s] avg [%4.2f s]" % (len(stat),len(stat)+len(errs),min(stat),max(stat),sum(stat)/len(stat))
        except:
            print "Request %s/%s" % (len(stat),len(errs))
        time.sleep(1)

def user_simulate_boxscore(tid=1):
    match_id = 19858
    baseurl = "http://www.hoopchina.com/gamespace/"
    url = baseurl+"playbyplay_%s.html"%match_id
    while True:
        try:
            t = time.time()
            urllib2.urlopen(url).read()
            stat.append( time.time()-t )
        except Exception,e:
            print repr(e)
            errs.append( -1 )
        time.sleep(random.randint(0,5))

def user_simulate_live(tid=1):
    thread_id = tid
    match_id = 19858
    baseurl = "http://www.hoopchina.com/gamespace/"
    #baseurl = "http://dulei.dev.gamespace.hc.sf/gamespace_dev.php/"
        
    url = baseurl+"playbyplay_%s.html"%match_id

    while True:
        sid = random.randint(1,400)
        url = baseurl+"playbyplay/getAjaxLives?sid=%s&match_id=%s"%(sid,match_id)
        try:
            t = time.time()
            urllib2.urlopen(url).read()
            stat.append( time.time()-t )
        except Exception,e:
            print repr(e)
            errs.append( -1 )
        time.sleep(random.randint(0,5))
    
if __name__ == "__main__":
    import sys
    if len(sys.argv) == 3:
        c = int(sys.argv[2])
        jobs = []
        for tid in range(c):
            job = gevent.spawn(user_simulate_live,tid)
            jobs.append(job)
            time.sleep(0.01)
            if len(jobs)%50 == 0:
                print len(jobs),"users has opened the live page"
        livejobs = len(jobs)
        for tid in range(c/10):
            job = gevent.spawn(user_simulate_boxscore,tid)
            jobs.append(job)
            time.sleep(0.01)
            if (len(jobs)-livejobs)%50 == 0:
                print len(jobs)-livejobs,"users has opened the boxscore page"

        threading.Thread(target=monitor).start()
        gevent.joinall(jobs)
