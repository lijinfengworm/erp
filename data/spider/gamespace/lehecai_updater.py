#coding:utf-8
'''client library for lehecai.com
'''
import utils
import urllib
import json
import database

def namemap(name):
    return {u"克里夫兰骑士":u"克利夫兰骑士"}.get(name,name)

class LehecaiUpdater:
    def __init__(self):
        self.nba_root = 'http://data.lehecai.com'
        self.nba_schedule_api = '/api/basketball/schedule.do?json='
        self.nba_live_api = '/api/basketball/live.do?json='
        self.matchesdb = database.MatchesDB()

    def sync_schedule(self,day_deltas=range(0,14)):
        for day_delta in day_deltas:
            datestr = utils.EastTime().now(day_delta,fmt="%Y-%m-%d")
            j = {'where':[{'key':'time','op':'=','val':datestr}]}
            arg = urllib.quote(json.dumps(j))
            ret = urllib.urlopen(self.nba_root+self.nba_schedule_api+arg).read()
            j = json.loads(ret)
            for d in j.get('data',[]):
                match = d.values()[0]
                if match['sclassNameEn'] == 'NBA':
                    away_team_full_name,home_team_full_name = namemap(match['awayTeam']),namemap(match['homeTeam'])
                    #print("Lehecai:%s,%s,%s,%s"%(datestr,away_team_full_name,home_team_full_name,match.get('id',0)))
                    self.matchesdb.update_lehecai_info(datestr,away_team_full_name,home_team_full_name,match.get('id',0))

    def update_match_info(self,lehecai_ids=[]):
        j = {'where':[{'key':'matchId','op':'=','val':lehecai_ids}]}
        arg = urllib.quote(json.dumps(j))
        ret = urllib.urlopen(self.nba_root+self.nba_live_api+arg).read()
        j = json.loads(ret)
        scores = []
        for d in j.get('data',[]):
            for v in d.values():
                lehecai_id = v.get('id')
                home_score = v.get('homeScore',0)
                away_score = v.get('awayScore',0)
                scores.append( [away_score,home_score] )
        return scores

if __name__ == '__main__':
    LehecaiUpdater().sync_schedule(range(-1,1))
    #LehecaiUpdater().update_match_info([114763,114764,116945])

