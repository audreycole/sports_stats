<?php

/*

This php file is a controller that manages the routes for this application and all of our mysql queries. The home() 
route handles the GET request for the home.blade.php file and the getStats route handles the POST request for stats.blade.php file.

*/

namespace App\Http\Controllers;

use DB;

use Khill\Lavacharts\Lavacharts;

use Illuminate\Http\Request;

use App\Http\Requests;

class MainController extends Controller
{
   // home page
    public function home()
    {

        $teams = DB::table('TEAM')->select('team_id', 'city_and_name')->orderBy('city_and_name', 'asc')->get();
        $seasons = "Spring 2016";
        $games = DB::table('GAME')->select('game_id', 'start_datetime', 'away_team', 'home_team')->orderBy('game_id', 'asc')->get();

        return view('home')->withTeams($teams)->withSeasons($seasons)->withGames($games);
    }


    // Get the statistics for those inputs
    public function ratings(Request $request)
    {
        $team = $request->team;
        $season = $request->season;
        $game = $request->game;

        if($game != "ALL GAMES") {

            /* Get the selected game */
            $gametimes = DB::select( DB::raw( "SELECT HOUR(start_datetime) as hour,
            DAY(start_datetime) as day,
            MINUTE(start_datetime) as minutes,
            MONTH(start_datetime) as month
            FROM GAME
            WHERE game_id = $game"));

        	$gametime = $gametimes[0];

        	/* Get the most popular tweet within a 5 hour range of our game */
            $tweettime = DB::select( DB::raw("SELECT 
            60*($gametime->hour-HOUR(t.created_at)) + ($gametime->minutes-MINUTE(t.created_at)) AS minutes_before, 
            HOUR(t.created_at) as hour,
            MINUTE(t.created_at) as minutes,
            (retweet_count + favorite_count) AS popularity, 
            tweet_text, 
            screen_name
            FROM TWEET as t, USER as u
            WHERE ($gametime->hour - HOUR(t.created_at)) <= 5 and
            ($gametime->hour - HOUR(t.created_at)) >= 0 and
            DAY(t.created_at) = $gametime->day and 
            t.game_id = $game and 
            u.user_id_str = t.user_id_str
            ORDER BY (retweet_count + favorite_count) DESC
            LIMIT 1"));

            if(!$tweettime) {
            	$tweettime = DB::select( DB::raw("SELECT 
	            60*($gametime->hour-HOUR(t.created_at)) + ($gametime->minutes-MINUTE(t.created_at)) AS minutes_before, 
	            HOUR(t.created_at) as hour,
	            MINUTE(t.created_at) as minutes,
	            (retweet_count + favorite_count) AS popularity, 
	            tweet_text, 
                screen_name
	            FROM TWEET as t, USER as u
	            WHERE ($gametime->hour - HOUR(t.created_at)) <= 5 and
	            ($gametime->hour - HOUR(t.created_at)) >= 0 and
	            DAY(t.created_at) = $gametime->day and 
                u.user_id_str = t.user_id_str
	            ORDER BY (retweet_count + favorite_count) DESC
	            LIMIT 1"));
            }

            $hoursbefore = intval($tweettime[0]->minutes_before / 60);
            $minutesbefore = fmod($tweettime[0]->minutes_before, 60);
            $tweettext = $tweettime[0]->tweet_text;
            $toptweets = "";
            $username = $tweettime[0]->screen_name;

            /* Get the number of tweets each hour before the game within 5 hour range */
            $numtweets = DB::select( DB::raw("SELECT 
            ($gametime->hour-HOUR(t.created_at)) as hours_before, COUNT(*) as num
			from TWEET as t 
			WHERE ($gametime->hour-HOUR(t.created_at)) <= 5 and 
			($gametime->hour-HOUR(t.created_at)) >= 0 and 
			DAY(t.created_at) = $gametime->day 
			GROUP BY $gametime->hour-HOUR(t.created_at)
			ORDER BY $gametime->hour-HOUR(t.created_at)
			LIMIT 100"));

			$numberoftweets = \Lava::DataTable();
			if(count($numtweets) == 5) {
	           	$numberoftweets->addStringColumn('Hour(s) Before Game')
			      ->addNumberColumn('Number of Tweets')
			      ->addRow(["Hours Before: " . $numtweets[0]->hours_before,  $numtweets[0]->num])
			      ->addRow(["Hours Before: " . $numtweets[1]->hours_before,  $numtweets[1]->num])
			      ->addRow(["Hours Before: " . $numtweets[2]->hours_before,  $numtweets[2]->num])
			      ->addRow(["Hours Before: " . $numtweets[3]->hours_before,  $numtweets[3]->num])
			      ->addRow(["Hours Before: " . $numtweets[4]->hours_before,  $numtweets[4]->num]);
			}
			else if (count($numtweets) == 4) {
				$numberoftweets->addStringColumn('Hour(s) Before Game')
			      ->addNumberColumn('Number of Tweets')
			      ->addRow(["Hours Before: " . $numtweets[0]->hours_before,  $numtweets[0]->num])
			      ->addRow(["Hours Before: " . $numtweets[1]->hours_before,  $numtweets[1]->num])
			      ->addRow(["Hours Before: " . $numtweets[2]->hours_before,  $numtweets[2]->num])
			      ->addRow(["Hours Before: " . $numtweets[3]->hours_before,  $numtweets[3]->num]);
			}
			else if (count($numtweets) == 3) {
				$numberoftweets->addStringColumn('Hour(s) Before Game')
			      ->addNumberColumn('Number of Tweets')
			      ->addRow(["Hours Before: " . $numtweets[0]->hours_before,  $numtweets[0]->num])
			      ->addRow(["Hours Before: " . $numtweets[1]->hours_before,  $numtweets[1]->num])
			      ->addRow(["Hours Before: " . $numtweets[2]->hours_before,  $numtweets[2]->num]);
			}
			else if (count($numtweets) == 2) {
				$numberoftweets->addStringColumn('Hour(s) Before Game')
			      ->addNumberColumn('Number of Tweets')
			      ->addRow(["Hours Before: " . $numtweets[0]->hours_before,  $numtweets[0]->num])
			      ->addRow(["Hours Before: " . $numtweets[1]->hours_before,  $numtweets[1]->num]);
			}
			else {
				$numberoftweets->addStringColumn('Hour(s) Before Game')
			      ->addNumberColumn('Number of Tweets')
			      ->addRow(["Hours Before: " . $numtweets[0]->hours_before,  $numtweets[0]->num]);
			}

		    \Lava::BarChart('Number of Tweets', $numberoftweets);


            // Get the top 100 tweets for that game
            if($request->tweets) {
            	$toptweets = DB::select( DB::raw("SELECT 
                (60*($gametime->hour-HOUR(t.created_at)) + ($gametime->minutes-MINUTE(t.created_at)))/60 AS hours_before, 
                (60*($gametime->hour-HOUR(t.created_at)) + ($gametime->minutes-MINUTE(t.created_at))) % 60 AS minutes_before,
                HOUR(t.created_at) as hour,
                MINUTE(t.created_at) as minutes,
                (retweet_count + favorite_count) AS popularity, 
                tweet_text, 
                screen_name
                FROM TWEET as t, USER as u
                WHERE ($gametime->hour - HOUR(t.created_at)) <= 5 and
                ($gametime->hour - HOUR(t.created_at)) >= 0 and
                u.user_id_str = t.user_id_str and
                DAY(t.created_at) = $gametime->day
                ORDER BY (retweet_count + favorite_count) DESC
                LIMIT 100"));
            }

            /* Get the actual name for the game and team */
            $teamname = DB::select( DB::raw( "SELECT city_and_name
            FROM TEAM
            WHERE team_id = $team"));

            $gamename = DB::select( DB::raw( "SELECT g.start_datetime,
            	t1.city_and_name AS team1, t1.city_and_name AS team2
            FROM GAME AS g, TEAM AS t1, TEAM AS t2
            WHERE game_id = $game AND t1.team_id = g.away_team AND t2.team_id = g.home_team")); 

       
            return view('ratings')
            ->withTeamname($teamname)
            ->withSeason($season)
            ->withGamename($gamename)
            ->withTweettime($tweettime[0])
            ->withGametime($gametime)
            ->withHoursbefore($hoursbefore)
            ->withMinutesbefore($minutesbefore)   
            ->withTweettext($tweettext)
            ->withToptweets($toptweets)
            ->withNumberoftweets($numberoftweets)
            ->withUsername($username);
		}
		else {

			/* We are currently planning to calculate the average over all teams for the highest tweet visibility by 
			   using a PHP adaptation on the above query.  
			*/
			/* Get all the game times and game_ids */
			$gametimes = DB::select( DB::raw( "SELECT HOUR(start_datetime) as hour,
                        DAY(start_datetime) as day,
                        MINUTE(start_datetime) as minutes,
                        MONTH(start_datetime) as month, 
                        game_id
                        FROM GAME 
                        LIMIT 10"));



                        $minutesbefore = 0;

                        foreach($gametimes as $gametime) {
                                /* Get the most popular tweet within a 5 hour range of our game */

                                $tweettime = DB::select( DB::raw("SELECT 
                                60*($gametime->hour-HOUR(t.created_at)) + ($gametime->minutes-MINUTE(t.created_at)) AS minutes_before,
                                HOUR(t.created_at) as hour,
                                MINUTE(t.created_at) as minutes,
                                (retweet_count + favorite_count) AS popularity
                                FROM TWEET as t
                                WHERE ($gametime->hour - HOUR(t.created_at)) <= 5 and
                                ($gametime->hour - HOUR(t.created_at)) >= 0 and
                                DAY(t.created_at) = $gametime->day and 
                                t.game_id = $gametime->game_id
                                ORDER BY (retweet_count + favorite_count) DESC
                                LIMIT 1"));

                               
                                $minutesbefore += $tweettime[0]->minutes_before;
                                

                        }

                		$toptweets = "";

                        $average = $minutesbefore / count($gametimes);

                        $avghours = intval($average / 60);
                        $avgminutes = fmod($average, 60);
                }

        return view('ratings')
        ->withTeam($team)
        ->withSeason($season)
        ->withGame($game)
        ->withAvghours($avghours)
        ->withAvgminutes($avgminutes)
        ->withGametime("")
        ->withTweettime("")
        ->withToptweets($toptweets)
        ->withNumberoftweets("");
    }

    public function stats(Request $request) {

    	$option = $request->option;


    	// Get the top 100 tweets 
    	if ($option == 'tweets') {
    		$result = DB::select( DB::raw("SELECT
	                screen_name
                    HOUR(t.created_at) as hour,
	                MINUTE(t.created_at) as minutes,
	                DAY(t.created_at) as day,
	                MONTH(t.created_at) as month,
	                (retweet_count + favorite_count) AS popularity, 
	                tweet_text
	                FROM TWEET as t, USER as u
                    WHERE u.user_id_str = t.user_id_str
	                ORDER BY (retweet_count + favorite_count) DESC
	                LIMIT 100"));

    		return view('stats')->withOption($option)->withResult($result);
    	}
    	else if ($option == 'games') {
    		$teams = DB::select( DB::raw("SELECT
	                team_id
	                FROM TEAM"));
	        $result = Array();
	        
	        foreach($teams as $team) {       
		        $games = DB::select( DB::raw( "SELECT t1.city_and_name AS team1, t2.city_and_name AS team2, g.game_id AS game_id, g.start_datetime AS start_datetime
				FROM GAME as g, TEAM as t1, TEAM as t2
				WHERE t1.team_id = g.away_team and t2.team_id = g.home_team and (t1.team_id=$team->team_id OR t2.team_id=$team->team_id)
				ORDER BY g.start_datetime DESC"));
    			
    			// Add all the games to the result
    			foreach ($games as $game) {
    				array_push($result, $game);
    			}
    		}
    		return view('stats')->withOption($option)->withResult($result);
    	}
    	else { // $option == 'teams'
    		$result = DB::select( DB::raw("SELECT
	                *
	                FROM TEAM"));
    		return view('stats')->withOption($option)->withResult($result);
    	}

    }

    // Update the home page drop down options
    public function updateData(Request $request) {
    	$team = $request->team;

    	if($team == "ALL TEAMS") {
    		return response()->json([
        	'data' => 'ALL GAMES',
    		]);
    	}
    	else {
    		/* Get the games associated with that particular team we selected */
    		$games = DB::select( DB::raw( "SELECT t1.city_and_name AS team1, t2.city_and_name AS team2, g.game_id AS game_id, g.start_datetime AS start_datetime
			FROM GAME as g, TEAM as t1, TEAM as t2
			WHERE t1.team_id = g.away_team and t2.team_id = g.home_team and (t1.team_id=$team OR t2.team_id=$team)"));

			return response()->json([
        	'data' => $games,
    		]);
    	}
    }

}

