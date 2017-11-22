#encoding=utf-8
import logging
import random
import time
import time,math,os,re,urllib,urllib2,cookielib
from BeautifulSoup import BeautifulSoup
import time
import socket
import os
from translate import translate
from translate import playername_id
from string import join
import database
logger = logging.getLogger(__name__)

##################################
# author:ty
# email:tianyu@hoopchina.com
# date:2012-02-22
##################################

class BrowserBase(object):
    ERROR = {
        '0':'Can not open the url,checck you net',
        '1':'Creat download dir error',
        '2':'The image links is empty',
        '3':'Download faild',
        '4':'Build soup error,the html is empty',
        '5':'Can not save the image to your disk',
    }
    image_links = []
    image_count = 0

    def __init__(self):
        socket.setdefaulttimeout(20)

    def speak(self,content):
        info =  "[browser]%s" %(content)
        logger.info(info)

    def openurl(self,url):
        """
        打开网页
        """
        cookie_support= urllib2.HTTPCookieProcessor(cookielib.CookieJar())
        self.opener = urllib2.build_opener(cookie_support,urllib2.HTTPHandler)
        urllib2.install_opener(self.opener)
        user_agents = [
                    'Mozilla/5.0 (Windows; U; Windows NT 5.1; it; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11',
                    'Opera/9.25 (Windows NT 5.1; U; en)',
                    'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
                    'Mozilla/5.0 (compatible; Konqueror/3.5; Linux) KHTML/3.5.5 (like Gecko) (Kubuntu)',
                    'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.12) Gecko/20070731 Ubuntu/dapper-security Firefox/1.5.0.12',
                    'Lynx/2.8.5rel.1 libwww-FM/2.14 SSL-MM/1.4.1 GNUTLS/1.2.9',
                    "Mozilla/5.0 (X11; Linux i686) AppleWebKit/535.7 (KHTML, like Gecko) Ubuntu/11.04 Chromium/16.0.912.77 Chrome/16.0.912.77 Safari/535.7",
                    "Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:10.0) Gecko/20100101 Firefox/10.0 ",

                    ]

        # 随机选取一个agent
        agent = random.choice(user_agents)
        self.opener.addheaders = [("User-agent",agent),("Accept","*/*"),('Referer','http://www.google.com')]
        try:
            res = self.opener.open(url)
        except Exception,e:
            self.speak(str(e)+url)
            raise Exception
        else:
            return res

class Spider(BrowserBase):
    name = 'Spider'

    def __init__(self):
        BrowserBase.__init__(self)

    def get_team_list(self):
        """
        得到所有球队的链接
        """
        url = "http://espn.go.com/nba/teams"
        html = self.openurl(url).read()
        soup = BeautifulSoup(html)
        content_soup = soup.findAll('div',id='content')[0]
        link_soup = content_soup.findAll('a')

        result = set()
        for link in link_soup:
            if str(link.string) == 'Stats':
                url = 'http://espn.go.com' + link['href']
                result.add(url)
        result = list(result)
        return result

    def get_team_info(self,url):
        """
        找到球队的比分信息
        """
        try:
            html = self.openurl(url)
        except Exception,e:
            log.error(str(e))
            return False
        soup = BeautifulSoup(html)
        tables = soup.findAll('table')
        result = {}

        # 得到城市名称
        team_name =  str(soup.h1.string).split('Stats')[0]
        city_name = team_name.split(' ')[:-2]
        city = join(city_name,' ').replace('Trail','')

        for tr in tables[0].findAll('tr'):
            if 'colhead' in str(tr) or 'stathead' in str(tr):
                continue

            try:
                player = str(tr.a.string)
            except AttributeError:
                # 跳过无用的行
                continue
            player_link = str(tr.a['href'])

            try:
                result[player]
            except KeyError:
                result[player] = {}

            result[player]['link'] = player_link
            result[player]['team'] = team_name
            result[player]['city'] = city

            tds = tr.findAll('td')
            result[player]['GP'] = tds[1].string
            result[player]['GS'] = tds[2].string
            result[player]['MIN'] = tds[3].string
            result[player]['PPG'] = tds[4].string
            result[player]['OFFR'] = tds[5].string
            result[player]['DEFR'] = tds[6].string
            result[player]['RPG'] = tds[7].string
            result[player]['APG'] = tds[8].string
            result[player]['SPG'] = tds[9].string
            result[player]['BPG'] = tds[10].string
            result[player]['TPG'] = tds[11].string
            result[player]['FPG'] = tds[12].string
            result[player]['ATO'] = tds[13].string
            result[player]['PER'] = tds[14].string

        for tr in tables[1].findAll('tr'):
            if 'colhead' in str(tr) or 'stathead' in str(tr):
                continue

            try:
                player = str(tr.a.string)
            except AttributeError:
                # 跳过无用的行
                continue


            tds = tr.findAll('td')
            result[player]['FGM'] = tds[1].string
            #result[player]['FGA'] = tds[2].string
            #result[player]['FG'] = tds[3].string
            result[player]['3PM'] = tds[4].string
            #result[player]['3PA'] = tds[5].string
            #result[player]['3P'] = tds[6].string
            result[player]['FTM'] = tds[7].string
            #result[player]['FTA'] = tds[8].string
            #result[player]['FT'] = tds[9].string
            #result[player]['2PM'] = tds[10].string
            #result[player]['2PA'] = tds[11].string
            #result[player]['2P'] = tds[12].string
            #result[player]['PPS'] = tds[13].string
            #result[player]['AFG'] = tds[14].string


        return result

def update_all():
    s = Spider()

    db = database.StatsDB()

    teams = [u'http://espn.go.com/nba/teams/stats?team=sas', u'http://espn.go.com/nba/teams/stats?team=por', u'http://espn.go.com/nba/teams/stats?team=orl', u'http://espn.go.com/nba/teams/stats?team=cle', u'http://espn.go.com/nba/teams/stats?team=sac', u'http://espn.go.com/nba/teams/stats?team=mem', u'http://espn.go.com/nba/teams/stats?team=phi', u'http://espn.go.com/nba/teams/stats?team=tor', u'http://espn.go.com/nba/teams/stats?team=chi', u'http://espn.go.com/nba/teams/stats?team=mia', u'http://espn.go.com/nba/teams/stats?team=cha', u'http://espn.go.com/nba/teams/stats?team=atl', u'http://espn.go.com/nba/teams/stats?team=lac', u'http://espn.go.com/nba/teams/stats?team=gsw', u'http://espn.go.com/nba/teams/stats?team=okc', u'http://espn.go.com/nba/teams/stats?team=ind', u'http://espn.go.com/nba/teams/stats?team=lal', u'http://espn.go.com/nba/teams/stats?team=bos', u'http://espn.go.com/nba/teams/stats?team=dal', u'http://espn.go.com/nba/teams/stats?team=min', u'http://espn.go.com/nba/teams/stats?team=den', u'http://espn.go.com/nba/teams/stats?team=mil', u'http://espn.go.com/nba/teams/stats?team=det', u'http://espn.go.com/nba/teams/stats?team=pho', u'http://espn.go.com/nba/teams/stats?team=nor', u'http://espn.go.com/nba/teams/stats?team=was', u'http://espn.go.com/nba/teams/stats?team=uth', u'http://espn.go.com/nba/teams/stats?team=nyk', u'http://espn.go.com/nba/teams/stats?team=hou', u'http://espn.go.com/nba/teams/stats?team=njn']


    team_list = []
    for i in range(len(teams)):
        li = (i+1,teams[i])
        team_list.append(li)


    # 直到处理完所有的team
    while len(team_list):
        team = team_list.pop(0)

        team_id = team[0]
        url = team[1]

        #players = s.get_team_info('http://espn.go.com/nba/team/stats/_/name/nj/new-jersey-nets')
        players = s.get_team_info(url)
        if not players:
            info = '获取球队信息失败 at %s' %url
            logger.info(info)
            team_list.append(team)
            continue

        com = "#球员名,出场,首发,上场时间,得分,进攻板,防守板,篮板,助攻,抢断,盖帽,失误,犯规,助攻失误比,效率值,链接信息\n"
        value = com

        li = {'key':None,'value':None}
        for name in players:
            data = players[name]
            city = data['city']

            li['key'] = "teamstat_%d" %team_id

            #cn_city = translate(city)
            cn_player = translate(name)
            en_player = name.replace("'",'').replace(' ','').lower()
            try:
                player_id = playername_id[cn_player]
            except KeyError:
                player_id = 0

            #姚明(3214)
            link_info = "%s(%s)"  %(en_player,player_id)
            #print players[name]['team']'',team
            #print cn_name
            #PLAYER GP GS MIN PPG OFFR DEFR RPG APG SPG BPG TPG FPG A/TO PER
            value += "%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s\n" %(cn_player,
                    data['GP'],data['GS'],data['MIN'],data['PPG'],data['OFFR'],
                    data['DEFR'],data['RPG'],data['APG'],data['SPG'],data['BPG'],
                    data['TPG'],data['FPG'],data['ATO'],data['PER'],
                    link_info.replace(' ','')
                    )
            li['value'] = value

        logger.debug(li)

        db.update(li['key'],li['value'])


if __name__ == '__main__':
    update_all()

