<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PhotoShare</title>

    <link href="/css/app.css" rel="stylesheet">
    {{--<link href="/css/snow.css" rel="stylesheet">--}}
    <link href="/css/photoshare.css" rel="stylesheet">
    <link href="/css/christmas_button.css" rel="stylesheet">

    {{--<link href="/css/login.css" rel="stylesheet">--}}
    {{--<script type="text/javascript" src="/js/snowstorm.js"></script>--}}
    {{--<script type="text/javascript" src="/js/lights.js"></script>--}}

    {{--<script src="http://yui.yahooapis.com/combo?2.6.0/build/yahoo-dom-event/yahoo-dom-event.js&2.6.0/build/animation/animation-min.js"></script>--}}

            <!-- Fonts -->
    {{--<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>--}}

            <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        body {
            background: url(/imgs/fuyu.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid"
            {{--style="background-image: url(/imgs/bg-christmas.png)"--}}
    >

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed"
                    data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/"
               style="color: brown;"
            >PhotoShare</a>
        </div>

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                {{--<li><a href="/auth/login-name"--}}
                {{--style="color: white!important;"--}}
                {{-->Qikログイン</a></li>--}}
                <li><a href="/auth/login" class="btn btn-success btn-xs"
                       style="color: white!important;"
                    >ログイン</a></li>
                <li><a href="/auth/register"
                       style="color: brown!important;"
                    >新規登録</a></li>

            </ul>
        </div>
    </div>
</nav>


@yield('content')

<footer class="footer">
    <div class="container">
        <p class="text-muted"><a href="/progress">@2015 - IE4A2班 -卒制</a></p>
    </div>
</footer>
{{--<div class="fir">--}}
{{--<div class="fir__item"></div>--}}
{{--<div class="fir__item"></div>--}}
{{--<div class="fir__item"></div>--}}

{{--<div class="fir__log"></div>--}}

{{--<div class="orbs orbs-1"></div>--}}
{{--<div class="orbs orbs-2"></div>--}}
{{--<div class="orbs orbs-3"></div>--}}
{{--<div class="orbs orbs-4"></div>--}}
{{--</div>--}}
        <!-- Scripts -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>
</html>
