#coding:utf-8
import pytz
from pytz import timezone
from datetime import datetime,timedelta
from settings import FMT
import re


class EastTime:
    def __init__(self,datestr="20111216",hourstr="7:00 PM ET"):
        year,month,day = int(datestr[:4]),int(datestr[4:6]),int(datestr[6:8])
        m = re.compile(r"(\d+):(\d+) (..)").search(hourstr)
        hour = int(m.group(1)) + (m.group(3)=="PM" and 12 or 0)
        if hour in [12,24]:
            hour -= 12
        minute = int(m.group(2))
        self.eastern = timezone('US/Eastern')
        self.t = self.eastern.localize(datetime(year,month,day,hour,minute))
        self.fmt = '%Y-%m-%d %H:%M:%S'

    def et(self,fmt=FMT):
        return self.t.strftime(fmt)

    def cn(self,fmt=FMT):
        china = timezone('Asia/Shanghai')
        cn = self.t.astimezone(china)
        return cn.strftime(fmt)

    def now(self,day_delta=0,hour_delta=0,fmt=FMT):
        utcnow = pytz.utc.localize(datetime.utcnow()+timedelta(days=day_delta,hours=hour_delta))
        return utcnow.astimezone(self.eastern).strftime(fmt)

def get_current_season(dt=datetime.utcnow()):
    try:
        year, month = int(dt[:4]), int(dt[5:7])
        season = year-1 if month < 8 else year
    except:
        season = 2011
    return season

if __name__ == '__main__':
    print get_current_season()
    print EastTime().et()
    print EastTime().cn()
    print EastTime().now(-1)

