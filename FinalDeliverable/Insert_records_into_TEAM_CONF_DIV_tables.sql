INSERT into CONFERENCE (conference_id, name) VALUES
(1, 'Eastern'),
(2, 'Western');

INSERT into DIVISION (division_id, name) VALUES
(1, 'Atlantic'),
(2, 'Central'),
(3, 'Southeast'),
(4, 'Southwest'),
(5, 'Northwest'),
(6, 'Pacific');

INSERT into TEAM (team_id, city_and_name, name_short, city, state, country, stadium, conference_id, division_id, nickname) VALUES 
(1, 'Boston Celtics', 'Celtics', 'Boston', 'Massachusetts', 'USA', 'TD Garden', 1, 1, NULL),
(2, 'Brooklyn Nets', 'Nets', 'Brooklyn', 'New York', 'USA', 'Barclays Center', 1, 1, NULL),
(3, 'New York Knicks', 'Knicks', 'New York', 'New York', 'USA', 'Madison Square Garden', 1, 1, NULL),
(4, 'Philadelphia 76ers', '76ers', 'Philadelphia', 'Pennsylvania', 'USA', 'Wells Fargo Center', 1, 1, 'sixers'),
(5, 'Toronto Raptors', 'Raptors', 'Toronto', 'Ontario', 'Canada', 'Air Canada Centre', 1, 1, NULL),
(6, 'Chicago Bulls', 'Bulls', 'Chicago', 'Illinois', 'USA', 'United Center', 1, 2, NULL),
(7, 'Cleveland Cavaliers', 'Cavaliers', 'Cleveland', 'Ohio', 'USA', 'Quicken Loans Arena', 1, 2, 'cavs'),
(8, 'Detroit Pistons', 'Pistons', 'Detroit', 'Michigan', 'USA', 'The Palace of Auburn Hills', 1, 2, NULL),
(9, 'Indiana Pacers', 'Pacers', 'Indianapolis', 'Indiana', 'USA', 'Bankers Life Fieldhouse', 1, 2, NULL),
(10, 'Milwaukee Bucks', 'Bucks', 'Milwaukee', 'Wisconsin', 'USA', 'BMO Harris Bradley Center', 1, 2, NULL),
(11, 'Atlanta Hawks', 'Hawks', 'Atlanta', 'Georgia', 'USA', 'Philips Arena', 1, 3, NULL),
(12, 'Charlotte Hornets', 'Hornets', 'Charlotte', 'North Carolina', 'USA', 'Time Warner Cable Arena', 1, 3, NULL),
(13, 'Miami Heat', 'Heat', 'Miami', 'Florida', 'USA', 'American Airlines Arena', 1, 3, NULL),
(14, 'Orlando Magic', 'Magic', 'Orlando', 'Florida', 'USA', 'Amway Center', 1, 3, 'orlandomagic'),
(15, 'Washington Wizards', 'Wizards', 'Washington', 'District of Columbia', 'USA', 'Verizon Center', 1, 3, NULL),
(16, 'Dallas Mavericks', 'Mavericks', 'Dallas', 'Texas', 'USA', 'American Airlines Center', 2, 4, 'mavs'),
(17, 'Houston Rockets', 'Rockets', 'Houston', 'Texas', 'USA', 'Toyota Center', 2, 4, NULL),
(18, 'Memphis Grizzlies', 'Grizzlies', 'Memphis', 'Tennessee', 'USA', 'FedExForum', 2, 4, NULL),
(19, 'New Orleans Pelicans', 'Pelicans', 'New Orleans', 'Louisiana', 'USA', 'Smoothie King Center', 2, 4, NULL),
(20, 'San Antonio Spurs', 'Spurs', 'San Antonio', 'Texas', 'USA', 'AT&T Center', 2, 4, NULL),
(21, 'Denver Nuggets', 'Nuggets', 'Denver', 'Colorado', 'USA', 'Pepsi Center', 2, 5, NULL),
(22, 'Minnesota Timberwolves', 'Timberwolves', 'Minneapolis', 'Minnesota', 'USA', 'Target Center', 2, 5, 'twolves'),
(23, 'Oklahoma City Thunder', 'Thunder', 'Oklahoma city', 'Oklahoma', 'USA', 'Chesapeake Energy Arena', 2, 5, NULL),
(24, 'Portland Trail Blazers', 'Trail Blazers', 'Portland', 'Oregon', 'USA', 'Moda Center', 2, 5, 'blazers'),
(25, 'Utah Jazz', 'Jazz', 'Salt Lake city', 'Utah', 'USA', 'Vivint Smart Home Arena', 2, 5, NULL),
(26, 'Golden State Warriors', 'Warriors', 'Oakland', 'California', 'USA', 'Oracle Arena', 2, 6, 'goldenstate'),
(27, 'Los Angeles Clippers', 'Clippers', 'Los Angeles', 'California', 'USA', 'Staples Center', 2, 6, NULL),
(28, 'Los Angeles Lakers', 'Lakers', 'Los Angeles', 'California', 'USA', 'Staples Center', 2, 6, NULL),
(29, 'Phoenix Suns', 'Suns', 'Phoenix', 'Arizona', 'USA', 'Talking Stick Resort Arena', 2, 6, NULL),
(30, 'Sacramento Kings', 'Kings', 'Sacramento', 'California', 'USA', 'Sleep Train Arena', 2, 6, NULL);
