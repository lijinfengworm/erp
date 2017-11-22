#coding:utf-8
import sys
reload(sys)
sys.setdefaultencoding('utf-8')

import MySQLdb
from BeautifulSoup import BeautifulSoup as soup
import urllib
import threading
import gevent
from gevent import monkey
monkey.patch_all()

debug = True
lock = threading.Lock()

if debug:
    DB_HOST = "192.168.8.11"
    DB_PORT = 3233
    DB_USERNAME = "root"
    DB_PASSWORD = "testserver"
    DB_DATABASE = "hc_new_test4"

DB = MySQLdb.connect(host=DB_HOST,port=DB_PORT,user=DB_USERNAME,passwd=DB_PASSWORD,db=DB_DATABASE,charset="utf8")

def sync(espn_id):
    url = 'http://espn.go.com/nba/player/gamelog/_/id/%s/' % espn_id
    print url
    page = urllib.urlopen(url).read()
    s = soup(page)
    try:
        if 'No stats available.' in page:
            print espn_id, 'no stats'
            return 
        espn_name = s.findAll('h1')[1].text.replace('&nbsp;','').strip()
    except Exception,e:
        print "ERROR",repr(e)
        return
    if espn_name.lower() == 'nba players':
        return
    with lock:
        c = DB.cursor()
        c.execute('select * from hoopPlayers where eng_name=%s',(espn_name,))
        if c.fetchone():
            c.execute('update hoopPlayers set espn_id=%s,espn_name=%s where eng_name=%s',(espn_id,espn_name,espn_name))
        else:
            c.execute('insert into hoopPlayers (name,eng_name,first_name,last_name,espn_id,espn_name) values (%s,%s,%s,%s,%s,%s)',
                (espn_name.replace(' ','-'),espn_name,espn_name.split(' ')[0],espn_name.split(' ')[-1],espn_id,espn_name))
        DB.commit()

if __name__ == '__main__':
    for r in range(0,90): 
        gevent.joinall( [ gevent.spawn(sync,i) for i in range(r*100+1,r*100+100) ] )
