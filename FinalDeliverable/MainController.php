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
        		/* Get teams and games */
                $teams = DB::table('TEAM')->select('team_id', 'city_and_name')->orderBy('city_and_name', 'asc')->get();
                $seasons = "Spring 2016";

                $gameids = DB::table('GAME')->select('game_id')->orderBy('game_id', 'asc')->get();

                return view('home')->withTeams($teams)->withSeasons($seasons)->withGameids($gameids);
        }

    // Get the statistics for those inputs
    public function getStats(Request $request)
        {
                $team = $request->team;
                $season = $request->season;
                $game = $request->game;
				
				if($game != "ALL GAMES") {

					/* Get the selected game */
					$gametime = DB::select( DB::raw( "SELECT HOUR(start_datetime) as hour,
					DAY(start_datetime) as day,
					MINUTE(start_datetime) as minutes,
					MONTH(start_datetime) as month
					FROM GAME
					WHERE game_id = $game"));

				    $firstgame = $gametime[0];

				    /* Get the most popular tweet within a 5 hour range of our game */
					$time = DB::select( DB::raw("SELECT HOUR(t.created_at) as hour,
					MINUTE(t.created_at) as minutes,
					(retweet_count + favorite_count) AS popularity
					FROM TWEET as t
					WHERE ($firstgame->hour - HOUR(t.created_at)) <= 5 and
					DAY(t.created_at) = $firstgame->day
					ORDER BY (retweet_count + favorite_count) DESC
					LIMIT 1"));

				}
				else {

					/* We are currently planning to calculate the average over all teams for the highest tweet visibility by 
					   using a PHP adaptation on the above query.  
					*/

				}

                return view('stats')->withTeam($team)->withSeason($season)->withGame($game)->withTime($time)->withFirstgame($firstgame);
        }

}

