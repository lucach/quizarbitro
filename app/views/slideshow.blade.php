<!DOCTYPE html>
<html>
    <head>
        <title>QuizRef</title>
        <meta charset="utf-8">
        <meta name="author" content="Luca Chiodini">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" type="text/css" href='//fonts.googleapis.com/css?family=Lato:300,400'>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/jquery.qtip.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
        <link rel="stylesheet" type="text/css" href="assets/css/index-slide.css"> 
        <link rel="stylesheet" type="text/css" href="assets/css/jquery.snappish.css">
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap-social.css">

        <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/jquery.qtip.min.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/javascript/jquery.mousewheel.min.js"></script>
        <script type="text/javascript" src="assets/javascript/jquery.event.move.min.js"></script>
        <script type="text/javascript" src="assets/javascript/jquery.event.swipe.min.js"></script>
        <script type="text/javascript" src="assets/javascript/jquery.snappish.min.js"></script>
        <script type="text/javascript" src="assets/javascript/jquery.bootstrap-growl.min.js"></script>        
        <script type="text/javascript" src="assets/javascript/functions.js"></script>

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="../assets/js/html5shiv.js"></script>
        <![endif]-->
    </head>
        <style type="text/css">
            #child {
                position: absolute;
                top: 50%;
                left: 37.5%;
                height: 30%;
                width: 75%;
                margin: -15% 0 0 -25%;
            }
            .snappish-main {
                background-image: inherit;
            }
            article.slide-1 {
                background-image: url('assets/images/walle.jpg');
            }
            #main-description {
                margin-top: 60px;
            }
            #right-menu {
                z-index: 1;
            }
            #suggest-register {
                position: absolute;
                top: auto;
                bottom: 5%;
            }
            .glyphicon {
                cursor: pointer;
                z-index: 2;
                font-size: 400%;
                position: absolute;
                left: 0;
                right: 0;
                margin: 0 auto;
            }
            .black {
                color: #000;
            }
            .white {
                color: #FFF;
            }
            .glyphicon.down {
                top: auto;
                bottom: 5%;
            }
            .glyphicon.up {
                top: 1%;
                bottom: auto;
            }
            .full-width {
                width: 100%;
            }
            a, a:hover {
                color: #FFF;
            }
            * {
                animation-iteration-count: 1
            }
        </style>
    </head>
    <body>
        <div id="wrapper">

            <div id="right-menu">
                {{ Form::open(array('url' => 'login', 'class' => 'form-inline', 'style' => 'display: inline;')) }}
                <div class="form-group" id="form-mail">
                    {{ Form::text('email', Input::old('email'), array('placeholder' => 'Email', 'class' => 'form-control')) }}
                </div>
                <div class="form-group" id="form-pwd">
                    {{ Form::password('password', array('placeholder' => 'Password', 'class' => 'form-control')) }}
                </div>
                {{ Form::submit('Accedi', array('class' => 'btn btn-default')) }}
                &nbsp;&nbsp; oppure &nbsp;&nbsp;
                <button type="button" onclick='location="{{url('/')}}/registration"' class="btn btn-primary">Registrati</button>

                <div style="margin-top:5px">
                    <p style="text-align:left; float:left">{{ Form::checkbox('remember_me') }} Ricordami</p>
                    <p id="lostpwd" style="margin-right:44%; padding-top: 2px">
                        <a id="lostpwd-link" href="password/reset">Hai perso la password?</a>
                    </p>
                    <a class="btn btn-small btn-social btn-facebook" href="facebook/login">
                        <i class="fa fa-facebook"></i> Accedi con Facebook
                    </a>
                </div>
                {{ Form::close() }}
            </div>

            <div class="snappish-main">
                <article class="slide-1 active">
                    <div id="child">
                        <h1>QuizRef</h1>
                        <p id="main-description">Una nuova piattaforma di quiz sul regolamento del calcio è arrivata. <br>Ed è pronta per stupirti. </p>
                    </div>
                    <span onclick="$('#wrapper').trigger('scrolldown.snappish')" class="white down glyphicon glyphicon-chevron-down"></span>
                </article>
                <article class="slide-2">
                    <span onclick="$('#wrapper').trigger('scrollup.snappish')" class="black up glyphicon glyphicon-chevron-up"></span>
                    <div class="image"></div>
                    <h1>Interattivo come nessun altro</h1>
                    <p>I quiz classici sono noiosi. Ma fanno parte del passato. <br> Grazie a un sistema interattivo, QuizRef rende l'esercitarsi divertente.</p>
                    <span onclick="$('#wrapper').trigger('scrolldown.snappish')" class="black down glyphicon glyphicon-chevron-down"></span>
                </article>
                <article class="slide-3">
                    <span onclick="$('#wrapper').trigger('scrollup.snappish')" class="black up glyphicon glyphicon-chevron-up"></span>
                    <div class="image"></div>
                    <h1>Tecnologico e moderno</h1>
                    <p>Laravel, AJAX, Bootstrap e tanto altro. <br> QuizRef usa le più avanzate tecnologie, per essere sempre al passo con i tempi.</p>
                    <span onclick="$('#wrapper').trigger('scrolldown.snappish')" class="black down glyphicon glyphicon-chevron-down"></span>
                </article>
                <article class="slide-4">
                    <span onclick="$('#wrapper').trigger('scrollup.snappish')" class="black up glyphicon glyphicon-chevron-up"></span>
                    <div class="image"></div>
                    <h1>Risposte a ogni domanda</h1>
                    <p>Domande e risposte sono il cuore di QuizRef.<br>Per questo su esse vi è la massima cura, con aggiornamenti continui. <br> Fatti da arbitri.</p>
                    <div id="suggest-register" class="col-md-offset-3 col-md-6">
                        <button type="button" onclick='location="{{url('/')}}/registration"' class="full-width btn btn-lg btn-primary">Registrati subito</button>
                    </div>
                </article>
            </div>
        </div>
    <script type="text/javascript">
        var $snappish = $('#wrapper').snappish();

        $(document).keydown(function(e){
            if (e.keyCode == 38 || e.keyCode == 33)  //freccia su o pageup 
                $('#wrapper').trigger('scrollup.snappish');     // scroll up one slide
            
            if (e.keyCode == 40 || e.keyCode == 34)
                $('#wrapper').trigger('scrolldown.snappish');
        });

        $snappish.on('scrollbegin.snappish', function(e, data) {
            data.toSlide.addClass('active');
            $('nav a').removeClass('active');
            $('nav a[data-slide-num="' + data.toSlideNum + '"]').addClass('active');
        }).on('scrollend.snappish', function(e, data) {
            $("#right-menu").css('color', data.toSlideNum > 0 ? 'black' : 'white');
            $("#lostpwd-link").css('color', data.toSlideNum > 0 ? 'black' : 'white');
            data.fromSlide.removeClass('active');
        });

        $('nav a').on('click', function(e) {
            e.preventDefault();
            $snappish.trigger('scrollto.snappish', $(this).data('slide-num'));
        });

        @foreach ($errors->all() as $message)
           showNotification('danger', "{{ $message }}");
        @endforeach
        @if (Session::has('info'))
            showNotification('info', "{{ Session::get("info") }}");
        @endif
        @if (Session::has('success'))
            showNotification('success', "{{ Session::get("success") }}");
        @endif
        @if (Session::has('message'))
            showNotification('info', "{{ Session::get("message") }}");
        @endif


      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-55128868-1', 'auto');
      ga('send', 'pageview');
    </script>
    </body>
</html>
