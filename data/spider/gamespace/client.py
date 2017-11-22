import json
import urllib
import settings

def post_json(j):
    ''' post json to server '''
    url = "http://localhost:%s/"%settings.PORT
    data = json.dumps(j)
    ret = urllib.urlopen(url,data).read()
    return ret

def print_jobs():
    ret = post_json({"op":"jobs"})
    d = json.loads(ret.split('\n',1)[0])
    for j in d:
        print j

print_jobs()
