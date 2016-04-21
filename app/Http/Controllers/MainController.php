<?php

/*

This php file is a controller that manages the routes for this application and all of our mysql queries. The home() 
route handles the GET request for the home.blade.php file and the getStats route handles the POST request for stats.blade.php file.

*/

namespace App\Http\Controllers;

use DB;

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
    public function getStats(Request $request)
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
                (retweet_count + favorite_count) AS popularity
                FROM TWEET as t
                WHERE ($gametime->hour - HOUR(t.created_at)) <= 5 and
                ($gametime->hour - HOUR(t.created_at)) >= 0 and
                DAY(t.created_at) = $gametime->day
                ORDER BY (retweet_count + favorite_count) DESC
                LIMIT 1"));

                $hoursbefore = intval($tweettime[0]->minutes_before / 60);
                $minutesbefore = fmod($tweettime[0]->minutes_before, 60);


                return view('stats')->withTeam($team)->withSeason($season)->withGame($game)->withTweettime($tweettime[0])->withGametime($gametime)->withHoursbefore($hoursbefore)->withMinutesbefore($minutesbefore);


		}
		else {

			/* We are currently planning to calculate the average over all teams for the highest tweet visibility by 
			   using a PHP adaptation on the above query.  
			*/
			/* Get all the game times */
			$gametimes = DB::select( DB::raw( "SELECT HOUR(start_datetime) as hour,
                        DAY(start_datetime) as day,
                        MINUTE(start_datetime) as minutes,
                        MONTH(start_datetime) as month
                        FROM GAME "));

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
                                DAY(t.created_at) = $gametime->day
                                ORDER BY (retweet_count + favorite_count) DESC
                                LIMIT 1"));

                               
                                $minutesbefore += $tweettime[0]->minutesbefore;

                        }

                        $average = $minutesbefore / count($gametimes);

                        $avghours = intval($average / 60);
                        $avgminutes = fmod($average, 60);
                }

        return view('stats')->withTeam($team)->withSeason($season)->withGame($game)->withAvghours($avghours)->withAvgminutes($avgminutes)->withGametime("")->withTweettime("");
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

