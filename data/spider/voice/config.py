# -*- coding: UTF-8 -*-
#twitter API
dev_twitter_api = {
    'consumer_key' : 'mJdu2FKVrVwOquMKDwILxg', 
    'consumer_secret' : 'HesKMDAJIBoCk8L3TNGCE7ckhKzbnNY1lCneLIGWIyA', 
    'access_token_key' : '456457058-nFx5iD9rPb5D78FKMKV24WrCI3qUMpHkcilXGybx',
    'access_token_secret' : 'DiLmBt11xvptr9uUg3Whsl9XHi3TLeVFueTG5lPo6zo'
    }
prod_twitter_api = {
    'consumer_key' : 'mJdu2FKVrVwOquMKDwILxg', 
    'consumer_secret' : 'HesKMDAJIBoCk8L3TNGCE7ckhKzbnNY1lCneLIGWIyA', 
    'access_token_key' : '456457058-nFx5iD9rPb5D78FKMKV24WrCI3qUMpHkcilXGybx',
    'access_token_secret' : 'DiLmBt11xvptr9uUg3Whsl9XHi3TLeVFueTG5lPo6zo'
    }
#twitter2 API
dev_twitter2_api = {
    'consumer_key' : 'YXp3XatePKJmSeyJB9nsQ', 
    'consumer_secret' : 'KOyF69LFCe3VcujZ6YKVs7dzzdf78ceC2KrEOdefuM', 
    'access_token_key' : '1701331926-H5tpoQltGeVEbl7yeFfLHP1yBy0PnaZ4mR5Te6s',
    'access_token_secret' : 'dlHlqIush3n3vxGSYCtjWmdZWvdGPMZeOn5qrTHOU'
    }
prod_twitter2_api = {
    'consumer_key' : 'YXp3XatePKJmSeyJB9nsQ', 
    'consumer_secret' : 'KOyF69LFCe3VcujZ6YKVs7dzzdf78ceC2KrEOdefuM', 
    'access_token_key' : '1701331926-H5tpoQltGeVEbl7yeFfLHP1yBy0PnaZ4mR5Te6s',
    'access_token_secret' : 'dlHlqIush3n3vxGSYCtjWmdZWvdGPMZeOn5qrTHOU'
    }
#sina weibo API
prod_sina_api = {
    'access_token_key_url' : 'http://api.hupu.com/weibo/sina_access_token_key',
    'home_timeline_url' : 'https://api.weibo.com/2/statuses/friends_timeline.json',
    }
dev_sina_api = { 
    'access_token_key_url' : 'http://mt.hoopchina.com/api_dev.php/weibo/sina_access_token_key',
    'home_timeline_url' : 'https://api.weibo.com/2/statuses/friends_timeline.json',
    }
#qq weibo API
prod_qq_api = {
    'consumer_key' : '801094981', 
    'consumer_secret' : '08bad8e1ca0e04d6e7677852d0046c2e', 
    'access_token_key' : '6b5b2f8dcaa54c278662868caff9052f',
    'access_token_secret' : '6b65fb11a2798f13216c3df6ca04468d',
    'home_timeline_url' : 'http://open.t.qq.com/api/statuses/home_timeline',
    'oauth_signature_method' : 'HMAC-SHA1',
    'oauth_version' : '1.0',
    }
dev_qq_api = {
    'consumer_key' : '801093121', 
    'consumer_secret' : 'b233aad30cd19ac9ee4331fdc0259fa1', 
    'access_token_key' : '176121e337474970b37dbdf3347cc9b7',
    'access_token_secret' : '999902bbd8061c06a2c96ec460b79eee',
    'home_timeline_url' : 'http://open.t.qq.com/api/statuses/home_timeline',
    'oauth_signature_method' : 'HMAC-SHA1',
    'oauth_version' : '1.0',
    }

#数据库交互接口
dev_api = {
    'save' : 'http://mt.hoopchina.com/api_dev.php/weibo/save',
	'getrss' : 'http://mt.hoopchina.com/api_dev.php/weibo/get_urls',	
    'update_last_update' : 'http://mt.hoopchina.com/api_dev.php/weibo/last_update',
    'accounts_by_type' : 'http://mt.hoopchina.com/api_dev.php/weibo/get_accounts_by_type',
    'accounts_by_type_and_url' : 'http://mt.hoopchina.com/api_dev.php/weibo/get_account_by_type_and_url',
    }
prod_api = {
    'save' : 'http://api.hupu.com/weibo/save',
	'getrss' : 'http://api.hupu.com/weibo/get_urls',	
    'update_last_update' : 'http://api.hupu.com/weibo/last_update',
    'accounts_by_type' : 'http://api.hupu.com/weibo/get_accounts_by_type',
    'accounts_by_type_and_url' : 'http://api.hupu.com/weibo/get_accounts_by_type_url',
	}
#有道翻译
prod_youdao = {
        'key' : 592900332,
        'keyfrom' : 'hoopchina-voice',
        'request_url' : 'http://fanyi.youdao.com/openapi.do?type=data&version=1.1'
        }
dev_youdao = {
        'key' : 592900332,
        'keyfrom' : 'hoopchina-voice',
        'request_url' : 'http://fanyi.youdao.com/openapi.do?type=data&version=1.1'
        }
