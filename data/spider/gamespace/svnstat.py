#!/usr/bin/env python
#coding:utf-8
'''calculate svn author contribution using "svn blame"
'''

__author__ = "observer@hoopchina.com"
__date__ = "2012.12.30"

import os
import re
import sys
import sqlite3
import time
import random
from datetime import datetime
from collections import defaultdict

excludes = ['pyXLSX','heroes.py','equipments.py']
exts = ['.py','.php','.js','.html','.css','.htm']
svn_blame = r'svn blame -x -w %s'
pat_blame  = re.compile('^\s*(\d+)\s*(\S*)\s*.*$')
svn_log = r'svn log %s'
pat_log = re.compile(r'r(\d+)\s*\|\s*(\S+)\s*\|\s*(\d+-\d+-\d+)\s*')
db = sqlite3.connect(":memory:")
try:
    with db:
        db.execute('create table stat ( author text, date integer, rev integer )')
except:
    pass
        
examed = set([])

def find_root():
    path = os.path.join("../",".svn")
    while os.path.exists( path ):
        lastpath = os.path.realpath(os.path.dirname(path))
        print lastpath
        path = os.path.join("../",path)
    return lastpath

def process_blame(path):
    print "processing",path
    pipe = os.popen(svn_blame % path)
    for line in pipe.readlines():
        m = pat_blame.match(line[0:-1])
        try:
            c = db.cursor()
            c.execute('insert into stat (author,rev,date) values (?,?,0)',(m.group(2),m.group(1)))
            db.commit()
        except Exception,e:
            print repr(e)
            db.rollback()
            
    
def iterate_dir(path):
    path = os.path.realpath(path)
    if path in examed:
        return
    if path.rsplit('/',1)[-1] in excludes:
        return
    examed.add(path)
    if(os.path.isdir(path)):
        for item in os.listdir(path):
            iterate_dir(os.path.join(path, item))
    else:
        if os.path.splitext(path)[1] in exts:
            process_blame(path)

def log_revdate(path):
    print "fetching logs"
    path = os.path.realpath(path)
    pipe = os.popen(svn_log % path)
    for line in pipe.readlines():
        m = pat_log.search(line)
        if not m:
            continue
        rev,date = m.group(1),m.group(3)
        rev = int(rev)
        dateint = time.mktime( datetime(*[int(x) for x in date.split('-')]).timetuple() )
        try:
            with db:
                db.execute('update stat set date=? where rev=?',(dateint,rev))
        except Exception,e:
            print repr(e)

def report_txt():
    '''Author 上周代码统计  双周代码统计  上月代码统计  一年代码统计
    '''
    with db:
        c = db.execute('select author from stat group by author')
    authors = [ x[0] for x in c.fetchall() ]
    print '              %s        %s        %s        %s        %s' % ('用户名','一周代码统计','双周代码统计','一月代码统计','一年代码统计')
    for author in authors:
        print '%20s'%author,
        times = [ time.time()-x for x in [86400*7,86400*14,86400*30,86400*365] ]
        for t in times:
            with db:
                c = db.execute('select count(*) from stat where author=? and date>? group by rev',(author,t))
            num_revs = len(c.fetchall())
            with db:
                c = db.execute('select count(*) from stat where author=? and date>?',(author,t))
            num_codes = c.fetchone()[0]
            print '       %2d次/%4d行 '%(num_revs,num_codes),
        print

def report_graph(days=180):
    with db:
        c = db.execute('select min(date),max(date) from stat where date>0')
    xmin,xmax =  c.fetchone()
    xmin = max(time.time()-86400*days,xmin)
    convx = lambda x:(x-xmin)/86400

    with db:
        c = db.execute('select author from stat group by author')
    authors = [ x[0] for x in c.fetchall() ]

    from pylab import plot,xlabel,ylabel,title,grid,show,legend
    colors = 'bgrcmyk'
    styles = ['-','--','-.',':']
    p = []
    a = []
    for author in authors:
        with db:
            c = db.execute('select date from stat where author=? and date>=?',(author,xmin))
        dates = [x[0] for x in c.fetchall()]
        dates_dict = defaultdict(int)
        for date in dates:
            dates_dict[date] += 1
        dates_list = [ list(x) for x in sorted(dates_dict.items()) ]
        for i in range(len(dates_list)-1):
            dates_list[i+1][1] += dates_list[i][1]
        xx = [ convx(x[0]) for x in dates_list ]
        yy = [ x[1] for x in dates_list ]
        if len(yy)>2:
            p.append( plot(xx,yy,c=random.choice(colors),ls=random.choice(styles),lw=3)[0] )
            a.append( author )

    xlabel('time (days)')
    ylabel('code (lines)')
    title('The SVN Contribution Graph')
    grid(True)
    legend(p,a,loc=2)
    show()
    
if __name__ == '__main__':
    root = find_root()
    iterate_dir(root)
    log_revdate(root)
    with db:
        c = db.execute('select * from stat')
    if len(sys.argv) == 2:
        if sys.argv[1].isdigit():
            report_graph(days=int(sys.argv[1]))
        else:
            report_graph(days=30)
    else:
        report_graph(days=30)
