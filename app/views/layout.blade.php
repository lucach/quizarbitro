<!DOCTYPE html>
<html>
    <head>
        <title>QuizRef</title>
        <meta charset="utf-8">
        <meta name="author" content="Luca Chiodini">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" type="text/css" href='//fonts.googleapis.com/css?family=Lato:300,400'>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/qtip2/2.2.0/basic/jquery.qtip.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">
        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- TODO Is the following actually needed? -->
        <!--<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.0/basic/imagesloaded.pkg.min.js"></script>-->
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/flot/0.8.2/jquery.flot.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/flot/0.8.2/jquery.flot.time.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.7.0/moment.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.7.0/lang/it.min.js"></script>
        <script type="text/javascript" src="{{ asset('assets/javascript/jquery.bootstrap-growl.min.js') }}"></script>        
        <script type="text/javascript" src="{{ asset('assets/javascript/functions.js') }}"></script>
        @yield('assets')
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="../assets/js/html5shiv.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="navbar navbar-default navbar-static-top" role="navigation">
          <div class="container">
            <div class="navbar-header">
              <a class="navbar-brand" href="#">QuizRef</a>
            </div>
            <div class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li><a href="{{ url('/') }}/home">Home</a></li>
                <li><a href="{{ url('/') }}/profile">Profilo</a></li>
                <li><a href="{{ url('/') }}/history">Cronologia quiz</a></li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Nuovo quiz <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="{{ url('/') }}/newquiz/0">Quiz facile</a></li>
                    <li class="divider"></li>
                    <li><a href="{{ url('/') }}/newquiz/1">Quiz difficile</a></li>
                  </ul>
                </li>
              </ul>
              <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Autenticato come {{ $email = Auth::user()->name; }} <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                    <li><a href="{{ url('/') }}/logout">Esci</a></li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </div>
        
        @yield('content')

    </body>
</html>
