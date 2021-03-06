<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
          <img src="http://findicons.com/files/icons/679/nx10/128/basketball.png"  alt="Basketball" width="50" height="50">
          <li role="presentation" class="active"><a href="/">Home</a></li> 
        </ul>
      </nav>
    
     <h1>NBA Twitter Statistics</h1>
      <p>Welcome to your source of twitter stats during the NBA 2016!</p>

      <hr>
     
    @if ($option == 'tweets')
        @foreach ($result as $r)
        <ul class="list-group">
          <li class="list-group-item">Text: {{ $r->tweet_text }} Popularity: {{ $r->popularity }} </li>
        </ul>
        @endforeach

    @elseif ($option == 'games')
        @foreach ($result as $r)
        <ul class="list-group">
          <li class="list-group-item">Team 1: {{ $r->team1 }}  Team 2: {{ $r->team2}} Start: {{ $r->start_datetime }}</li>
        </ul>
        @endforeach

    @else
      @foreach ($result as $r)
      <ul class="list-group">
        <li class="list-group-item">Name: {{ $r->city_and_name }} State: {{ $r->state }} Stadium: {{ $r->stadium }}</li>
      </ul>
      @endforeach

    @endif
      <br>

      
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
