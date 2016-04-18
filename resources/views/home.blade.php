<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>NBA Sports Ad Statistics</title>

    <!-- Bootstrap -->
    <!--<link href="/templates/my_blog/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="{% static 'css/style.css' %}">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    
    <div class="container">
      <nav class="navbar navbar-inverse">
        <ul class="nav nav-pills">
          <!--<li role="presentation" class="active"><a href="/">Home</a></li> -->
          <img src="http://findicons.com/files/icons/679/nx10/128/basketball.png"  alt="Basketball" width="50" height="50">
        </ul>
      </nav>
    
      <h1>NBA Twitter Statistics</h1>
      <p>Welcome to your source of twitter stats during the NBA 2016!</p> 

      <hr>
        
     <form method="POST" action="/stats" >  {{ csrf_field() }}
      <fieldset class="form-group">
        <label for="sel1">Choose a Team:</label>
        <select class="form-control" id="sel1" name="team" onchange="changeTeam(this);">
          <option value=""> Select a Team </option>
          <option value="ALL TEAMS"> ALL TEAMS </option>
          @foreach ($teams as $team)
            <option value="{{ $team->team_id }}">{{ $team->city_and_name }}</option>
          @endforeach
        </select>
      </fieldset>
      <fieldset class="form-group">
         <label for="sel1">Choose a Season:</label>
          <select class="form-control" id="sel1" name="season">
            <option>Spring 2016</option>
          </select>
      </fieldset>
      <fieldset class="form-group">
        <label for="sel1">Choose a Game:</label>
        <select class="form-control" id="sel" name="game" onchange="">
          <option value=""> Select a Team </option>
          <option value="ALL GAMES"> ALL GAMES </option>
           <!--@foreach ($games as $game)
              <option value="{{ $game->game_id }}">{{ $game->start_datetime }} {{ $game->game_id }}</option>
            @endforeach -->
        </select>
      </fieldset>
      <button type="submit" class="btn btn-primary">Go</button>
      </form>


      <br>
      
    </div>

    <script type="text/javascript">

    function changeTeam(team) {
      var team = team.value;

      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

      if(team != "") {
        // AJAX request
        var formData = {team: team, _token: CSRF_TOKEN}; //Array 
 
        $.ajax({
            url : "/updateData",
            type: "POST",
            dataType:"json",
            data : formData,
            success: function(data, textStatus, jqXHR)
            {
                //data - response from server
                // You recieve ALL GAMES or the games that are associated with that team 
                console.log(data);
            },
            error: function (e)
            {
              console.log(e.responseText);
            }
        });
      }
    }

    </script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
