<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class MainController extends Controller
{
    // home page
    public function home()
	{
		return view('home');	
	}

	// Get the statistics for those inputs
	public function getStats(Request $request) 
	{
		$team = $request->team;
		$season = $request->season;
		$game = $request->game;

		// Query database

		return view('stats')->withTeam($team)->withSeason($season)->withGame($game);
	}

}
