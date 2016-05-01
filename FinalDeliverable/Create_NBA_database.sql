CREATE DATABASE NBA;

use NBA;

CREATE TABLE CONFERENCE (
conference_id TINYINT UNSIGNED NOT NULL,
name VARCHAR(30) NOT NULL,
PRIMARY KEY (conference_id)
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE DIVISION (
division_id TINYINT UNSIGNED NOT NULL,
name VARCHAR(30) NOT NULL,
PRIMARY KEY (division_id)
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;

-- city_and_name is not a violation of 3NF since for the Golden State Warriors,
-- city_and_name = "Golden State Warriors" but city = "Oakland"
CREATE TABLE TEAM (
team_id TINYINT UNSIGNED NOT NULL,
city_and_name VARCHAR(30) NOT NULL,
name_short VARCHAR(15) NOT NULL,
city VARCHAR(30) NOT NULL,
state VARCHAR(20) NOT NULL,
country VARCHAR(15) NOT NULL,
stadium VARCHAR(30) NOT NULL,
nickname VARCHAR(15),
conference_id TINYINT UNSIGNED NOT NULL,
division_id TINYINT UNSIGNED NOT NULL,
PRIMARY KEY (team_id),
FOREIGN KEY (conference_id) REFERENCES CONFERENCE (conference_id),
FOREIGN KEY (division_id) REFERENCES DIVISION (division_id)
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE GAME (
game_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
start_datetime TIMESTAMP NOT NULL,
away_team TINYINT UNSIGNED NOT NULL,
home_team TINYINT UNSIGNED NOT NULL,
PRIMARY KEY (game_id),
FOREIGN KEY (home_team) REFERENCES TEAM (team_id),
FOREIGN KEY (away_team) REFERENCES TEAM (team_id)
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE USERS (
user_id_str VARCHAR(19) NOT NULL,
name VARCHAR(40) NOT NULL,
screen_name VARCHAR(40) NOT NULL,
PRIMARY KEY (user_id_str)
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;

CREATE TABLE TWEET ( 
tweet_id_str VARCHAR(19) NOT NULL,
retweet_count INT UNSIGNED NOT NULL,
favorite_count INT UNSIGNED NOT NULL,
tweet_text VARCHAR(140) NOT NULL,
created_at TIMESTAMP NOT NULL,
game_id INT UNSIGNED NOT NULL,
user_id_str VARCHAR(19) NOT NULL,
PRIMARY KEY (tweet_id_str),
FOREIGN key (game_id) REFERENCES GAME (game_id),
FOREIGN key (user_id_str) REFERENCES USERS (user_id_str)
) engine = INNODB DEFAULT character SET = utf8 COLLATE = utf8_general_ci;
