import os, sys
import time
import datetime

import simplejson
import twitter

if __name__ == '__main__':
    # api = twitter.Api(consumer_key='2UXhuafe6sk9h26vkYSag',
    #                   consumer_secret='VI5LK9neYKuyRILPvummqeNdNpyQTR2hBUde1KzMfAc',
    #                   access_token_key='16133-hobwje3qKNw4gk78c9ADULGz6waIt9RJcUSKPFb2R9k',
    #                   access_token_secret='Pyi3M8qZozsQo1weEtta49nCekvKIXHylrg39Qpsduc',
    #                   debugHTTP=False)
    api = twitter.Api(consumer_key='scMsCnNpGCcYrCk13zhxGA',
                      consumer_secret='F8xSPMjsYL8wRGBK1CfZvSECiKZLPbVGC5D4WxuwB4',
                      access_token_key='8437862-ssS7g0tXQOFcj4T1z9GH6iO2dIrCypIDWC8ZTJsilU',
                      access_token_secret='09w1xvOECOdqYUplpkuDCoAWTqIE5WQcjwQi6Wibzs',
                      debugHTTP=False)

    print api.VerifyCredentials()

    since_id = None
    #
    # try:
    #     print '-+'*10, 'Rate Limit Status'
    #     print api.GetRateLimitStatus(resources="statuses,friends")
    # finally:
    #     print '*'*50
    #
    # try:
    #     print '-+'*10, 'Followers'
    #     for status in api.GetFollowers():
    #         item = status.AsDict()
    #         print item
    # finally:
    #     print '*'*50
    
    try:
        print '-+'*10, 'Friends'
        for user in api.GetFriends():
            item = user.AsDict()
            print item
    finally:
        print '*'*50
    
    try:
        print '-+'*10, 'Friend IDs'
        for item in api.GetFriendIDs():
            print item
    finally:
        print '*'*50
    
    try:
        print '-+'*10, 'User Timeline'
        for status in api.GetUserTimeline(exclude_replies=True):
            item = status.AsDict()
            print item
    finally:
        print '*'*50
    
    try:
        print '-+'*10, 'UsersLookup'
        print api.UsersLookup(screen_name=["bear_foo_bar", "bear9292949492929"])
    finally:
        print '*'*50
    
    try:
        print '-+'*10, 'Favorites'
        for status in api.GetFavorites():
            item = status.AsDict()
            print item
    finally:
        print '*'*50
    
    try:
        print '-+'*10, 'Mentions'
        for status in api.GetMentions():
            item = status.AsDict()
            print item
    finally:
        print '*'*50
    
    try:
        print '-+'*10, 'User search'
        for user in api.GetUsersSearch(term='bear'):
            item = user.AsDict()
            print item
    finally:
        print '*'*50
    
    try:
        print '-+'*10, 'Retweets by User'
        for status in api.GetUserRetweets():
            item = status.AsDict()
            print item
    finally:
        print '*'*50
    
    try:
        print '-+'*10, 'Retweets'
        for status in api.GetRetweets(276761726274387968):
            item = status.AsDict()
            print item
    finally:
        print '*'*50
    
    try:
        print '-+'*10, 'RetweetsOfMe'
        for status in api.GetRetweetsOfMe():
            item = status.AsDict()
            print item
    finally:
        print '*'*50
    
    try:
        print '-+'*10, 'Replies'
        for status in api.GetReplies():
            item = status.AsDict()
            print item
    finally:
        print '*'*50
    
    try:
        print '-+'*10, 'Lists'
        for status in api.GetLists(screen_name='bear'):
            item = status.AsDict()
            print item
    finally:
        print '*'*50
    
    # try:
    #     print '-+'*10, 'Post Test'
    #     api.PostUpdate('Testing python-twitter %s' % time.time())
    # finally:
    #     print '*'*50
    # 
    # try:
    #     print '-+'*10, 'Post Test - unicode'
    #     s = '\xE3\x81\x82' * 10
    #     api.PostUpdate('%s %s' % (s, time.time()))
    # finally:
    #     print '*'*50
    #
    # try:
    #     print '-+'*10, 'Post Test - unicode'
    #     s = '\u1490'
    #     api.PostUpdate('%s %s' % (s, time.time()))
    # finally:
    #     print '*'*50
    # try:
    #     print '-+'*10, 'Post Test - unicode'
    #     # make sure input encoding param is set
    #     s = u'\u3042' * 10
    #     api.PostUpdate(u'%s %s' % (s, time.time()))
    # finally:
    #     print '*'*50
    #
    # try:
    #     print '-+'*10, 'Retweet Test'
    #     api.PostRetweet(313507139496837120)
    # finally:
    #     print '*'*50
    # 
    try:
        print '-+'*10, 'create friendship link'
        print api.CreateFriendship(screen_name="coda").AsDict()
    finally:
        print '*'*50

    try:
        print '-+'*10, 'destroy friendship link'
        print api.DestroyFriendship(screen_name="coda").AsDict()
    finally:
        print '*'*50
    



