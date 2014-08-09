<!DOCTYPE html>
<html>
    <head>
        <title>QuizArbitro</title>
        <meta charset="utf-8">
        <meta name="author" content="Luca Chiodini">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" type="text/css" href='//fonts.googleapis.com/css?family=Lato:300,400'>
        <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/jquery.qtip.min.css">
        <link rel="stylesheet" type="text/css" href="assets/css/custom.css">
        <link rel="stylesheet" type="text/css" href="assets/css/index-slide.css"> 
        <link rel="stylesheet" type="text/css" href="assets/css/jquery.snappish.css">
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
            #main-description
            {
                margin-top: 60px;
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
                </div>
                {{ Form::close() }}
            </div>

            <div class="snappish-main">
                <article class="slide-1 active">
                    <div id="child">
                        <h1>Quiz Arbitro</h1>
                        <p id="main-description">Una nuova piattaforma di quiz sul regolamento del calcio è arrivata. <br>Ed è pronta per stupirti. </p>
                    </div>
                </article>
                <article class="slide-2">
                    <div class="image"></div>
                    <h1>Interattivo come nessun altro</h1>
                    <p>I quiz classici sono noiosi. Ma fanno parte del passato. <br> Con un sistema interattivo, con QuizArbitro esercitarsi diventa divertente.</p>
                </article>
                <article class="slide-3">
                    <div class="image"></div>
                    <h1>Tecnologico e moderno</h1>
                    <p>HTML5, AJAX, Bootstrap e tanto altro. <br> QuizArbitro può essere consultato alla perfezione da qualsiasi device. <br> Per soddisfare ogni caso d'uso.</p>
                </article>
                <article class="slide-4">
                    <div class="image"></div>
                    <h1>Risposte a ogni domanda</h1>
                    <p>Domande e risposte sono il cuore di QuizArbitro.<br>Per questo su esse vi è la massima cura, con aggiornamenti continui. <br> Fatti da arbitri.</p>
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


    </script>
    </body>
</html>
