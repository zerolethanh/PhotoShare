<!DOCTYPE html>
<html lang="en">
<head>
    <title>PhotoShare</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet'
          type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

    <!-- Styles -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    {{-- <link href="{{ elixir('css/app.css') }}" rel="stylesheet"> --}}

    <style>
        body {
            font-family: 'Lato';
        }

        .fa-btn {
            margin-right: 6px;
        }
    </style>
</head>
<body id="app-layout">
<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#app-navbar-collapse">
                {{--<span class="sr-only">Toggle Navigation</span>--}}
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                PhotoShare
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
        {{--<ul class="nav navbar-nav">--}}
        {{--<li><a href="{{ url('/home') }}">Home</a></li>--}}
        {{--</ul>--}}

        <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ url('/login') }}">Login</a></li>
                    <li><a href="{{ url('/register') }}">Register</a></li>
                    <li><a href="/feedback">Feedback</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        </ul>
                    </li>
                @endif


            </ul>
        </div>
    </div>

</nav>

@yield('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <script type="text/javascript">var a8 = 'a16092429141_2NT6DR_ESUZ76_2HOM_BUB81';
                var rankParam = '4Z35LTMJ-f2yCkdL-K2XNH2Szf2S9cMgLZ9cLN2lyBmds_O43';
                var bannerType = '0';
                var bannerKind = 'item.fix.kind1';
                var frame = '1';
                var ranking = '1';
                var category = 'パソコン・周辺機器';</script>
            <script type="text/javascript" src="//rws.a8.net/rakuten/ranking.js"></script>
        </div>
    </div>
    {{--<div class="row">--}}
        {{--<div class="col-sm-2 col-md-2 col-md-offset-2">--}}
            {{--<!-- Rakuten Widget FROM HERE -->--}}
            {{--<script type="text/javascript">rakuten_affiliateId = "0ea62065.34400275.0ea62066.204f04c0";--}}
                {{--rakuten_items = "ranking";--}}
                {{--rakuten_genreId = "0";--}}
                {{--rakuten_recommend = "on";--}}
                {{--rakuten_design = "slide";--}}
                {{--rakuten_size = "120x240";--}}
                {{--rakuten_target = "_blank";--}}
                {{--rakuten_border = "on";--}}
                {{--rakuten_auto_mode = "on";--}}
                {{--rakuten_adNetworkId = "a8Net";--}}
                {{--rakuten_adNetworkUrl = "http%3A%2F%2Frpx.a8.net%2Fsvt%2Fejp%3Fa8mat%3D2NT6DR%2BESUZ76%2B2HOM%2BBS629%26rakuten%3Dy%26a8ejpredirect%3D";--}}
                {{--rakuten_pointbackId = "a16092429141_2NT6DR_ESUZ76_2HOM_BS629";--}}
                {{--rakuten_mediaId = "20011816";</script>--}}
            {{--<script type="text/javascript" src="//xml.affiliate.rakuten.co.jp/widget/js/rakuten_widget.js"></script>--}}
            {{--<!-- Rakuten Widget TO HERE -->--}}
            {{--<img border="0" width="1" height="1" src="https://www10.a8.net/0.gif?a8mat=2NT6DR+ESUZ76+2HOM+BS629" alt="">--}}
        {{--</div>--}}
        {{--<div class="col-sm-2 col-md-2">--}}
            {{--<a href="https://px.a8.net/svt/ejp?a8mat=2NT6DR+ETGESY+50+2HCB1D" target="_blank">--}}
                {{--<img border="0" width="100" height="60" alt=""--}}
                     {{--src="https://www24.a8.net/svt/bgt?aid=160924815896&wid=001&eno=01&mid=s00000000018015006000&mc=1"></a>--}}
            {{--<img border="0" width="1" height="1" src="https://www15.a8.net/0.gif?a8mat=2NT6DR+ETGESY+50+2HCB1D" alt="">--}}
        {{--</div>--}}
        {{--<div class="col-md-2">--}}
            {{--<script type="text/javascript">var a8 = 'a16092429141_2NT6DR_ESUZ76_2HOM_BU3I9';--}}
                {{--var rankParam = 'CcL.m7dWFn1zTywkgQ-OfwdWTnqmTViVYV19iV5jkc-6dVdzic1WFyJkZcCxx';--}}
                {{--var bannerType = '0';--}}
                {{--var bannerKind = 'travel.fix.kind5';--}}
                {{--var frame = '1';--}}
                {{--var ranking = '1';</script>--}}
            {{--<script type="text/javascript" src="//rws.a8.net/rakuten/ranking.js"></script>--}}
        {{--</div>--}}
    </div>
</div>
<!-- JavaScripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
{{-- <script src="{{ elixir('js/app.js') }}"></script> --}}
<?php include_once(resource_path('views/GA/analyticstracking.php')) ?>
</body>
</html>
