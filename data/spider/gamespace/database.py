#coding:utf-8
from settings import *

import json
import utils
import logging
logger = logging.getLogger(__name__)
logger.setLevel(LOG_LEVEL)

import threading
import MySQLdb
from datetime import datetime,timedelta


#######################################
# DB initialization
#######################################
LOCK = threading.Lock()

DB = None

def get_cursor():
    global DB
    if not DB:
        DB = MySQLdb.connect(host=DB_HOST,port=DB_PORT,user=DB_USERNAME,passwd=DB_PASSWORD,db=DB_DATABASE,charset="utf8")
    try:
        return DB.cursor()
    except:
        DB = MySQLdb.connect(host=DB_HOST,port=DB_PORT,user=DB_USERNAME,passwd=DB_PASSWORD,db=DB_DATABASE,charset="utf8")
        return DB.cursor()

OLD_DB = None

def get_cursor_old():
    global OLD_DB
    if not OLD_DB:
        OLD_DB = MySQLdb.connect(host=OLD_DB_HOST,port=OLD_DB_PORT,user=OLD_DB_USERNAME,passwd=OLD_DB_PASSWORD,db=OLD_DB_DATABASE,charset="utf8",connect_timeout=1)
    try:
        return OLD_DB.cursor()
    except:
        OLD_DB = MySQLdb.connect(host=OLD_DB_HOST,port=OLD_DB_PORT,user=OLD_DB_USERNAME,passwd=OLD_DB_PASSWORD,db=OLD_DB_DATABASE,charset="utf8",connect_timeout=1)
        return OLD_DB.cursor()

######################################
# Help Functions
######################################
def database_fallback(func):
    '''a decorator that unifies the default database fallback behaviour
    '''
    def fallback(*args):
        try:
            return func(*args)
        except Exception,e:
            logger.error(repr(e))
            return None
    return fallback

########################################
# Errors and Exceptions
########################################
class NotFound(Exception):
    pass

class FormatError(Exception):
    pass


########################################
# Classes
########################################
class StadiumsDB:
    def __init__(self):
        self.logger = logging.getLogger(__name__+'|TeamsDB')
        self.logger.setLevel(LOG_LEVEL)
        self.table = DB_STADIUMS

    def create(self,stadium_name,city,state):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''insert into '''+self.table+''' (name,eng_name,city,state) values (%s,%s,%s,%s)''',(stadium_name,stadium_name,city,state))
                insert_id = DB.insert_id()
                DB.commit()
                c.close()
                return DB.insert_id()
            except:
                return 0

    def get_id(self,stadium_name,city,state):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select id from '''+self.table+''' where eng_name=%s''',(stadium_name,))
                stadium_id = c.fetchone()
                stadium_id = stadium_id[0] if stadium_id else 0
            except:
                stadium_id = 0
        if not stadium_id:
            stadium_id = self.create(stadium_name,city,state)
        return stadium_id

    def get_translations(self):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select name,eng_name from '''+self.table)
                return dict( [ (x[1],x[0]) for x in c.fetchall() if x[0] and x[1] ] )
            except:
                return {}

class TeamsDB:
    def __init__(self):
        self.logger = logging.getLogger(__name__+'|TeamsDB')
        self.logger.setLevel(LOG_LEVEL)
        self.table = DB_TEAMS

    def create(self,team_name):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''insert into '''+self.table+''' (fid,name,eng_name,full_name,eng_full_name,bbr,logo,home,homepage_link,intro) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)''',(0,team_name,team_name,team_name,team_name,team_name,"",team_name,"http://www.hoopchina.com","This is a new team, create its info ASAP!!!"))
                insert_id = DB.insert_id()
                DB.commit()
                c.close()
                return insert_id
            except:
                return 0

    def get_id(self,team_name,column_name='eng_name'):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select id from '''+self.table+''' where '''+column_name+'''=%s''',(team_name,))
                team_id = c.fetchone()
                team_id = team_id[0] if team_id else 0
            except:
                team_id = 0
        if not team_id:
            self.logger.error("Unknown team name [%s], will create a fake one at database!!"%team_name)
            return self.create(team_name)
        return team_id

    def get_translations(self):
        c = get_cursor()
        with LOCK:
            c.execute('''select name,eng_name from '''+self.table)
            return dict( [ (x[1],x[0]) for x in c.fetchall() if x[0] and x[1] ] )

    def get_city_translations(self):
        c = get_cursor()
        with LOCK:
            c.execute('''select name,city from '''+self.table)
            d =  dict( [ (x[1],x[0]) for x in c.fetchall() if x[0] and x[1] ] )
            return d

class PlayersDB:
    def __init__(self):
        self.logger = logging.getLogger(__name__+'|PlayersDB')
        self.logger.setLevel(LOG_LEVEL)
        self.table = DB_PLAYERS

    def create(self,player_name,espn_player_id):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''insert into '''+self.table+''' (name,eng_name,first_name,last_name,espn_id,espn_name) values (%s,%s,%s,%s,%s,%s)''',(player_name.replace(' ','-'),player_name,player_name.split(' ')[0],player_name.split(' ')[-1],espn_player_id,player_name))
                insert_id = DB.insert_id()
                DB.commit()
                c.close()
                return insert_id
            except:
                return 0

    def get_id(self,player_name,espn_player_id):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select id from '''+self.table+''' where eng_name=%s''',(player_name,))
                player_id = c.fetchone()
                player_id = player_id[0] if player_id else 0
            except:
                player_id = 0
        if not player_id:
            self.logger.error("Unknown player name [%s], will create a fake one at database!!"%player_name)
            player_id = self.create(player_name,espn_player_id)
        return player_id

    def get_translations(self):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select name,eng_name from '''+self.table)
                return dict( [ (x[1],x[0]) for x in c.fetchall() if x[0] and x[1] ] )
            except:
                return {}

    def get_idname_dict(self):
        d = {}
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select eng_name,id,name,espn_id from '''+self.table)
                for k,id,name,espn_id in c.fetchall():
                    d[k] = {str(id):[unicode(name),k,espn_id]}
                return d
            except:
                return {}

class StandingsDB:
    def __init__(self):
        self.logger = logging.getLogger(__name__+'|StandingsDB')
        self.logger.setLevel(LOG_LEVEL)
        self.table = DB_STANDINGS

    def update(self,team_name,flag,won,lost,win_rate,gb,home,road,div,conf,pf,pa,diff,strk,last_ten,rank):
        matchesdb = MatchesDB()
        season,season_type = matchesdb.get_latest_season_info()
        teamsdb = TeamsDB()
        team_id = teamsdb.get_id(team_name)

        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select * from '''+self.table+''' where team_id=%s and season=%s and season_type=%s''',(team_id,season,season_type))
                if c.fetchone():
                    c.execute('''update '''+self.table+''' set season=%s,season_type=%s,flag=%s,won=%s,lost=%s,win_rate=%s,gb=%s,home=%s,road=%s,`div`=%s,conf=%s,pf=%s,pa=%s,diff=%s,strk=%s,last_ten=%s,rank=%s where team_id=%s and season=%s and season_type=%s''',(season,season_type,flag,won,lost,win_rate,gb,home,road,div,conf,pf,pa,diff,strk,last_ten,rank,team_id,season,season_type))
                else:
                    c.execute('''replace into '''+self.table+''' (team_id,season,season_type,flag,won,lost,win_rate,gb,home,road,`div`,conf,pf,pa,diff,strk,last_ten,rank) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)''',(team_id,season,season_type,flag,won,lost,win_rate,gb,home,road,div,conf,pf,pa,diff,strk,last_ten,rank))
                DB.commit()
                self.logger.debug("update Standings for team_id %s"%team_id)
                c.close()
            except:
                DB.rollback()

class PlayerMatchStatsDB:
    def __init__(self):
        self.logger = logging.getLogger(__name__+'|PlayerMatchStatsDB')
        self.logger.setLevel(LOG_LEVEL)
        self.table = DB_PLAYERMATCHSTATS

    def get_recent_player_ids(self):
        c = get_cursor()
        with LOCK:
            try:
                t1 = datetime.now() - timedelta(days=1)
                t2 = datetime.now() + timedelta(days=1)
                c.execute('''select player_id from '''+self.table+''' where china_time>%s and china_time<%s''',(t1,t2))
                hoop_ids = [ x[0] for x in c.fetchall() ]
                return hoop_ids
            except:
                DB.rollback()

    def update(self,player_name,team_name,match_id,mins,pts,fga,fgm,tpa,tpm,fta,ftm,dreb,oreb,reb,ast,stl,blk,to,pf,dnp,position,pfa,is_starter,net_points,espn_player_id):
        playersdb = PlayersDB()
        player_id = playersdb.get_id(player_name,espn_player_id)
        teamsdb = TeamsDB()
        team_id = teamsdb.get_id(team_name)
        if player_id == 0:
            return

        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select id from '''+self.table+''' where match_id=%s and player_id=%s''',(match_id,player_id))
                pms_id = c.fetchone()
                c.execute('''select usa_time,china_time,season,match_type from '''+DB_MATCHES+''' where id=%s''',(match_id,))
                try:
                    usa_time,china_time,season,match_type = c.fetchone()
                except:
                    self.error("match_id of %s does not exist!, must have error in database")
                    usa_time = china_time = season = match_type = "ERROR"
                if not pms_id:
                    c.execute('''replace into '''+self.table+''' (player_id,player_name,team_id,team_name,match_id,usa_time,china_time,season,match_type,mins,pts,fga,fgm,tpa,tpm,fta,ftm,dreb,oreb,reb,asts,stl,blk,`to`,pf,dnp,position,pfa,is_starter,net_points) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)''',(player_id,player_name,team_id,team_name,match_id,usa_time,china_time,season,match_type,mins,pts,fga,fgm,tpa,tpm,fta,ftm,dreb,oreb,reb,ast,stl,blk,to,pf,dnp,position,pfa,is_starter,net_points))
                else:
                    c.execute('''update '''+self.table+''' set mins=%s,pts=%s,fga=%s,fgm=%s,tpa=%s,tpm=%s,fta=%s,ftm=%s,dreb=%s,oreb=%s,reb=%s,asts=%s,stl=%s,blk=%s,`to`=%s,pf=%s,dnp=%s,position=%s,pfa=%s,is_starter=%s,net_points=%s where id=%s''',(mins,pts,fga,fgm,tpa,tpm,fta,ftm,dreb,oreb,reb,ast,stl,blk,to,pf,dnp,position,pfa,is_starter,net_points,pms_id[0]))
                self.logger.debug("Updating Players [%s's] Stats @ Team [%s] @ [EastTime %s]"%(player_name,team_name,usa_time))
                DB.commit()
                c.close()
            except:
                DB.rollback()

class MatchStatsDB:
    def __init__(self):
        self.logger = logging.getLogger(__name__+'|MatchStatsDB')
        self.logger.setLevel(LOG_LEVEL)
        self.table = DB_MATCHSTATS

    def update(self,match_id,team_name, mins, pts, fga, fgm, tpa, tpm, fta, ftm, dreb, oreb, reb, ast, stl, to, pf, blk, fast_scores, paint_scores, first_scores, second_scores, third_scores, fourth_scores, ot_scores, team_to, points_off):
        teamsdb = TeamsDB()
        team_id = teamsdb.get_id(team_name)

        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select id from '''+self.table+''' where match_id=%s and team_id=%s''',(match_id,team_id))
                ms_id = c.fetchone()
                c.execute('''select usa_time,china_time,season,match_type from '''+DB_MATCHES+''' where id=%s''',(match_id,))
                try:
                    usa_time,china_time,season,match_type = c.fetchone()
                except:
                    self.error("match_id of %s does not exist!, must have error in database")
                    usa_time = china_time = season = match_type = "ERROR"

                try:
                    c.execute('''select home_biggest_lead,home_team_id,away_biggest_lead,away_team_id from '''+DB_MATCHES+''' where id=%s''',(match_id,))
                    home_lead,home_id,away_lead,away_id = c.fetchone()
                    if home_id==team_id:
                        max_leader = home_lead
                    else:
                        max_leader = away_lead
                except Exception,e:
                    self.logger.error(repr(e))
                    max_leader = 0

                if not ms_id:
                    c.execute('''insert into '''+self.table+''' (match_id,team_id,team_name,usa_time,china_time,season,match_type,mins,pts,fga,fgm,tga,tgm,fta,ftm,dreb,oreb,reb,ast,st,`to`,pf,blk,fast_scores,paint_scores,max_leader,first_scores,second_scores,third_scores,fourth_scores,ot_scores,team_to,points_off) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)''',(match_id,team_id,team_name,usa_time,china_time,season,match_type,mins,pts,fga,fgm,tpa,tpm,fta,ftm,dreb,oreb,reb,ast,stl,to,pf,blk,fast_scores,paint_scores,max_leader,first_scores,second_scores,third_scores,fourth_scores,ot_scores,team_to,points_off))
                else:
                    c.execute('''update '''+self.table+''' set team_name=%s,usa_time=%s,china_time=%s,season=%s,match_type=%s,mins=%s,pts=%s,fga=%s,fgm=%s,tga=%s,tgm=%s,fta=%s,ftm=%s,dreb=%s,oreb=%s,reb=%s,ast=%s,st=%s,blk=%s,`to`=%s,pf=%s,fast_scores=%s,paint_scores=%s,max_leader=%s,first_scores=%s,second_scores=%s,third_scores=%s,fourth_scores=%s,ot_scores=%s,team_to=%s,points_off=%s where id=%s''',(team_name,usa_time,china_time,season,match_type,mins,pts,fga,fgm,tpa,tpm,fta,ftm,dreb,oreb,reb,ast,stl,blk,to,pf,fast_scores,paint_scores,max_leader,first_scores,second_scores,third_scores,fourth_scores,ot_scores,team_to,points_off,ms_id[0]))
                DB.commit()
                self.logger.debug("Updating match stats %s @ %s"%(team_name,usa_time))
                c.close()
            except:
                DB.rollback()

class MatchesDB:
    def __init__(self):
        self.logger = logging.getLogger(__name__+'|MatchesDB')
        self.logger.setLevel(LOG_LEVEL)
        self.table = DB_MATCHES
        self.status = { "PENDING":3,"IN_PROGRESS":2,"FINISHED":1,"CANCELLED":4 }

    def get_team_idnames(self,match_id):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select '''+DB_TEAMS+'''.id,name from '''+self.table+','+DB_TEAMS+''' where '''+self.table+'''.id=%s and '''+self.table+'''.home_team_name='''+DB_TEAMS+'''.eng_name''',(match_id,))
                home_id,home_name = c.fetchone()
                c.execute('''select '''+DB_TEAMS+'''.id,name from '''+self.table+','+DB_TEAMS+''' where '''+self.table+'''.id=%s and '''+self.table+'''.away_team_name='''+DB_TEAMS+'''.eng_name''',(match_id,))
                away_id,away_name = c.fetchone()
            except:
                home_id,away_id = 0,0
                home_name,away_name = "",""
            return home_id,away_id,home_name,away_name

    def get_espn_id(self,match_id):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select espn_id from '''+self.table+''' where id=%s''',(match_id,))
                espn_id = c.fetchone()
                espn_id = espn_id[0] if espn_id else 0
                c.close()
                return espn_id
            except:
                return 0

    def get_hoop_id(self,espn_id):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select id from '''+self.table+''' where espn_id=%s''',(espn_id,))
                hoop_id = c.fetchone()
                hoop_id = hoop_id[0] if hoop_id else 0
                c.close()
                return hoop_id
            except:
                return 0

    def get_lehecai_id(self,match_id):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select lehecai_id from '''+self.table+''' where id=%s''',(match_id,))
                lehecai_id = c.fetchone()
                lehecai_id = lehecai_id[0] if lehecai_id else 0
                c.close()
                return lehecai_id
            except:
                return 0

    def get_match_id(self,usa_time,home_team_id,away_team_id):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select match_id from '''+self.table+''' where usa_time=%s and home_team_id=%s and away_team_id=%s''',(usa_time,home_team_id,away_team_id))
                match_id = c.fetchone()
                match_id = match_id[0] if match_id else 0
                c.close()
                return match_id
            except:
                DB.rollback()

    def get_match_usa_time(self,match_id):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select usa_time from '''+self.table+''' where match_time=%s''',(match_id,))
                usa_time = c.fetchone()
                usa_time = usa_time[0] if usa_time else utils.EastTime().now()
                return usa_time
            except:
                return ""

    def get_recent_match_info(self,hours=24):
        et1 = utils.EastTime().now(hour_delta=-hours)
        et2 = utils.EastTime().now(hour_delta=hours)
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select id,china_time,usa_time from '''+self.table+''' where usa_time>=%s and usa_time <=%s''',(et1,et2))
                return [ {x[0]:(x[1],x[2])} for x in c.fetchall() ]
            except:
                return []

    def get_latest_season_info(self):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select season,match_type from '''+self.table+''' where usa_time<=%s order by usa_time desc limit 1''',(utils.EastTime().now(),))
                try:
                    season,match_type = c.fetchone()
                except:
                    season,match_type = 2011,"REGULAR"
                return season,match_type
            except:
                DB.rollback()

    def is_fetched(self,match_id):
        return self.get_match_status(match_id) == 1

    def get_match_status(self,match_id):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('select status from '''+self.table+''' where id=%s''',(match_id,))
                status =  c.fetchone()
                status = status[0] if status else 3
                return status
            except:
                return 3

    def set_match_status(self,match_id,status="FINISHED"):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''update '''+self.table+''' set status=%s where id=%s''',(self.status.get(status,3),match_id))
                DB.commit()
                c.close()
                return True
            except:
                return False

    def update_schedule(self,away_team_name,home_team_name,east_time,espn_id,season,match_type):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select id from '''+self.table+''' where espn_id=%s''',(espn_id,))
                match_id = c.fetchone()
                match_id = match_id[0] if match_id else 0
            except Exception,e:
                DB.rollback()
        if match_id:
            espn_id = int(espn_id)
            home_team_id = TeamsDB().get_id(home_team_name)
            away_team_id = TeamsDB().get_id(away_team_name)
            china_time = east_time.cn()
            usa_time = east_time.et()
            with LOCK:
                try:
                    c.execute('''update '''+self.table+''' set espn_id=%s,home_team_id=%s,home_team_name=%s,away_team_id=%s,away_team_name=%s,usa_time=%s,china_time=%s,season=%s,match_type=%s where id=%s''',(espn_id,home_team_id,home_team_name,away_team_id,away_team_name,usa_time,china_time,season,match_type,match_id))
                    DB.commit()
                    self.logger.debug("Update schedule of match id %s, %s vs %s @ %s"%(match_id,home_team_name,away_team_name,china_time))
                    return match_id
                except Exception,e:
                    DB.rollback()
        else:
            status = 3
            return self.update(espn_id,status,home_team_name,0,away_team_name,0,0,east_time,season,match_type,"","0:00","1,12:00",0,0,0,0,0,0)

    def update_stadium_info(self,espn_id,stadium_name,city,state):
        stadiumsdb = StadiumsDB()
        stadium_id = stadiumsdb.get_id(stadium_name,city,state)
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''update '''+self.table+''' set stadium_id=%s where espn_id=%s''',(stadium_id,espn_id))
                DB.commit()
                self.logger.debug("assign stadium %s to espn_id %s"%(stadium_name,espn_id))
                c.close()
            except:
                DB.rollback()

    def update_lehecai_info(self,datestr,away_team_full_name,home_team_full_name,lehecai_id):
        teamsdb = TeamsDB()
        away_team_id = teamsdb.get_id(away_team_full_name,'full_name')
        home_team_id = teamsdb.get_id(home_team_full_name,'full_name')

        c = get_cursor()
        with LOCK:
            try:
                c.execute('''update '''+self.table+''' set lehecai_id=%s where china_time>=DATE(%s)-INTERVAL 1 DAY and china_time<=DATE(%s)+INTERVAL 1 DAY and away_team_id=%s and home_team_id=%s''',(lehecai_id,datestr,datestr,away_team_id,home_team_id))
                away_team_id,home_team_id = home_team_id,away_team_id
                c.execute('''update '''+self.table+''' set lehecai_id=%s where china_time>=DATE(%s)-INTERVAL 1 DAY and china_time<=DATE(%s)+INTERVAL 1 DAY and away_team_id=%s and home_team_id=%s''',(lehecai_id,datestr,datestr,away_team_id,home_team_id))
                DB.commit()
                c.execute('''select id from '''+self.table+''' where lehecai_id=%s''',(lehecai_id,))
                match_id = c.fetchone()
                if match_id:
                    self.logger.debug("assign lehecai_id %s to match_id %s"%(lehecai_id,match_id[0]))
                c.close()
            except:
                DB.rollback()

    def update_lehecai_live(self,match_id,match_time,away_score,home_score):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select away_team_id,home_team_id,china_time from '''+self.table+''' where id=%s''',(match_id,))
                away_team,home_team,china_time = c.fetchone()

                # update home_score/away_score by hoopMatchStats
                c.execute('''select pts from '''+DB_MATCHSTATS+''' where team_id=%s and match_id=%s''',(away_team,match_id))
                away_score2 = c.fetchone()
                away_score2 = away_score2[0] if away_score2 else 0
                c.execute('''select pts from '''+DB_MATCHSTATS+''' where team_id=%s and match_id=%s''',(home_team,match_id))
                home_score2 = c.fetchone()
                home_score2 = home_score2[0] if home_score2 else 0
                home_socre = home_score2
                away_score = away_score2

                #TODO if score2 differs from score, we should do *REAL* lehecai update
                c.execute('''update '''+self.table+''' set home_score=%s,away_score=%s,match_time=%s where id=%s''',(home_score,away_score,match_time,match_id))
                
                if away_score<home_score:
                    home_lead = home_score - away_score
                    c.execute('''update '''+self.table+''' set home_biggest_lead=%s where home_biggest_lead<%s and id=%s''',(home_lead,home_lead,match_id))
                else:
                    away_lead = away_score - home_score
                    c.execute('''update '''+self.table+''' set away_biggest_lead=%s where away_biggest_lead<%s and id=%s''',(away_lead,away_lead,match_id))
                DB.commit()
                self.logger.debug("update match_id=%s, %s:%s"%(match_id,away_score,home_score))

                c.close()
            except Exception,e:
                self.logger.error(repr(e))
                DB.rollback()
        # update old database
        try:
            with LOCK:
                c = get_cursor_old()
                c.execute('''update hoop_match set home_score=%s,away_score=%s where away_team=%s and home_team=%s and date(match_china_time)=date(%s)''',(home_score,away_score,away_team,home_team,china_time))
                OLD_DB.commit()
        except Exception,e:
            self.logger.error(repr(e))

    def update_leads(self,match_id,max_away_lead,max_home_lead):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''update '''+self.table+''' set home_biggest_lead=%s,away_biggest_lead=%s where id=%s''',(max_home_lead,max_away_lead,match_id))
                DB.commit()
                self.logger.debug("update max_leads away=%s home=%s"%(max_away_lead,max_home_lead))
                c.close()
            except:
                DB.rollback()

    def update_match_time_and_leads(self,match_id,match_time,away_lead,home_lead):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select id from '''+self.table+''' where id=%s''',(match_id,))
                if c.fetch_one():
                    DB.commit()
                else:
                    raise NotFound("There's no record in %s with id=%s"%(self.table,match_id))
            except:
                DB.rollback()

    def update_match_info(self,match_id,attendance,game_time,away_fast,home_fast,away_paint,home_paint):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''update '''+self.table+''' set attendance=%s, game_time=%s, home_fast=%s, away_fast=%s, home_paint=%s, away_paint=%s where id=%s''',(attendance,game_time,home_fast,away_fast,home_paint,away_paint,match_id))
                DB.commit()
                self.logger.debug("Updating match info to match_id %s"%match_id)
                c.close()
            except:
                DB.rollback()

    def update(self,espn_id,status,home_team_name,home_score,away_team_name,away_score,attendance,east_time,season,match_type,memo,game_time,match_time,home_fast,away_fast,home_biggest_lead,away_biggest_lead,home_paint,away_paint):
        espn_id = int(espn_id)
        teamsdb = TeamsDB()
        home_team_id,away_team_id = teamsdb.get_id(home_team_name),teamsdb.get_id(away_team_name)
        if not isinstance(east_time,utils.EastTime):
            raise FormatError("Not a valid EastTime instance: %s"%east_time)
        china_time = east_time.cn()
        usa_time = east_time.et()

        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select id from '''+self.table+''' where espn_id=%s''',(espn_id,))
                match_id = c.fetchone()
                if match_id:
                    match_id = match_id[0]
                    c.execute('''update '''+self.table+''' set lehecai_id=%s, stadium_id=%s, status=%s, home_team_id=%s, home_team_name=%s, home_score=%s, away_team_id=%s, away_team_name=%s, away_score=%s, attendance=%s, china_time=%s, usa_time=%s, season=%s, match_type=%s, memo=%s, game_time=%s, match_time=%s, home_fast=%s, away_fast=%s, home_biggest_lead=%s, away_biggest_lead=%s, home_paint=%s, away_paint=%s where espn_id=%s''',(0,0,status,home_team_id,home_team_name,home_score,away_team_id,away_team_name,away_score,attendance,china_time,usa_time,season,match_type,memo,game_time,match_time,home_fast,away_fast,home_biggest_lead,away_biggest_lead,home_paint,away_paint,espn_id))
                else:
                    c.execute('''insert into '''+self.table+''' (espn_id,lehecai_id,stadium_id,status,home_team_id,home_team_name,home_score,away_team_id,away_team_name,away_score,attendance,china_time,usa_time,season,match_type,memo,game_time,match_time,home_fast,away_fast,home_biggest_lead,away_biggest_lead,home_paint,away_paint) values (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)''',(espn_id,0,0,status,home_team_id,home_team_name,home_score,away_team_id,away_team_name,away_score,attendance,china_time,usa_time,season,match_type,memo,game_time,match_time,home_fast,away_fast,home_biggest_lead,away_biggest_lead,home_paint,away_paint) )
                    match_id = DB.insert_id()
                DB.commit()
                c.close()
            except:
                DB.rollback()
            return match_id

class MatchLiveDB:
    def __init__(self):
        self.logger = logging.getLogger(__name__+'|MatchLiveDB')
        self.logger.setLevel(LOG_LEVEL)
        self.table = DB_MATCHLIVE

    def get_all_vs(self,match_id):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select event from '''+self.table+''' where match_id=%s''',(match_id,))
                vses = []
                for event in c.fetchall():
                    j = json.loads(event[0])
                    home,away = j.get("vs","0-0").split('-')
                    away,home = int(away),int(home)
                    vses.append((away,home))
                return vses
            except:
                DB.rollback()

    def is_finished(self,match_id):
        c = get_cursor()
        result = False
        with LOCK:
            try:
                c.execute('''select match_time from '''+self.table+''' where match_id=%s order by sequence_id desc limit 1''',(match_id,))
                t = c.fetchone()
                if not t:
                    self.logger.debug("match_time not found, is_finished will set to false")
                    return result
                t = t[0]
                quarter,matchtime = t.split(',')
                if quarter >= '4' and matchtime == '0:00':
                    result=True
            except Exception,e:
                self.logger.error(repr(e))
        return result

    @database_fallback
    def get_last_sequence(self,match_id):
        '''return lastest time for the specific match_id

        in mysql, it is better to have (match_id,sequence_id) indexed
        '''
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select max(sequence_id) from '''+self.table+''' where match_id=%s''',(match_id,))
                seq = c.fetchone()[0]
                seq = seq if seq else 0
                self.logger.debug("get last sequence id %s of match id %s"%(seq,match_id))
                if seq>5:
                    seq -= 5
                return seq
            except:
                return 0

    @database_fallback
    def reset_match(self,match_id):
        '''reset database for match_id'''
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''delete from '''+self.table+''' where match_id=%s''',(match_id,))
                DB.commit()
                c.close()
                self.logger.debug("match_id %s reseted."%match_id)
            except:
                DB.rollback()

    def reset_sequence(self,match_id,sequence_id):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''delete from '''+self.table+''' where match_id=%s and sequence_id>=%s''',(match_id,sequence_id))
                DB.commit()
                c.close()
                self.logger.debug("match_id %s reseted."%match_id)
            except:
                DB.rollback()

    def update_all(self,match_id,events):
        def calc(t):
            import operator
            return sum(map(operator.mul,[int(x) for x in t.split(':')],[60,1]))
        c = get_cursor()
        events_seq = []
        counter = 1
        last_time = "12:00"
        last_quarter = "1"
        for e in events:
            quarter_time = str(e[0]).split(',',1)[-1]
            quarter = e[0].split(',',1)[0]
            if quarter<last_quarter and calc(last_time)<calc(quarter_time):
                continue
            events_seq.append( (match_id,counter,str(e[0]),str(e[1])) )
            self.logger.debug("%s,%s,%s,%s"% (match_id,counter,e[0],e[1]))
            last_time = quarter_time
            last_quarter = quarter
            counter += 1
        with LOCK:
            try:
                c.execute('''delete from '''+self.table+''' where match_id=%s''',(match_id,))
                for e in events_seq:
                    c.execute('insert into '''+self.table+''' (match_id,sequence_id,match_time,event) values (%s,%s,%s,%s)''',e)
                DB.commit()
            except Exception,e:
                self.logger.error(repr(e))
                DB.rollback()

    @database_fallback
    def update(self,match_id,time,event):
        '''update live broadcasting into database

        if event exists in table, skip;
        otherwise do the insert.

        in mysql database, it is better to have (match_id,time) indexed
        '''
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select event from '''+self.table+''' where match_id=%s and match_time=%s''',(match_id,time))
                data = [json.loads(x).get('involved_players') for x in c.fetchall()]
            except:
                data = []

        event = json.loads(event)
        if event.get('involved_players',{"0":[0,0]}) not in data:
            event = json.dumps(event)
            with LOCK:
                try:
                    c.execute('''select count(sequence_id) from '''+self.table+''' where match_id=%s''',(match_id,))
                    next_id = c.fetchone()[0]
                    next_id = next_id+1 if next_id else 1
                    c.execute('''insert into '''+self.table+''' (match_id,sequence_id,match_time,event) values (%s,%s,%s,%s)''',(match_id,next_id,time,event))
                    DB.commit()
                    c.close()
                    self.logger.debug("insert into %s where match_id=%s, sequence_id=%s, match_time=%s, event=%s"%(self.table,match_id,next_id,time,event))
                except Exception,e:
                    self.logger.error(repr(e))
                    DB.rollback()

class SettingsDB:
    def __init__(self):
        self.logger = logging.getLogger(__name__+'|MatchesDB')
        self.logger.setLevel(LOG_LEVEL)
        self.table = DB_SETTINGS

    def need_fetch(self, usa_date):
        c = get_cursor()
        with LOCK:
            try:
                c.execute('''select allstar_date from '''+self.table+''' where allstar_date=date(%s)''',(usa_date,))
                if c.fetchone():
                    return False
                else:
                    return True
            except Exception,e:
                self.logger.error(repr(e))
    

class StatsDB:
    def __init__(self):
        self.logger = logging.getLogger(__name__+'|MatchesDB')
        self.logger.setLevel(LOG_LEVEL)
        self.table = DB_STATS

    def update(self, name, value):
        c = get_cursor()
        if isinstance(value,unicode):
            value = value.encode('utf-8')
        with LOCK:
            try:
                c.execute('''replace into '''+self.table+''' (name,value) values (%s,%s)''', (name,value))
                DB.commit()
            except Exception,e:
                self.logger.error(repr(e))
                DB.rollback()

