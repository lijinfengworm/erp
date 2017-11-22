# -*- encoding: UTF-8 -*-
import urllib,urllib2
import sys,os
import time
import json
import config
import twitter
import mechanize
from BeautifulSoup import BeautifulSoup
import re
import math
import threading
import base64
import hmac
import hashlib
import random
import socket

class request(object):

    def __init__(self):
        pass       

    def getRequest(self, url, params={}, timeout=3):
        '''
        get方式
        '''

        url = self.getUrl(url, params) 
        #print url 
        try:
            response = urllib2.urlopen(url, timeout=timeout)
        except Exception,e:
            msg = 'GET Url %s Error!' % url
            myexcept.log(e, msg)
            return False
        return response.read()

    def urlencode(self, string):
        string = str(string)
        string = string.replace('&', '%26')
        string = string.replace('=', '%3D')
        string = string.replace(':', '%3A')
        string = string.replace('/', '%2F')
        string = string.replace('+', '%2B')
        string = string.replace(' ', '+')
        string = string.replace('#', '%23')
        return string

    def getUrl(self, url, params):        
        parameter = self.formateGetParameter(params)
        if not parameter:
            return url      
        if url.find('?')==-1:
            return url + '?' + parameter
        else:
            return url + '&' + parameter 

    def formateGetParameter(self, params):
        parameter = []
        for k in params:
            if isinstance(params[k], unicode):
                params[k] = params[k].encode('utf-8')
            parameter.append('%s=%s' % (k, self.urlencode((params[k]))))
        return '&'.join(parameter)

    def postRequest(self, url, postdata, timeout=3):
        '''
        post方式
        '''
        for k in postdata:
            if isinstance(postdata[k], unicode):
                postdata[k] = postdata[k].encode('utf-8')
        try:
            postdata = urllib.urlencode(postdata)
            req = urllib2.Request(url, postdata)
            response = urllib2.urlopen(req, timeout = timeout)
        except Exception,e:
            msg = 'POST Date Error!'
            myexcept(e, msg)
            return False
        return response.read()

    def doRequest(self, url, method, params, timeout):
        if self.method == 'POST':
            return self.postRequest(url, postdata=params, timeout=timeout)
        else:
            return self.getRequest(url, params=params, timeout=timeout)

    def jsonDecode(self, data, default=False):
        try:
            return json.loads(data)
        except Exception,e:
            msg = 'Json Decode Error!'
            myexcept.log(e, msg)
            return default



class hcAPI(request):

    dev_api = {
        'save' : 'http://mt.hoopchina.com/api_dev.php/weibo/save',
        'getrss' : 'http://mt.hoopchina.com/api_dev.php/weibo/get_urls',    
        'update_last_update' : 'http://mt.hoopchina.com/api_dev.php/weibo/last_update',
        'accounts_by_type' : 'http://mt.hoopchina.com/api_dev.php/weibo/get_accounts_by_type',
        'accounts_update_last_update' : 'http://mt.hoopchina.com/api_dev.php/weibo/accounts_update_last_update',
        }
    prod_api = {
        'save' : 'http://api.hupu.com/weibo/save',
        'getrss' : 'http://api.hupu.com/weibo/get_urls',    
        'update_last_update' : 'http://api.hupu.com/weibo/last_update',
        'accounts_by_type' : 'http://api.hupu.com/weibo/get_accounts_by_type',
        'accounts_update_last_update' : 'http://api.hupu.com/weibo/accounts_update_last_update',
        }
    def __init__(self):
        if len(sys.argv) >= 3 and sys.argv[1] == '-env' and sys.argv[2] == 'prod':
            self.env = 'prod'
        else:
            self.env = 'dev'


    def getConfig(self):
        if self.env == 'prod':
            return self.prod_api
        else:
            return self.dev_api

    def getSaveMsgAPI(self):
        config = self.getConfig()
        return config['save']
    
    def getRssAPI(self):
        config = self.getConfig()
        return config['getrss']
    
    def getUpdateLast_updateAPI(self):
        config = self.getConfig()
        return config['update_last_update']

    def getAccountsLast_updateAPI(self):
        config = self.getConfig()
        return config['accounts_update_last_update']

    def getAccountsByTypeAPI(self):
        config = self.getConfig()
        return config['accounts_by_type']

    def getAccountsByType(self, type):
        '''
        更具类型返回账号信息
        '''

        response = self.getRequest(url=self.getAccountsByTypeAPI(), params={'type' : type}, timeout=10)
        return self.jsonDecode(response, default={})

    def saveNewMessage(self, data):
        '''
        保存数据
        '''
        return self.postRequest(url=self.getSaveMsgAPI(), postdata=data)

    def updateAccountLastUpdatetime(self, account_id, last_time):
        '''
        更新最后更新时间
        '''
        return self.getRequest(url=self.getUpdateLast_updateAPI(), params={'account_id':account_id, 'last_update':last_time})

    def updateAccountsLastUpdatetime(self, accounts):
        '''
        更新一组用户的最后更新时间
        '''
        return self.postRequest(url=self.getAccountsLast_updateAPI(), postdata=accounts)

class translate(request):
    prod_youdao = {
        'key' : '592900332',
        'keyfrom' : 'hoopchina-voice',
        'request_url' : 'http://fanyi.youdao.com/openapi.do?type=data&version=1.1'
        }
    dev_youdao = {
        'key' : '592900332',
        'keyfrom' : 'hoopchina-voice',
        'request_url' : 'http://fanyi.youdao.com/openapi.do?type=data&version=1.1'
        } 
    error_youdao = {
            '20' : '要翻译的文本过长',
            '30' : '无法进行有效的翻译',
            '40' : '不支持的语言类型',
            '50' : '无效的key'
        }  
    @staticmethod     
    def doTranslate(content):
        if len(sys.argv) >= 3 and sys.argv[1] == '-env' and sys.argv[2] == 'prod':
            config = translate.prod_youdao
        else:
            config = translate.dev_youdao
        if isinstance(content, unicode):
            content = content.encode('utf8')
        params = {}
        params['key'] = config['key']
        params['keyfrom'] = config['keyfrom']        
        params['doctype'] = 'json'
        params['q'] = content
        r = request()
        res = r.getRequest(url=config['request_url'], params=params)   
        if res:
            return translate.getTranslateText(r.jsonDecode(res, default={}), content)
        return ''

    @staticmethod
    def getTranslateText(data, text):    
        if type(data) is dict and 'errorCode' in data:
            if data['errorCode'] == 0:
                if 'translation' in data:
                    return data['translation'][0]
            else:           
                log_dir = getLogDir()
                file = '%stranslate.log' % log_dir
                f =open(file, 'a')
                print >> f, '[%s]: Translate Error' % time.strftime('%Y-%m-%d %H:%M:%S')
                print >> f, 'Translate Text: %s' % text
                print >> f, 'Error Code: %d Info: %s' % (data['errorCode'], translate.error_youdao[str(data['errorCode'])])
                f.close()
        return ''





class myexcept(Exception):
    
    @staticmethod
    def log(e, msg = ''):
        log_path = getLogDir()
        log_path = '%svoice_error.log' % log_path
        f = open(log_path, 'a')
        print >> f, '[%s]: %s' % (time.strftime('%Y-%m-%d %H:%M:%S'), msg)
        print >> f, 'Error Info: %s' % repr(e)
        f.close()


class spider(object):
    
    timeout = 3
    def __init__(self, type):
        self.type = type.upper()
        self.hcAPI = hcAPI()

    def notice(self, msg, error=None):
        name = self.type.lower()
        log_dir = getLogDir()
        file = '%s%s.log' % (log_dir, name)
        f = open(file, "a")    
        print >> f, 'Notice: [%s]' % time.strftime('%Y-%m-%d %H:%M:%S')
        print >>f, msg
        print >>f, 'Info: %s' %repr(error)
        f.close()
    
    def getHcAPI(self):    
        return self.hcAPI

    def getNewMsgs(self):
        accounts = self.getAccountsByType(self.type)
        return self.getAccountsMsgs(accounts)

    def getAccountsByType(self, type):
        return self.getHcAPI().getAccountsByType(type)

    def getAccountsMsgs(self, accounts):
        br = mechanize.Browser()
        br.addheaders = [('User-agent', 'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.1) Gecko/2008071615 Fedora/3.0.1-1.fc9 Firefox/3.0.1')]
        br.set_handle_robots(False)
        self.notice('begin foreach accounts')
        for account in accounts:
            #if中内容为处理sina，不走api,通过第三方接口获取数据
            if account['type'] == 'SINA':
                if not account['url']:
                    if account['orginal_user_id']:
                        account['url'] = 'http://rssing.sinaapp.com/?uname=%d&uid=&encode=&originalimage=true#ation=&titlelimit=26&fulltitle=&ver=2' % int(account['orginal_user_id'])
                    else:
                        account['url'] = 'http://rssing.sinaapp.com/?uname=%s&uid=&encode=&originalimage=true#ation=&titlelimit=26&fulltitle=&ver=2' % account['orginal_name']

            try:
                self.notice('get account rss data, url: as follow', account['url'])
                br.open(account['url'], timeout=self.timeout)
            except urllib2.HTTPError,e:
                msg = 'http: %d . Open Url: %s Error!' % (e.code, account['url'])
                myexcept.log(e, msg)
                continue
            except Exception,e:
                msg = 'Open Url: %s Error!' % account['url']
                myexcept.log(e, msg)
                continue
            self.notice('get response')
            xml = br.response().read()
            self.notice('begin to parse response')
            try:                
                msgs = BeautifulSoup(xml, fromEncoding='utf-8')           
            except Exception, e:
                msg = 'BeautifulSoup Parse Data: %s Error! ' % repr(xml)
                myexcept.log(e,msg)
                continue
            #check msg            
            if self.checkMsg(msgs):
                self.saveMsgs(msgs, account)
            else:
                self.notice('msgs check error')
    def checkMsg(self,msgs):
        return True
    def saveMsgs(self, msgs, account):
        '''
        适用于一次只能抓取一个用户的微博信息
        '''
        
        self.notice('begin to save messages')
        items = msgs.findAll('item')
        if len(items) > 0:
            last_update = account['last_update']
            max_publish_date = 0
            for item in items:
                publish_date = self.getCreatedDate(item)
                if publish_date > last_update:                    
                    data = self.getData(item, account) 
                    if self.saveMsg(data):
                        if publish_date > max_publish_date:
                            max_publish_date = publish_date               
            if max_publish_date > last_update: 
                self.notice('update account last_update_time', account)               
                self.updateLastUpdatetime(account, max_publish_date)
                        

    def saveMsgs2(self, msgs, accounts):
        '''
        适用于一次能抓取所有用户的微博信息
        '''
        #print accounts
        self.notice('begin to save messages')
        if msgs and accounts:            
            max_publish_date = {}
            for msg in msgs:          
                account_info = self.getUserInfo(msg)                
                account = self.isWantedUser(accounts, account_info)
                if account:
                    id = account['id']
                    last_time = account['last_update']                    
                    publish_date = self.getCreatedDate(msg)
                    if publish_date > last_time:                 
                        data = self.getData(msg, account)
                        if self.saveMsg(data):
                            id = int(id)
                            if id not in max_publish_date:
                                max_publish_date[id] = publish_date
                            else:
                                if publish_date > max_publish_date[id]:
                                    max_publish_date[id] = publish_date
            if len(max_publish_date):
                self.notice('update accounts last_update_time', max_publish_date)                
                self.updateAccountsLastUpdatetime(max_publish_date)     
                

                


    def saveMsg(self, data):
        return self.getHcAPI().saveNewMessage(data)

    def getData(self, item, account):
        data = self.getAccountData(account)
        data['publish_date'] = self.getCreatedDate(item)
        data['orginal_url'] = self.getLinkData(item)
        data['orginal_url'] = data['orginal_url'].replace('&amp;', '&')
        data['text'] = self.getContentData(item)
        data['translate_text'] = self.getTranslateData(item, account)      
        return data

    def getAccountData(self, account):
        data = {}
        data['twitter_user_id'] = int(account['twitter_user_id'])
        data['twitter_account_id'] = int(account['id'])
        return data

    def getTranslateData(self, item, account):
        if account['need_translate']:
            return translate.doTranslate(self.getTranslateContent(item))
        return ''

    def stripHTML(self, content):
        '''
        去掉html标签
        '''
        a = re.sub('<[^<]+?>.*?<\/[^<]>', '', content)
        a = re.sub('<[^<]+?>', '', a)
        return a

    def updateLastUpdatetime(self, account, last_time):
        '''
        更新最后更新时间
        '''
        return self.getHcAPI().updateAccountLastUpdatetime(account['id'], last_time)

    def updateAccountsLastUpdatetime(self, accounts):
        '''
        更新一组用户的最后更新数据时间
        '''
        return self.getHcAPI().updateAccountsLastUpdatetime({'accounts':accounts})

    def updateDictAccounts(self, accounts, account_id, publish_date):
        for account in accounts:
            if account['id'] == account_id:
                account['last_update'] = publish_date
                return accounts
        return accounts

class oauth(object):
    timeouot = 3
    def __init__(self, type, oauth_params):
        self.type = type.upper()
        self.consumer_key = oauth_params['consumer_key']
        self.consumer_secret = oauth_params['consumer_secret']
        self.access_token_key = oauth_params['access_token_key']
        self.access_token_secret = oauth_params['access_token_secret']
        self.home_timeline_url = oauth_params['home_timeline_url']
        self.oauth_signature_method = oauth_params['oauth_signature_method']
        self.oauth_version = oauth_params['oauth_version']
        self.request = request()

    def getConsumerKey(self):
        return self.consumer_key

    def getConsumerSecret(self):
        return self.consumer_secret

    def getAccessTokenKey(self):
        return self.access_token_key

    def getAccessTokenSecret(self):
        return self.access_token_secret

    def getHomeTimelineUrl(self):
        return self.home_timeline_url

    def getOauthSignatureMethod(self):
        return self.oauth_signature_method

    def getRequestObject(self):
        return self.request

    def getConsumerSecret(self):
        return self.consumer_secret

    def getAccessTokenKey(self):
        return self.access_token_key

    def getOauthVersion(self):
        return self.oauth_version
        
    def getTimestamp(self):
        return int(time.time())

    def getOauthNonth(self):
        return '%d%d' % (self.getTimestamp(), random.randint(1, 9999))

    def getHomeTimeline(self): 
        self.notice('begin to get friends timeline')
        param = {}
        param['oauth_consumer_key'] = self.getConsumerKey()
        param['oauth_nonce'] = self.getOauthNonth()
        param['oauth_signature_method'] = self.getOauthSignatureMethod()
        param['oauth_timestamp'] = self.getTimestamp()
        param['oauth_token'] = self.getAccessTokenKey()
        param['oauth_version'] = self.getOauthVersion()
        url = self.getHomeTimelineUrl()
        return self.getOauthRequest(url=url, param=param, method='GET', timeout=self.timeout)
        

    def getOauthRequest(self, url, param, method, timeout):
        oauth_signature = self.getOauthSignature(url, param, method)
        url = self.getHomeTimelineUrl() + '?' + self.getSortedQueryParams(param) + '&oauth_signature=' + oauth_signature        
        response = self.getRequestObject().getRequest(url, params={}, timeout = timeout)
        if response:
            return self.getRequestObject().jsonDecode(response)
        return False

    def getOauthSignature(self, url, param, method):
        signature_key = self.getSignatureKey()
        base = self.getSortedQueryParams(param)
        base_string = method.upper() + '&' + self.getRequestObject().urlencode(url) + '&' + self.getRequestObject().urlencode(base)
        return self.getRequestObject().urlencode(base64.encodestring(hmac.new(signature_key, base_string, hashlib.sha1).digest()))

    def getSortedQueryParams(self, param):
        if 'oauth_signature' in param:
            del param['oauth_signature']
        keys = param.keys()
        keys.sort()
        argv = []        
        for k in keys:
            argv.append('%s=%s' % (k, self.getRequestObject().urlencode(str(param[k]))))
        return '&'.join(argv)

    def getSignatureKey(self):
        '''
        获取签名key
        '''
        return self.getConsumerSecret() + '&' + self.getAccessTokenSecret()

class qqOauth(oauth):
    def __init__(self, oauth_params):
        oauth.__init__(self, 'qq', oauth_params)

class qqSpider(spider, qqOauth):
    def __init__(self, oauth_params):
        spider.__init__(self, 'qq')
        qqOauth.__init__(self, oauth_params)

    def getNewMsgs(self):   
        accounts = self.getAccountsByType(self.type)
        msgs = self.getNewMessages()
        self.saveMsgs2(msgs, accounts)

    def getNewMessages(self):
        timeline = self.getHomeTimeline()
        if timeline:
            if 'data' in timeline and timeline['ret'] == 0 and 'info' in timeline['data'] :
                return timeline['data']['info']
            else:
                self.notice(msg='QQ Get New Msgs Fail!', error=timeline)
                return []
        return []
    
    def getUserInfo(self, msg):
        return msg['name']

    def isWantedUser(self, accounts, name):
        if type(accounts) is not list:
            return False
        url = u'http://t.qq.com/' + name.lower()
        for account in accounts: 
            if (account['orginal_name'] !=None and account['orginal_name'].lower() == name) \
            or (account['url'] != None and account['url'].lower() == url):
                return account
        return False

    def getCreatedDate(self, msg):
        return msg['timestamp']

    def getLinkData(self, msg):        
        return 'http://t.qq.com/p/t/' + msg['id']

    def getContentData(self, msg):
        content = msg['text']
        if msg['source']:                       
            content = content + u' //@' + msg['source']['name'] + u' ' + msg['source']['text']
            if 'image' in msg['source'] and type(msg['source']['image']) is list and len(msg['source']['image']) > 0:
                image = msg['source']['image'][0].replace('app.qpic.cn', 't1.qpic.cn')  #修改api返回图片地址错误的问题
                content = content + u'<img src="'+ image +'/2000" />'  #图片添加到内容中
        if msg['image']:
            image = msg['image'][0].replace('app.qpic.cn', 't1.qpic.cn')  #修改api返回图片地址错误的问题
            content = content + u'<img src="'+ image +'/2000" />'  #图片添加到内容中
        return content

    def getTranslateContent(self, msg):
        return msg['text']

    def updateNickname(self):
        print 222

class sinaOauth(oauth):
    def __init__(self, oauth_params):
        #oauth.__init__(self, 'sina', oauth_params)  
        self.access_token_key_url = oauth_params['access_token_key_url']  
        self.home_timeline_url = oauth_params['home_timeline_url']  
        self.request = request()

    def getAccessTokenKeyUrl(self):
        return self.access_token_key_url

    def getAccessTokenKey(self):
        url = self.getAccessTokenKeyUrl()
        return self.getRequestObject().getRequest(url=url, params={}, timeout=self.timeout)

    def getHomeTimelineUrl(self):
        return self.home_timeline_url

class sinaSpider(spider, sinaOauth):
    def __init__(self, oauth_params):
        spider.__init__(self, 'sina')
        sinaOauth.__init__(self, oauth_params)
    
    

    def getHomeTimeline(self):
        url = '%s?access_token=%s&count=200' % (self.getHomeTimelineUrl(), self.getAccessTokenKey())
        response = self.getRequestObject().getRequest(url=url, params={}, timeout=self.timeout)
        if response:
            return self.getRequestObject().jsonDecode(response)
        return False
        
    def getNewMsgs(self):
        accounts = self.getAccountsByType(self.type)
        msgs = self.getHomeTimeline() 
        if msgs:
            if 'statuses' in msgs:   
                self.saveMsgs2(msgs['statuses'], accounts)

    def getCreatedDate(self, msg):
        d = time.strptime(msg['created_at'], '%a %b %d %H:%M:%S +0800 %Y')
        return int(time.mktime(d))

    def getLinkData(self, msg):
        mid = self.getMid(msg['mid'])
        return 'http://weibo.com/%d/%s' % (msg['user']['id'], mid)

    def getContentData(self, msg):
        content = msg['text']
        if 'retweeted_status' in msg:
            content = content + u' //@' + msg['retweeted_status']['user']['name'] + ' ' + msg['retweeted_status']['text']  #引用添加到内容中
        if 'retweeted_status' in msg and 'original_pic' in msg['retweeted_status']:
            content = content + u'<img src="'+ msg['retweeted_status']['original_pic'] +'" />'  #图片添加到内容中
        if 'original_pic' in msg and msg['original_pic']:
            content = content + u'<img src="'+ msg['original_pic'] +'" />'  #图片添加到内容中
        return content

    def getTranslateContent(self, msg):
        return msg['text']

    def getUserInfo(self, msg):
        return msg['user']

    def isWantedUser(self, accounts, user):
        if type(accounts) is not list:
            return False
        id = user['id']
        slug = user['domain']
        for account in accounts: 
            _uid = u'uid=%d&' % id
            _name = u'uname=%s&' % slug        
            if (account['orginal_user_id'] != None and int(account['orginal_user_id']) == id) \
            or (account['orginal_name'] != None and account['orginal_name'] != '' and account['orginal_name'].lower() == slug.lower())  \
            or (account['url'] != None and (account['url'].lower().find(_uid.lower()) != -1 or (slug != '' and account['url'].lower().find(_name.lower()) != -1))):
               return account
        return False

    def getMid(self, mid):
        if type(mid) is int:
            self.mid = '%d' % mid
        if type(mid) is unicode or type(mid) is str:
            return self.doMidToStr(mid)
        return ''
        
    def doMidToStr(self, mid):
        length = len(mid)
        if length > 7:
            return self.doMidToStr(mid[0 : length-7]) + self.intTo62(mid[length-7 : length])
        else:
            return self.intTo62(mid)

    def intTo62(self, int10):
        int10 = int(int10)
        s62 = ''
        while int10:
            r = int10 % 62
            s62 = self.str62keys(r) + s62
            int10 = math.floor(int10/62)
        return s62

    def str62keys(self, key):
        key = int(key)
        keys = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9","a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z","A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"]
        return keys[key]


class sohuSpider(spider):
    '''
    sohu spider
    '''
    def __init__(self):
        spider.__init__(self, 'sohu')

    def getCreatedDate(self, item):
        d = time.strptime(item.pubdate.string, '%a, %d %b %Y %H:%M:%S +0800')
        return int(time.mktime(d))

    def getContentData(self, item):
        content = item.description.string        
        return content

    def getLinkData(self, item):
        link = item.link.nextSibling
        return link

    def getTranslateContent(self, item):
        text = self.getContentData(item)        
        return text



class facebookSpider(spider):
    '''
    facebook spider
    '''
    def __init__(self):
        spider.__init__(self, 'facebook')
    def checkMsg(self,msgs):
        if(msgs.findAll('category') == 'PageSyndicationFeed'):
            return True
        else:
            return False
    def getCreatedDate(self, item):
        if(item.pubdate.string.endswith('0000')):
            d = time.strptime(item.pubdate.string, '%a, %d %b %Y %H:%M:%S +0000')
            return int(time.mktime(d)) + 8*3600
        else:
            d = time.strptime(item.pubdate.string, '%a, %d %b %Y %H:%M:%S -0800')
            return int(time.mktime(d)) + 16*3600


    def getContentData(self, item):
        content = item.description.string
        content = content.replace('&quot;', '"')
        content = content.replace('&gt;', '>')
        content = content.replace('&lt;', '<')
        content = content.replace('&amp;', '&')
        return content

    def getLinkData(self, item):
        link = item.link.nextSibling
        return link

    def getTranslateContent(self, item):
        text = self.getContentData(item)
        text = self.stripHTML(text)
        return text



class twitterSpider(spider):

    count = 200
    def __init__(self, oauth_params):
        spider.__init__(self, 'twitter')
        self.api = twitter.Api(consumer_key=oauth_params['consumer_key'], 
                    consumer_secret=oauth_params['consumer_secret'],
                    access_token_key=oauth_params['access_token_key'],
                    access_token_secret=oauth_params['access_token_secret'])

    def getNewMsgs(self):
        accounts = self.getAccountsByType(self.type)
        self.notice('begin to get friends timeline')
        msgs = self.api.GetHomeTimeline(count=self.count)
        self.saveMsgs2(msgs, accounts)

    def getUserInfo(self, msg):
        return msg.user

    def isWantedUser(self, accounts, user):
        for account in accounts:
            if account['orginal_user_id'] != None and user.id == int(account['orginal_user_id']):
                return account
        return False

    def getCreatedDate(self, msg):
        d = time.strptime(msg.created_at, '%a %b %d %H:%M:%S +0000 %Y')
        return int(time.mktime(d)) + 8*3600

    def getLinkData(self, msg):
        return 'https://twitter.com/#!/%s/status/%d' %(msg.user.screen_name, msg.id)

    def getContentData(self, msg):
        return msg.text

    def getTranslateContent(self, msg):
        text = msg.text
        return re.sub(r'http:\/\/t\.co\/[a-z0-9A-Z]{8}', '', text)







def sinaThread():
    s = sinaSpider(oauth_params=sina_api)
    loop = 1
    while True:
        log('sina', loop)
        loop += 1
        try:
            s.getNewMsgs()
        except Exception,e:
            print 'sinaThread exception:'
            print e
        time.sleep(180)
    del s

def qqThread():
    q = qqSpider(oauth_params=qq_api)
    loop = 1
    while True:
        log('qq', loop)
        loop += 1
        try:
            q.getNewMsgs()
        except Exception,e:
            print 'qqThread exception:'
            print e
        time.sleep(sleeptime)
    del q

def twitterThread():
    t = twitterSpider(oauth_params=twitter_api)    
    loop = 1
    while True:
        log('twitter', loop)
        loop += 1
        try:
            t.getNewMsgs()
        except Exception,e:
            print 'twitterThread exception:'
            print e
        time.sleep(sleeptime)
    del t

def twitter2Thread():
    t2 = twitterSpider(oauth_params=twitter2_api)    
    loop = 1
    while True:
        log('twitter2', loop)
        loop += 1
        try:
            t2.getNewMsgs()
        except Exception,e:
            print 'twitter2Thread exception:'
            print e
        time.sleep(sleeptime)
    del t2

def sohuThread():
    sh = sohuSpider()
    loop = 1
    while True:
        log('sohu', loop)
        loop += 1
        try:
            sh.getNewMsgs()
        except Exception,e:
            print 'sohuThread exception:'
            print e
        time.sleep(120)
    del sh

def facebookThread():
    f = facebookSpider()
    loop = 1
    while True:
        log('facebook', loop)
        loop += 1
        try:
            f.getNewMsgs()
        except Exception,e:
            print 'facebookThread exception:'
            print e
        time.sleep(120)
    del f

def log(name, loop):
    log_dir = getLogDir()
    file = '%s%s.log' % (log_dir, name)
    f = open(file, "a")
    print >> f, '%s Loop: %d' % (name, loop)
    print >> f, 'Begin At: %s' % time.strftime('%Y-%m-%d %H:%M:%S')
    f.close()



def main():
    threads = []
    sina_thread = threading.Thread(target=sinaThread, name='thread-sina')
    threads.append(sina_thread)
    #随主线程一起退出
    sina_thread.setDaemon(True)
    sina_thread.start()

    qq_thread = threading.Thread(target=qqThread, name='thread-qq')
    threads.append(qq_thread)
    #随主线程一起退出
    qq_thread.setDaemon(True)
    qq_thread.start()

    twitter_thread = threading.Thread(target=twitterThread, name='thread-twitter')
    threads.append(twitter_thread)
    #随主线程一起退出
    twitter_thread.setDaemon(True)
    twitter_thread.start()

    twitter2_thread = threading.Thread(target=twitter2Thread, name='thread-twitter2')
    threads.append(twitter2_thread)
    #随主线程一起退出
    twitter2_thread.setDaemon(True)
    twitter2_thread.start()

    sohu_thread = threading.Thread(target=sohuThread, name='thread-sohu')
    threads.append(sohu_thread)
    #随主线程一起退出
    sohu_thread.setDaemon(True)
    sohu_thread.start()

    facebook_thread = threading.Thread(target=facebookThread, name='thread-facebook')
    threads.append(facebook_thread)
    #随主线程一起退出
    facebook_thread.setDaemon(True)
    facebook_thread.start()

    #保证线程启动
    time.sleep(2)
    #阻塞主线程
    for t in threads:
        t.join()
    print '主线程结束', time.strftime('%Y-%m-%d %H:%M:%S')

def getFriends(oauth_params, oauth_params2):
    me = twitter.Api(consumer_key=oauth_params['consumer_key'], 
                    consumer_secret=oauth_params['consumer_secret'],
                    access_token_key=oauth_params['access_token_key'],
                    access_token_secret=oauth_params['access_token_secret'])
    other = twitter.Api(consumer_key=oauth_params2['consumer_key'], 
                    consumer_secret=oauth_params2['consumer_secret'],
                    access_token_key=oauth_params2['access_token_key'],
                    access_token_secret=oauth_params2['access_token_secret'])
    friends = other.GetFriendIDs()
    myfriends = me.GetFriendIDs()    
    for id in friends['ids']:
        if id not in myfriends['ids']:
            print 'follow %d' % id
            me.CreateFriendship(id)


def getLogDir():
    script_dir = os.path.dirname(os.path.realpath(__file__))
    log_dir = '%s/../../../log/' % script_dir
    return log_dir

if __name__ == '__main__':
    sleeptime = 60
    socket.setdefaulttimeout(3)
    if len(sys.argv) >=3 and sys.argv[1] == '-env' and sys.argv[2] == 'prod':
        twitter_api = config.prod_twitter_api
        twitter2_api = config.prod_twitter2_api
        sina_api = config.prod_sina_api
        qq_api = config.prod_qq_api
        message = '脚本将以生产环境的配置工作'
    else:
        twitter_api = config.dev_twitter_api
        twitter2_api = config.dev_twitter2_api
        sina_api = config.dev_sina_api
        qq_api = config.dev_qq_api
        message = '脚本将以开发环境的配置工作'
    print message
    main() 

    



