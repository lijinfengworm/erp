#coding:utf-8
from settings import *
TIMEOUT = 10

import re
import json
import utils
import logging
import database
import translate

import mechanize
from BeautifulSoup import BeautifulSoup as soup

class PlayerStatsUpdater:
    def __init__(self):
        self.br = mechanize.Browser()
        self.br.set_handle_robots(False)
        self.logger = logging.getLogger(__name__)
        self.logger.setLevel(LOG_LEVEL)
        self.statsdb = database.StatsDB()

    def update_all(self,concurrent=10):
        hoop_ids = database.PlayerMatchStatsDB().get_recent_player_ids()
        for i in range((len(hoop_ids)-1)/concurrent+1):
            for j in range(i*concurrent,min(i*concurrent+concurrent,len(hoop_ids))):
                for crawler in ['update_last5','update_gamelog','update_career','update_profile']:
                    try:
                        getattr(self,crawler)(hoop_ids[j])
                    except Exception,e:
                        self.logger.error(e)

    def fetch_stats(self, hoop_id, stats_url = None):
        self.logger.info('updating hoop player id %s on stats' % hoop_id )
        chinese_name, english_name, espn_id = translate.playerid_name[str(hoop_id)]

        player_stats_url = (stats_url or 'http://espn.go.com/nba/player/stats/_/id/%s') % str(espn_id)

        self.logger.debug('fetching %s: [%s,%s,%s]' % (player_stats_url, chinese_name, english_name, espn_id))

        try:
            self.br.open(player_stats_url, timeout=TIMEOUT)
            html = self.br.response().read()
        except Exception,e:
            self.logger.error(repr(e))
            html = ''
        
        try:
            s = soup(html)
        except Exception,e:
            self.logger.error(repr(e))
            s = ''

        return s

    def fetch_gamelog(self, hoop_id):
        self.logger.info('updating hoop player id on gamelog %s' % hoop_id )

        chinese_name, english_name, espn_id = translate.playerid_name[str(hoop_id)]
        season = utils.get_current_season()

        player_gamelog_url = 'http://espn.go.com/nba/player/gamelog/_/id/' + str(espn_id)

        self.logger.debug('fetching %s: [%s,%s,%s,%s]' % (player_gamelog_url, chinese_name, english_name, espn_id, season))

        try:
            self.br.open(player_gamelog_url, timeout=TIMEOUT)
            html = self.br.response().read()
        except Exception,e:
            self.logger.error(repr(e))
            html = ''

        try:
            s = soup(html)
        except Exception,e:
            self.logger.error(repr(e))
            s = ''
        
        return s

    def fetch_profile(self, hoop_id):
        self.logger.info('updating hoop player id on profile %s'% hoop_id)

        chinese_name, english_name, espn_id = translate.playerid_name[str(hoop_id)]
        season = utils.get_current_season()

        player_profile_url = 'http://espn.go.com/nba/player/_/id/' + str(espn_id)
        
        self.logger.debug('fetching %s: [%s,%s,%s,%s]' % (player_profile_url, chinese_name, english_name, espn_id, season))
        
        try:
            self.br.open(player_profile_url, timeout=TIMEOUT)
            html = self.br.response().read()
        except Exception,e:
            self.logger.error(repr(e))
            html = ''

        try:
            s = soup(html)
        except Exception,e:
            self.logger.error(repr(e))
            s = ''

        return s

    def parse_profile(self, s):
        comment1 = [u'#统计,场次,上场时间,投篮,命中率,三分,三分命中率,罚球,罚球命中率,篮板,助攻,盖帽,抢断,犯规,失误,得分']
        comment2 = [u'#赛季,球队,场次,首发,上场时间,投篮,投篮命中率,三分,三分命中率,罚球,罚球命中率,进攻板,防守板,篮板,助攻,盖帽,抢断,犯规,失误,得分']
        
        div = s.find('div',{'class':'mod-container mod-table mod-no-footer'})
        try:
            if div.h4.text != "STATS":
                return
        except:
            pass

        trs = div.findAll('tr',{'class':re.compile('oddrow|evenrow')})

        records = []
        fmt = 0
        for tr in trs:
            tds = tr.findAll('td')
            if len(tds) == 16:
                # stats format 1
                fmt = 1
                records.append( [ x.text for x in tds ] )
            elif len(tds) == 20:
                # stats format 2
                fmt = 2
                season = tds[0].text.replace("'",'')
                season = u'%s赛季' % season
                team_fullname = tds[1].a.get('href').rsplit('/',1)[-1]
                team_name = team_fullname.rsplit('-',1)[0]
                team_name = u' '.join( [ x.capitalize() for x in team_name.split('-') ] )
                team_name = u'%s' % translate.translate(team_name)
                if '\n' in team_name:
                    team_name = u'雷霆'
                if 'Trail' in team_name:
                    team_name = u'开拓者'
                linkinfo = team_fullname.rsplit('-')[-1]
                team_name = u'%s(%s)' % (team_name,linkinfo)
                record = [ season, team_name ]
                record.append( [ x.text for x in tds[2:] ] )
                records.append(record)
            else:
                self.logger.error('Can not parse tr, %s' % tr)

        if fmt == 1:
            records[0][0] = records[0][0].replace(u' Regular Season',u'赛季')
            records[1][0] = u'职业生涯'
            records = [ comment1 ] + records
        elif fmt == 2:
            records = [ comment2 ] + records

        for i in range(len(records)):
            records[i] = u','.join(records[i])
            self.logger.debug( records[i].encode('utf-8') )

        return records

    def parse_gamelog(self, trs, hoop_id, month_title = True):
        comment = u'#日期,对手,比分,上场时间,投篮,命中率,三分,三分命中率,罚球,罚球命中率,篮板,助攻,盖帽,抢断,犯规,失误,得分'
        chinese_name, english_name, espn_id = translate.playerid_name[str(hoop_id)]
        season = utils.get_current_season()

        records = []
        for tr in trs:
            if tr.get('class') == 'total':
                month = translate.translate(tr.td.text)
                # we add comment, then month
                records.append( comment )
                if month_title:
                    records.append( u'#%s%s-%sNBA%s月数据' % (chinese_name,season,season+1,month) )
                records.append( u'#-----' )
            elif 'team' in tr.get('class'):
                # we add rows(seperated by comma)
                tds = tr.findAll('td')
                date = u'%s月%s日' % tuple(tds[0].text.split(' ')[-1].split('/'))
                team_fullname = tds[1].a.get('href').rsplit('/',1)[-1]
                team_name = team_fullname.rsplit('-',1)[0]
                team_name = u' '.join( [ x.capitalize() for x in team_name.split('-') ] )
                team_name = u'%s' % translate.translate(team_name)
                if '\n' in team_name:
                    team_name = u'雷霆'
                if 'Trail' in team_name:
                    team_name = u'开拓者'
                linkinfo = team_fullname.rsplit('-')[-1]
                team_name = u'%s(%s)' % (team_name,linkinfo)
                score = tds[2].text
                result = u'胜' if score[0] == 'W' else u'负'
                score = score[1:]
                try:
                    match_espn_id = tds[2].a.get('href').rsplit('=',1)[-1]
                    match_hoop_id = database.MatchesDB().get_hoop_id(match_espn_id)
                except:
                    match_hoop_id = 0
                score = u'%s(%s)(%s)' % (score,result,match_hoop_id)
                stats = [ tds[i].text for i in [3,4,5,6,7,8,9,10,11,12,13,14,15,16] ]
                records.append( u'%s,%s,%s,%s' % (date,team_name,score,','.join(stats)) )
            else:
                pass
            
            if records:
                self.logger.debug(records[-1] )

        records = reversed(records)
        return records

    def parse_stats(self, s, hoop_id):
        comment = u"#赛季,球队,场次,首发,时间,投篮,%,三分,%,罚球,%,进攻板,防守板,篮板,助攻,盖帽,抢断,犯规,失误,得分"
        chinese_name, english_name, espn_id = translate.playerid_name[str(hoop_id)]
        season = utils.get_current_season()

        records = []
        trs = s.find('div',{'class':re.compile('mod-container mod-table mod-player-stats')}).findAll('tr')
        thedict = {} # save GP,GS,and M
        for tr in trs:
            if tr.get('class') == 'stathead':
                if tr.text =='Regular Season Averages':
                    records.append('#-----')
                    records.append(u'#%s常规赛平均数据' % chinese_name)
                    records.append(comment)
                elif tr.text == 'Regular Season Totals':
                    records.append('#-----')
                    records.append(u'#%s常规赛总数据' % chinese_name)
                    records.append(comment)
                elif tr.text == 'Postseason Averages':
                    records.append('#-----')
                    records.append(u'#%s季后赛平均数据' % chinese_name)
                    records.append(comment)
                elif tr.text == 'Postseason Totals':
                    records.append('#-----')
                    records.append(u'#%s季后赛总数据' % chinese_name)
                    records.append(comment)
                elif tr.text in [ u'Regular Season Misc Totals',u'Postseason Misc Totals' ]:
                    break
            elif tr.get('class') in ['oddrow','evenrow']:
                tds = tr.findAll('td')
                if len(tds) == 1:
                    # no stats found
                    records = []
                    break
                season = tds[0].text.replace("'",'')
                season = '19'+season if season.startswith('9') else '20'+season
                try:
                    team_fullname = tds[1].a.get('href').rsplit('/',1)[-1]
                    team_name = team_fullname.rsplit('-',1)[0]
                    team_name = u' '.join( [ x.capitalize() for x in team_name.split('-') ] )
                    team_name = u'%s' % translate.translate(team_name)
                    if '\n' in team_name:
                        team_name = u'雷霆'
                    if 'Trail' in team_name:
                        team_name = u'开拓者'
                except:
                    team_name = '未知'
                    team_fullname = '-unknown'
                linkinfo = team_fullname.rsplit('-')[-1]
                team_name =  u'%s(%s)' % (team_name,linkinfo)
                if len(tds) == 20: #average
                    cols = [5,6,7,8,9,10,11,12,13,14,15,16,17,18,19]
                    gp,gs,m = [ tds[i].text for i in [2,3,4] ]
                    thedict[season] = (gp,gs,m)
                else:
                    cols = [2,3,4,5,6,7,8,9,10,11,12,13,14,15,16]
                    gp,gs,m = thedict.get(season)
                stats = [ tds[i].text for i in cols ]
                records.append( u'%s,%s,%s,%s,%s,%s' % (season,team_name,gp,gs,m,','.join(stats)) )
            else:
                pass

            if records:
                self.logger.debug(records[-1] )

        return records

    def update_last5(self, hoop_id):
        s = self.fetch_gamelog(hoop_id)
        if not s:
            return

        trs = s.find('div',{'class':re.compile('mod-container mod-table mod-player-stats')}).findAll('tr')

        records = self.parse_gamelog(trs, hoop_id, month_title=False)
        value = u'\n'.join(list(records)[:7])
        
        self.statsdb.update( 'playerlast5_'+str(hoop_id), value )
        
    def update_gamelog(self, hoop_id):
        s = self.fetch_gamelog(hoop_id)
        if not s:
            return

        try:
            # we want to ensure that the gamelog belongs to current season
            season = utils.get_current_season()
            assert(s.find('select',{'class':'tablesm'}).findAll('option')[1].text.split('-')[0] == str(season))
            trs = s.find('div',{'class':re.compile('mod-container mod-table mod-player-stats')}).findAll('tr')
        except Exception,e:
            self.logger.error(repr(e))
            trs = []

        records = self.parse_gamelog(trs, hoop_id)
        value = u'\n'.join(records)
        
        self.statsdb.update( 'playergamelog_'+str(hoop_id), value )

    def update_career(self, hoop_id):
        url1 = 'http://espn.go.com/nba/player/stats/_/id/%s' 
        url2 = 'http://espn.go.com/nba/player/stats/_/id/%s/seasontype/3'
        s1 = self.fetch_stats(hoop_id, url1)
        s2 = self.fetch_stats(hoop_id, url2)
        if not( s1 or s2 ):
            return
        
        records = []

        records.extend( self.parse_stats(s1, hoop_id) )
        records.extend( self.parse_stats(s2, hoop_id) )
        value = u'\n'.join(records)
    
        self.statsdb.update( 'playercareer_'+str(hoop_id), value )
    
    def update_profile(self, hoop_id):
        s = self.fetch_profile(hoop_id)
        if not s:
            return

        records = self.parse_profile(s)
        value = u'\n'.join(records)

        self.statsdb.update( 'playerstats_'+str(hoop_id), value )
                
if __name__ == '__main__':
    #PlayerStatsUpdater().update_career(1037)
    #PlayerStatsUpdater().update_last5(1037)
    #PlayerStatsUpdater().update_gamelog(1037)
    #PlayerStatsUpdater().update_profile(1037)
    PlayerStatsUpdater().update_all(concurrent=1)

