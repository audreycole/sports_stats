"""
Script used to parse raw twitter json data and upload it in the form of MySQL 
statements to database
"""


import json, MySQLdb
from collections import defaultdict
from datetime import datetime

YEAR='2016'
DT_NOYEAR_LEN=20 
# Parsing the dates given by the REST api include the timezone offset, which cannot be processed by datetime library
# Meaning the datetime needs to be processed piecewise - all information except the year occurs in the first 20 chars
MAX_TIME_DIFF_SECONDS=36000 #We are interested in tweets 5 hours (36000 seconds) before/after each game
GAME, TIME=0, 1
min_tweet_criteria=['id_str', 'retweet_count', 'favorite_count', 'text', 'created_at']                 
all_team_names=['celtics', 'mavericks', 'mavs', 'nets', 'rockets', 'knicks', 'grizzlies', '76ers', 'sixers', 
                      'pelicans', 'raptors', 'spurs', 'bulls', 'nuggets', 'cavaliers', 'cavs', 'timberwolves', 
                      'twolves', 'pistons', 'thunder', 'pacers', 'blazers', 'bucks', 'jazz', 'hawks', 'warriors', 
                      'goldenstate', 'hornets', 'clippers', 'heat', 'lakers', 'magic', 'orlandomagic', 'suns', 
                      'wizards', 'kings']
                            

db=MySQLdb.connect(host='aa1fps5er4jc8y8.cveqyspqsytk.us-west-2.rds.amazonaws.com', port=3306, user='admin', 
                     passwd='chinchilla', charset='utf8')
cursor=db.cursor()
cursor.execute('USE NBA')
db.commit()

with open('twitter_stream.txt', 'U') as f:
    for line in f:
        while True:
            #try:
            jfile=defaultdict(str, json.loads(line))
            
            if all(jfile[key]!='' for key in min_tweet_criteria) and jfile['user']!='':
                retweeted_id=None
                if jfile['retweeted_status']!='' and jfile['retweeted_status'] is not None:
                    cmd="SELECT tweet_id_str FROM TWEET WHERE tweet_id_str=%s"
                    cursor.execute(cmd, (jfile['retweeted_status']['id_str'],))
                    retweeted_id=cursor.fetchone()
                    #  If it was a retweet but not found, we need to insert the ORIGINAL TWEET into the database
                    if retweeted_id is None:
                        cmd="INSERT IGNORE INTO USERS SET user_id_str=%s, name=%s, screen_name=%s"
                        cursor.execute(cmd, (jfile['retweeted_status']['user']['id_str'], 
                                             jfile['retweeted_status']['user']['name'], 
                                             jfile['retweeted_status']['user']['screen_name']))
                        db.commit()
                        found_team="''                        
                        for team_name in all_team_names:
                            if team_name in jfile['retweeted_status']['text'].lower():
                                found_team=team_name
                                break
                        if found_team!='':
                            cmd="SELECT team_id FROM TEAM WHERE name_short=%s OR nickname=%s"
                            cursor.execute(cmd, (found_team, found_team))
                            team_id=cursor.fetchone()[0] #Fetch first and only item from returned tuple
                            
                            cmd="SELECT game_id, start_datetime FROM GAME WHERE away_team=%s OR home_team=%s"
                            cursor.execute(cmd, (team_id, team_id))
                            games_and_times=cursor.fetchall()
                            game_id=None
                            for game_and_time in games_and_times:
                                time_diff=abs(game_and_time[1] - \
                                    datetime.strptime(jfile['retweeted_status']['created_at'][:DT_NOYEAR_LEN] 
                                       +' '+YEAR, '%a %b %d %H:%M:%S %Y'))
                                
                                if time_diff.seconds<MAX_TIME_DIFF_SECONDS:
                                    game_id=game_and_time[GAME]
                                    if game_id is not None:                                        
                                        cmd="""INSERT IGNORE INTO TWEET SET tweet_id_str=%s, retweet_count=%s, 
                                                    favorite_count=%s, tweet_text=%s, created_at=%s, game_id=%s, 
                                                    user_id_str=%s"""
                                        cursor.execute(cmd, (jfile['retweeted_status']['id_str'], 
                                                             jfile['retweeted_status']['retweet_count'], 
                                                             jfile['retweeted_status']['favorite_count'], 
                                                             jfile['retweeted_status']['text'], 
                                                             datetime.strptime(
                                                                 jfile['retweeted_status']['created_at'][:DT_NOYEAR_LEN] 
                                                                +' '+YEAR, '%a %b %d %H:%M:%S %Y'), 
                                                             int(game_id), 
                                                             jfile['retweeted_status']['user']['id_str']))
                                        db.commit()
                                    break

                if retweeted_id is None:
                    cmd="INSERT IGNORE INTO USERS SET user_id_str=%s, name=%s, screen_name=%s"
                    cursor.execute(cmd, (jfile['user']['id_str'], jfile['user']['name'], jfile['user']['screen_name']))
                    db.commit()
                    found_team=''
                    for team_name in all_team_names:
                        if team_name in jfile['text'].lower():
                            found_team=team_name
                            break
                    if found_team!='':
                        cmd="SELECT team_id FROM TEAM WHERE name_short=%s OR nickname=%s"
                        cursor.execute(cmd, (found_team, found_team))
                        team_id=cursor.fetchone()[0] #Fetch first and only item from returned tuple
                            
                        cmd="SELECT game_id, start_datetime FROM GAME WHERE away_team=%s OR home_team=%s"
                        cursor.execute(cmd, (team_id, team_id))
                        games_and_times=cursor.fetchall()
                        game_id=None
                        for game_and_time in games_and_times:
                            time_diff=abs(game_and_time[1]- \
                                datetime.strptime(jfile['created_at'][:DT_NOYEAR_LEN]+' '+YEAR, 
                                                      '%a %b %d %H:%M:%S %Y'))
                                
                            if time_diff.seconds<MAX_TIME_DIFF_SECONDS:
                                game_id=game_and_time[GAME]
                                if game_id is not None:                                        
                                    cmd="""INSERT IGNORE INTO TWEET SET tweet_id_str=%s, retweet_count=%s, 
                                            favorite_count=%s, tweet_text=%s, created_at=%s, game_id=%s, 
                                            user_id_str=%s"""
                                    cursor.execute(cmd, (jfile['id_str'], jfile['retweet_count'], 
                                                         jfile['favorite_count'], jfile['text'], 
                                                         datetime.strptime(jfile['created_at'][:DT_NOYEAR_LEN]+' '+YEAR,
                                                                           '%a %b %d %H:%M:%S %Y'), 
                                                         int(game_id), 
                                                         jfile['user']['id_str']))
                                    db.commit()
                                break
                else:
                    cmd="UPDATE TWEET SET retweet_count=%s WHERE tweet_id_str=%s"
                    cursor.execute(cmd, (jfile['retweeted_status']['retweet_count'], retweeted_id[0]))
                    #retweeted_id is a one-item tuple
                    db.commit()
            break
    db.close()
