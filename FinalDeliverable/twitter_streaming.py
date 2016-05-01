"""
Script used to stream data from twitter
"""

#Import the necessary methods from tweepy library
from pprint import pprint
from tweepy.streaming import StreamListener
from tweepy import OAuthHandler
from tweepy import Stream

#Variables that contains the user credentials to access Twitter API 
access_token = "706594642876354560-9vthy8knO0Ut3gi1xlGrEWZOm0nHv9X"
access_token_secret = "o1yj0zeWacKO9E00vVKCbmSp7bLjz9cED8ZQzFr9VmSq1"
consumer_key = "DcYwICTHxkh0DoUjVz5HfU9k0"
consumer_secret = "swE7t0rv2sR2Xf0qriVHFmVB3BGXaA5tFTGpUUMgNfdpdKoU4x"


#This is a basic listener that just prints received tweets to stdout.
class StdOutListener(StreamListener):

    def on_data(self, data):
        print data
        return True

    def on_error(self, status):
        print status


if __name__ == '__main__':

    #This handles Twitter authetification and the connection to Twitter Streaming API
    l = StdOutListener()
    auth = OAuthHandler(consumer_key, consumer_secret)
    auth.set_access_token(access_token, access_token_secret)
    stream = Stream(auth, l)

    #This line filter Twitter Streams to capture data by the keywords: 'python', 'javascript', 'ruby'
    stream.filter(track=['Celtics', 'Mavericks', 'Mavs', 'Nets', 'Rockets', 'Knicks', 'Grizzlies', '76ers', 'sixers', 'Pelicans', 'Raptors', 'Spurs', 'Bulls', 'Nuggets',
        'Cavaliers', 'Cavs', 'Timberwolves', 'twolves' 'Pistons', 'Thunder', 'Pacers', 'Blazers', 'Bucks', 'Jazz', 'Hawks', 'Warriors', 'goldenstate', 'Hornets', 'Clippers', 'Heat', 'Lakers',
        'Magic', 'orlandomagic', 'Suns', 'Wizards', 'Kings'])