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
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap-social.css') }}">

        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <!-- TODO Is the following actually needed? -->
        <!--<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.2.0/basic/imagesloaded.pkg.min.js"></script>-->
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
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
              <a class="navbar-brand" href="#"><img style="width:100px" src="{{ asset('assets/images/logo.png') }}"></img></a>
            </div>
            <div class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li><a href="{{ url('/') }}/">Home</a></li>
              </ul>
            </div>
          </div>
        </div>
        
        @yield('content')

    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-55128868-1', 'auto');
      ga('send', 'pageview');
    </script>
    </body>
</html>
