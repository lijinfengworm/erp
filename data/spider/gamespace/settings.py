#coding:utf-8
import logging
debug = False

if debug:
    DB_HOST = "192.168.8.11"
    DB_PORT = 3233
    DB_USERNAME = "root"
    DB_PASSWORD = "testserver"
    DB_DATABASE = "hc_new_test4"
    LOG_LEVEL = logging.DEBUG
else:
    DB_HOST = "192.168.5.240"
    DB_PORT = 3306
    DB_USERNAME = "hcgamespace"
    DB_PASSWORD = "liksduI346goro384iuewrJe"
    DB_DATABASE = "hc_new_gamespace"
    LOG_LEVEL = logging.INFO

OLD_DB_HOST = "192.168.1.51"
OLD_DB_PORT = 3306
OLD_DB_USERNAME = "hoopchina"
OLD_DB_PASSWORD = "mv5B8&#LSSL9exG,wTp"
OLD_DB_DATABASE = "hc_www"


ESPN_NBA_URL = "http://espn.go.com/nba/playbyplay?gameId=%s&period=0"

LOG_LEVEL = logging.DEBUG

DB_MATCHLIVE = "hoopMatchLive"
DB_MATCHES = "hoopMatches"
DB_MATCHSTATS = "hoopMatchStats"
DB_PLAYERMATCHSTATS = "hoopPlayerMatchStats"
DB_TEAMS = "hoopTeams"
DB_PLAYERS = "hoopPlayers"
DB_STADIUMS = "hoopStadiums"
DB_STANDINGS = "hoopStandings"
DB_SETTINGS = "hoopSettings"
DB_STATS = "hoopStats"

FMT = "%Y-%m-%d %H:%M:%S"

PORT = 8233
INTERVAL = 5

from ansistream import ColorizingStreamHandler
logging.StreamHandler = ColorizingStreamHandler
logging.basicConfig(level=LOG_LEVEL,format='[%(asctime)s]%(levelname)s:%(message)s', datefmt='%Y-%m-%d %H:%M:%S')
