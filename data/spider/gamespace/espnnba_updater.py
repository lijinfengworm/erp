#coding:utf-8
'''Update records from E.G.( http://espn.go.com/nba/playbyplay?gameId=310211011&period=0 )

'''
import settings
import logging
logger = logging.getLogger(__name__)
logger.setLevel(settings.LOG_LEVEL)
import json
import translate
import database
import mechanize
import BeautifulSoup
import utils
import time
import re
from lehecai_updater import LehecaiUpdater
from datetime import datetime,timedelta
import threading

####################################
# Exceptions
###################################
class ParseError(Exception):
    pass

def parse_int(x):
    try:
        return int(x)
    except:
        return 0

#####################################
# Updater
####################################

class EspnNbaUpdater:
    def __init__(self):
        self.br = mechanize.Browser()
        self.br.set_handle_robots(False)
        self.livedb = database.MatchLiveDB()
        self.matchesdb = database.MatchesDB()
        self.matchstatsdb = database.MatchStatsDB()
        self.playermatchstatsdb = database.PlayerMatchStatsDB()
        self.teamsdb = database.TeamsDB()
        self.playersdb = database.PlayersDB()
        self.standingsdb = database.StandingsDB()
        self.matchlivedb = database.MatchLiveDB()
        #self.idname_dict = self.playersdb.get_idname_dict()
        self.idname_dict = translate.idname_dict
        self.lehecai = LehecaiUpdater()
        self.logger = logger

    def _update_match_stats(self,match_id,soup):
        away_team = soup.find('div',{'class':re.compile('team away')}).a.text.strip()
        home_team = soup.find('div',{'class':re.compile('team home')}).a.text.strip()
        team_names = [away_team.split(' ')[-1],home_team.split(' ')[-1]]

        url = 'http://scores.espn.go.com/nba/conversation?gameId=%s' % self.matchesdb.get_espn_id(match_id)
        trs = soup.find('table',{'class':'linescore'}).findAll('tr')
        scores = []
        for tr in trs[1:]:
            tds = tr.findAll('td')
            scores.append( [ parse_int(x.text.strip().replace('&nbsp;','')) if x.text.strip().replace('&nbsp;','') else 0 for x in tds[1:] ])
        trs = soup.findAll('tr')
        column_names = []
        for tr in trs:
            if tr.th and tr.th.text == "STARTERS":
                ths = tr.findAll('th')
                for th in ths:
                    column_names.append(th.text)
                break
        cndict = {'mins':'MIN','fgm':'FGM-A','fga':'FGM-A','tpm':'3PM-A','tpa':'3PM-A','ftm':'FTM-A','fta':'FTM-A','oreb':'OREB','dreb':'DREB','reb':'REB','ast':'AST','stl':'STL','blk':'BLK','to':'TO','pf':'PF','ns':'+/-','pts':'PTS'}
        def locate(tds,x):
            try:
                return tds[column_names.index(cndict.get(x))].text
            except:
                return 0

        tbodys = soup.find('div',{'id':re.compile('my-players-table')}).findAll('tbody')
        team_stats = [{'mins':0,'pts':0,'fgm':0,'fga':0,'tpm':0,'tpa':0,'ftm':0,'fta':0,'oreb':0,'dreb':0,'reb':0,'ast':0,'stl':0,'blk':0,'to':0,'pf':0},{'mins':0,'pts':0,'fgm':0,'fga':0,'tpm':0,'tpa':0,'ftm':0,'fta':0,'oreb':0,'dreb':0,'reb':0,'ast':0,'stl':0,'blk':0,'to':0,'pf':0},]
        for i in [0,1]:
            is_starter = True
            for tbody in tbodys[i*3:i*3+2]:
                trs = tbody.findAll('tr')
                for tr in trs:
                    tds = tr.findAll('td')
                    pfa,dnp,start_position = "","",""
                    net_score=mins=pts=fga=fgm=tpa=tpm=fta=ftm=dreb=oreb=reb=ast=stl=blk=to=pf=0
                    espn_player_id = 0
                    if len(tds) == 2:
                        try:
                            player_name = tds[0].a.text.replace('&nbsp;','').strip()
                            start_position=tds[0].text.replace('&nbsp;','').split(',')[-1].strip()
                            espn_player_id=int(re.compile(r'/(\d+)/').search(tds[0].a.get('href')).group(1))
                        except:
                            player_name = tds[0].text.replace('&nbsp;','').strip()
                            start_pposition="N"
                        dnp = tds[1].text
                    elif len(tds) in [15,14]:
                        player_name = tds[0].a.text.replace('&nbsp;','').strip()
                        start_position=tds[0].text.replace('&nbsp;','').split(',')[-1].strip()
                        try:
                            espn_player_id=int(re.compile(r'/(\d+)/').search(tds[0].a.get('href')).group(1))
                        except Exception,e:
                            self.logger.error(repr(e))
                        try:
                            fgm,fga = [ parse_int(x) for x in locate(tds,'fgm').strip().split('-') ]
                            tpm,tpa = [ parse_int(x) for x in locate(tds,'tpm').strip().split('-') ]
                            ftm,fta = [ parse_int(x) for x in locate(tds,'ftm').strip().split('-') ]
                        except Exception,e:
                            import traceback
                            self.logger.error("column parse error")
                            traceback.print_exc()
                        oreb,dreb,reb,ast,stl,blk,to,pf,net_score,mins,pts = [ parse_int(locate(tds,x)) for x in ['oreb','dreb','reb','ast','stl','blk','to','pf','ns','mins','pts'] ]
                        if oreb+dreb != reb:
                            if dreb == 0:
                                dreb = reb-oreb
                            elif oreb == 0:
                                oreb = reb-dreb
                            elif reb == 0:
                                reb = oreb+dreb
                        for term in team_stats[i].keys():
                            team_stats[i][term] += locals()[term]
                    else:
                        self.logger.error("Unknown Format: %s" % tr)
                        continue
                    self.playermatchstatsdb.update(player_name,team_names[i],match_id,mins,pts,fga,fgm,tpa,tpm,fta,ftm,dreb,oreb,reb,ast,stl,blk,to,pf,dnp,start_position,pfa,is_starter,net_score,espn_player_id)
                # set False when we have is_starter true once
                is_starter = False

            q1,q2,q3,q4 = scores[i][:4]
            ots = ','.join([str(x) for x in scores[i][4:-1]])
            try:
                m = re.compile(r'Fast break points:.*?(\d+).*?Points in the paint:.*?(\d+)',re.DOTALL).search(tbodys[i*3+2].text)
                fast,paint = parse_int(m.group(1)),parse_int(m.group(2))
            except:
                fast,paint = 0,0
                self.logger.warning("fast break and paint points does not exist yet")
            try:
                m = re.compile(r'Team TO.*?(\d+).*?(\d+)',re.DOTALL).search(tbodys[i*3+2].text)
                team_to,points_off = parse_int(m.group(1)),parse_int(m.group(2))
            except:
                team_to,points_off = 0,0
                self.logger.error("Team TO, points off does not exist yet")
            self.matchstatsdb.update(match_id,team_names[i],fast_scores=fast,paint_scores=paint,
                first_scores=q1,second_scores=q2,third_scores=q3,fourth_scores=q4,ot_scores=ots,
                mins=team_stats[i]['mins'],pts=team_stats[i]['pts'],fga=team_stats[i]['fga'],
                fgm=team_stats[i]['fgm'],tpa=team_stats[i]['tpa'],tpm=team_stats[i]['tpm'],
                fta=team_stats[i]['fta'],ftm=team_stats[i]['ftm'],dreb=team_stats[i]['dreb'],
                oreb=team_stats[i]['oreb'],
                reb=team_stats[i]['reb'],ast=team_stats[i]['ast'],stl=team_stats[i]['stl'],
                to=team_stats[i]['to'],pf=team_stats[i]['pf'],blk=team_stats[i]['blk'],
                team_to=team_to,points_off=points_off,
            )

    def _update_match_info(self,match_id,soup):
        soup = soup.text
        try:
            scores = re.compile(r'Fast break points:.*?(\d+).*?Points in the paint:.*?(\d+)',re.DOTALL).findall(soup)
            away_fast,away_paint = scores[0]
            home_fast,home_paint = scores[1]
        except Exception,e:
            away_fast,away_paint,home_fast,home_paint = 0,0,0,0
            self.logger.warning("fast & paint info is not available")
        m = re.compile(r'Attendance:.*?(\d+,\d+).*?Time of Game:.*?(\d+:\d+)',re.DOTALL).search(soup)
        try:
            attendance,game_time = parse_int(m.group(1).replace(',','')),m.group(2)
        except:
            attendance,game_time = 0,"0:00"
        self.matchesdb.update_match_info(match_id,attendance,game_time,away_fast,home_fast,away_paint,home_paint)

    def _update_match_status(self,match_id,soup):
        s =  soup.find('p',{'class':'game-state'}).text.replace('&nbsp;','').strip()
        if s.endswith("ET"):
            self.matchesdb.set_match_status(match_id,"PENDING")
        elif "Final" in s:
            self.matchesdb.set_match_status(match_id,"FINISHED")
        else:
            self.matchesdb.set_match_status(match_id,"IN_PROGRESS")

    def get_match_status(self,match_id):
        url = 'http://scores.espn.go.com/nba/conversation?gameId=%s' % self.matchesdb.get_espn_id(match_id)
        try:
            self.br.open(url,timeout=10)
            page = self.br.response().read()
        except:
            return "0:00 PM ET"
        soup = BeautifulSoup.BeautifulSoup(page)
        return soup.find('p',{'class':'game-state'}).text.replace('&nbsp;','').strip()

    def is_finished(self,match_id):
        '''we must double ensure the match is endded'''
        return self.matchlivedb.is_finished(match_id) and "Final" in self.get_match_status(match_id)

    def has_espn_data(self,match_id,soup):
        try:
            url = 'http://scores.espn.go.com/nba/conversation?gameId=%s' % self.matchesdb.get_espn_id(match_id)
            trs = soup.find('table',{'class':'linescore'}).findAll('tr')
            return True
        except Exception,e:
            # may be not started
            if not self.get_match_status(match_id).endswith("ET"):
                # we should have data now, log error and return
                self.logger.error("we should have data now @ match_id %s"%match_id)
            return False

    def update_boxscore(self,match_id):
        self.br.clear_history()
        espn_id = self.matchesdb.get_espn_id(match_id)
        url = "http://scores.espn.go.com/nba/boxscore?gameId=%s"%espn_id
        try:
            self.br.open(url,timeout=10)
            page = self.br.response().read()
        except Exception,e:
            self.logger.error(repr(e))
            return False
        soup = BeautifulSoup.BeautifulSoup(page)

        if not self.has_espn_data(match_id,soup):
            return
        self._update_match_stats(match_id,soup)
        self._update_match_info(match_id,soup)
        self._update_match_status(match_id,soup)

    def update_schedule(self, day_deltas=range(0,14)):
        self.br.clear_history()
        for day_delta in day_deltas:
            # check if the match should be fetched or not
            date = utils.EastTime().now(day_delta,fmt="%Y-%m-%d")
            if not database.SettingsDB().need_fetch(date):
                continue

            datestr = utils.EastTime().now(day_delta,fmt="%Y%m%d")
            url = "http://scores.espn.go.com/nba/scoreboard?date=%s"%datestr
            self.logger.info("getting %s"%url)
            try:
                self.br.open(url,timeout=10)
                page = self.br.response()
            except Exception,e:
                self.logger.error(repr(e))
                continue
            soup = BeautifulSoup.BeautifulSoup(page)
            divs = soup.findAll('div',{'id':re.compile('gamebox')})
            for div in divs:
                trs = div.findAll('tr')
                away_team = trs[0].text.replace('&nbsp;','').strip().split(" ")[-1]
                home_team = trs[2].text.replace('&nbsp;','').strip().split(" ")[-1]
                hourstr = div.find('ul',{'class':'game-info'}).span.text
                espn_id = div.find('a',{'href':re.compile(r'/nba/conversation\?gameId=\d+')}).get('href').split('=')[-1]
                season = datestr[:4]
                if datestr[4:6]<"08":
                    season = parse_int(season)-1
                try:
                    east_time = utils.EastTime(datestr,hourstr)
                except:
                    # finished match does not show match time
                    try:
                        self.br.open('http://scores.espn.go.com/nba/conversation?gameId='+espn_id,timeout=10)
                        page2 = self.br.response().read()
                    except Exception,e:
                        self.logger.error(repr(e))
                        continue
                    soup2 = BeautifulSoup.BeautifulSoup(page2)
                    hourstr,datestr = soup2.find('div',{'class':'game-time-location'}).p.text.strip().split(',',1)
                    datestr = datetime.strptime(datestr.strip(),"%B %d, %Y").strftime("%Y%m%d")
                    east_time = utils.EastTime(datestr,hourstr)
                    # should strip numbers from team name
                    while away_team[-1].isdigit():
                        away_team = away_team[:-1]
                    while home_team[-1].isdigit():
                        home_team = home_team[:-1]
                try:
                    match_type = div.find('p',{'id':re.compile('gameNote')}).text.strip().split(' ')[-1]
                except:
                    match_type = "REGULAR"
                if not match_type.replace('&nbsp;','').strip():
                    match_type = "REGULAR"
                match_type = match_type.replace('&nbsp;','').strip()
                self.logger.info("Updating NBA match %s: %s vs %s, starts at %s %s, (Season%s %s)"%(espn_id,home_team,away_team,datestr,hourstr,season,match_type))
                match_id = self.matchesdb.update_schedule(away_team,home_team,east_time,espn_id,season,match_type)

                # updating stadium info from conversation page
                conurl = "http://scores.espn.go.com/nba/conversation?gameId=%s" % espn_id
                try:
                    self.br.open(conurl,timeout=10)
                    page = self.br.response().read()
                except Exception,e:
                    self.logger.warning("Conversation page cannot open, skiping: "+repr(e))
                    continue

                consoup = BeautifulSoup.BeautifulSoup(page)
                tldiv = consoup.find('div',{'class':'game-time-location'})
                try:
                    stadium_name,city,state = [ x.replace('&nbsp;','').strip() for x in tldiv.findAll('p')[-1].text.split(',') ]
                except:
                    try:
                        stadium_name,city = [ x.replace('&nbsp;','').strip() for x in tldiv.findAll('p')[-1].text.split(',') ]
                        state = city
                    except:
                        self.logger.error("cannot fetch stadium info(e.g. undecided), skipping")
                        continue
                self.matchesdb.update_stadium_info(espn_id,stadium_name,city,state)


    def _update_lehecai_live(self,match_id,match_time,home_score=None,away_score=None):
        lehecai_id = self.matchesdb.get_lehecai_id(match_id)
        try:
            if not home_score:
                away,home = self.lehecai.update_match_info([lehecai_id])[0]
                self.matchesdb.update_lehecai_live(match_id,match_time,away,home)
            else:
                away,home = away_score,home_score
                self.matchesdb.update_lehecai_live(match_id,match_time,away,home)
        except:
            pass

    def update_livedb(self,match_id):
        def get_involved_players(txt):
            d = []
            for eng_name,dd in self.idname_dict.items():
                if eng_name in txt:
                    d.append(dd)
            return d

        self.br.clear_history()
        espn_id = self.matchesdb.get_espn_id(match_id)
        url = settings.ESPN_NBA_URL % espn_id
        self.logger.info("updating match_id %s/espn_id %s started."%(match_id,espn_id))

        try:
            self.br.open(url,timeout=10)
            page = self.br.response().read()
        except Exception,e:
            self.logger.error(repr(e))
            return False

        soup = BeautifulSoup.BeautifulSoup(page)

        if not self.has_espn_data(match_id,soup):
            return

        trs = soup.find('table',{'class':'mod-data'}).findAll('tr')
        counter = 0
        #last_sequence = self.livedb.get_last_sequence(match_id)
        last_sequence = 0
        quarter = 0
        time = "1,12:00"
        home_score,away_score = None,None
        events = []
        for tr in trs:
            tds = tr.findAll('td')
            involved_players = []
            if not tds:
                continue
            if len(tds) == 4:
                time = str(quarter)+","+tds[0].text
                away_event = tds[1].text.replace('&nbsp;','').strip()
                vs = tds[2].text.replace('&nbsp;','').strip()
                vs = '-'.join(list(reversed(vs.split('-'))))
                home_score,away_score = vs.split('-')
                home_score,away_score = int(home_score),int(away_score)
                home_event = tds[3].text.replace('&nbsp;','').strip()
                score_change = []
                if tds[1].b:
                    score_change.append("away")
                if tds[3].b:
                    score_change.append("home")
                if home_event:
                    involved_players.extend( get_involved_players(home_event) )
                if away_event:
                    involved_players.extend( get_involved_players(away_event) )

                # bugfix 6962, boat and lakers share same city name
                # 29->快船
                # 24->湖人
                home_id,away_id,home_name,away_name = self.matchesdb.get_team_idnames(match_id)
                if home_id == 24:
                    home_name = u"湖人"
                if home_id == 29:
                    home_name = u"快船"
                if away_id == 24:
                    away_name = u"湖人"
                if away_id == 29:
                    away_name = u"快船"
                if (away_id,home_id) in [ (24,29), (29,24) ]:
                    home_event = home_event.replace(u"Los Angeles",home_name)
                    away_event = away_event.replace(u"Los Angeles",away_name)
                elif home_id in [24,29]:
                    home_event = home_event.replace(u"Los Angeles",home_name)
                elif away_id in [24,29]:
                    away_event = away_event.replace(u"Los Angeles",away_name)

                home_event = translate.translate(home_event)
                away_event = translate.translate(away_event)

                event = json.dumps( {"vs":vs,"home_event":home_event,"away_event":away_event,"score_change":score_change,"involved_players":involved_players} )
            elif len(tds) == 2:
                time = str(quarter)+","+tds[0].text
                match_event = tds[1].text.replace('&nbsp;','').strip()
                if match_event:
                    if "start" in match_event.lower():
                        quarter += 1
                    time = str(quarter)+","+tds[0].text
                    involved_players.extend( get_involved_players(match_event) )

                    # bugfix 6962, boat and lakers share same city name
                    # kuaichuan -> 29, huren ->24
                    home_id,away_id,home_name,away_name = self.matchesdb.get_team_idnames(match_id)
                    if home_id == 24:
                        home_name = u"湖人"
                    if home_id == 29:
                        home_name = u"快船"
                    if away_id == 24:
                        away_name = u"湖人"
                    if away_id == 29:
                        away_name = u"快船"
                    if (away_id,home_id) in [ (24,29), (29,24) ]:
                        match_event = match_event.replace(u"Los Angeles","")
                    elif home_id in [24,29]:
                        match_event = match_event.replace(u"Los Angeles",home_name)
                    elif away_id in [24,29]:
                        match_event = match_event.replace(u"Los Angeles",away_name)

                    match_event = translate.translate(match_event)

                event = json.dumps( {"match_event":match_event} )
            elif len(tds) == 1:
                self.logger.info("Meet label %s:%s"%(match_id,tds[0].text.replace('&nbsp;','').strip()))
                continue
            else:
                self.logger.warning("Format unrecognized %s"%tds)
                continue

            # skip database insertion if we know that there's `last_sequence` amount of data already in db
            counter += 1
            if counter <= last_sequence:
                continue

            #self.livedb.update(match_id,time,event)
            events.append((time,event))
        self.livedb.update_all(match_id,events)

        self._update_lehecai_live(match_id,time,home_score,away_score)
        self._update_match_status(match_id,soup)

        self.logger.info("Live Update match_id %s ended"%match_id)

    def update_standings(self):
        url = "http://espn.go.com/nba/standings/_/group/1"
        try:
            self.br.open(url,timeout=10)
            page = self.br.response().read()
        except Exception,e:
            self.logger.error("Unable to read standing data: %s"%repr(e))
            return

        soup = BeautifulSoup.BeautifulSoup(page)
        trs = soup.findAll('tr',{'class':re.compile('team-\d+-\d+')})
        rank = 0
        for tr in trs:
            tds = tr.findAll('td')
            rank += 1
            team_name = tds[0].a.get('href').rsplit('-',1)[-1].capitalize()
            #team_name = translate.translate(team_name)
            flag = ""
            won = parse_int(tds[1].text)
            lost = parse_int(tds[2].text)
            win_rate = float(tds[3].text)*100
            gb = tds[4].text.replace('&nbsp;','').replace('&#189;',r'.5').strip()
            if gb == '.5':
                gb = '0.5'
            home = tds[5].text
            road = tds[6].text
            div = tds[7].text
            conf = tds[8].text
            pf = float(tds[9].text)
            pa = float(tds[10].text)
            diff = float(tds[11].text)
            strk = tds[12].text.split(' ')[-1]
            try:
                strk = -parse_int(strk) if  tds[12].text.startswith("Lost") else parse_int(strk)
            except:
                strk = 0
            last_ten = tds[13].text
            self.standingsdb.update(team_name,flag,won,lost,win_rate,gb,home,road,div,conf,pf,pa,diff,strk,last_ten,rank)

    def update_leads(self,match_id):
        # away vses home
        vses = self.matchlivedb.get_all_vs(match_id)
        try:
            max_away_lead = max( [ x[0]-x[1] for x in vses ] )
            max_home_lead = max( [ x[1]-x[0] for x in vses ] )
        except:
            # empty live scores, do not update
            return
        self.matchesdb.update_leads(match_id,max_away_lead,max_home_lead)


################################################
# Help functions that should be deleted sometime
################################################
def update_match(match_id):
    espn = EspnNbaUpdater()
    espn.update_livedb(match_id)
    espn.update_boxscore(match_id)
    espn.update_leads(match_id)
    espn.update_standings()

def update_schedule(ran=range(0,14)):
    EspnNbaUpdater().update_schedule(ran)

if __name__ == "__main__":
    import sys
    if len(sys.argv) == 3:
        if sys.argv[1] == 'schedule':
            days = int(sys.argv[2])
            from multiprocessing import Pool
            p = Pool(20)
            results = []
            for i in range(0,days):
                results.append( p.apply_async(update_schedule,[range(i,i+1)]) )
            p.close()
            p.join()
            for r in results:
                r.wait()
        elif sys.argv[1] == 'match':
            days_from,days_to = sys.argv[2].split('~')
            days_from,days_to = int(days_from),int(days_to)
            espn = EspnNbaUpdater()
            data = espn.matchesdb.get_recent_match_info(hours=24*(max(abs(days_from),days_to)))
            for d in data:
                match_id,china_time = d.items()[0]
                if china_time>=datetime.now()-timedelta(days=abs(days_from)) and china_time <= datetime.now()+timedelta(days=abs(days_to)):
                    espn.update_livedb(match_id)
                    espn.update_boxscore(match_id)
    elif len(sys.argv) == 2:
        update_match(int(sys.argv[1]))
    else:
        print "usage:"
        print
        print "python2.7 espnnba_updater.py 19810"
        print "python2.7 espnnba_updater.py schedule 365"
        print "python2.7 espnnba_updater.py match -7~7"
