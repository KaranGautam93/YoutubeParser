<html>
<head>
    <title> Youtube Trending @yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="{{secure_asset('/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{secure_asset('favicon-16x16.png')}}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
            integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
            crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .container {
            width: 95%;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        nav {
            background-color: #806e6f;
        }

        .card-content p a {
            color: black;
            font-weight: bold;
        }

        .loader-div {
            height: 50% !important;
        }

        body {
            color: black;
        }

        element.style {
            font-weight: bold;
        }

        .btn.disabled, .disabled.btn-large, .disabled.btn-small, .btn-floating.disabled, .btn-large.disabled, .btn-small.disabled, .btn-flat.disabled, .btn:disabled, .btn-large:disabled, .btn-small:disabled, .btn-floating:disabled, .btn-large:disabled, .btn-small:disabled, .btn-flat:disabled, .btn[disabled], .btn-large[disabled], .btn-small[disabled], .btn-floating[disabled], .btn-large[disabled], .btn-small[disabled], .btn-flat[disabled] {
            pointer-events: none;
            background-color: #2c8e8e !important;
            -webkit-box-shadow: none;
            box-shadow: none;
            color: #fff !important;
            cursor: default;
        }

        .swal-text {
            color: black;
            font-size: large;
            font-weight: bold;
        }

        .swal-footer {
            text-align: center;
        }

        @media only screen and (max-width: 400px) {
            .channel-card {
                margin-top: 20px;
            }
            .channel-card img{
                margin-left: 20% !important;
            }
            .brand-logo img
            {
                display: none !important;
            }
        }
        .player{
            width: 100% !important;
        }
    </style>
</head>
<body>
<ul class="sidenav" id="mobile-demo">
    <li><a
            onclick="fetchLatestVideos()">
            Fetch Latest Videos
        </a></li>
</ul>
<nav>
    <div class="nav-wrapper">
        <a href="https://<?php echo env('APP_URL')?>" class="brand-logo" style="margin-left: 10px;">
            <span style="font-size: large;"><img src="{{secure_asset('/favicon-32x32.png')}}"
                                                 style="vertical-align: middle"/>
                <span style="vertical-align: middle;">Youtube Trending</span>
            </span>
        </a>
        <a href="#" data-target="mobile-demo" class="sidenav-trigger"><i class="fa fa-bars"></i></a>

        <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li>
                <a class="waves-effect waves-light btn fetch-btn" style="font-weight: bold;"
                   onclick="fetchLatestVideos()">
                    <i class="fa fa-circle-o-notch fa-spin btnLoader" style="margin-top: -15px;display: none;"></i>
                    Fetch Latest Videos
                </a>

            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="flex-center position-ref full-height loader-div">
        <div class="content">
            <div class="preloader-wrapper big active">
                <div class="spinner-layer spinner-blue-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @yield('content')
</div>
</body>
</html>
<script
    src="{{secure_asset('/js/jquery.min.js')}}" type="text/javascript">
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var elems = document.querySelectorAll('.sidenav');
        var instances = M.Sidenav.init(elems, []);
    });

    function fetchLatestVideos() {
        $(".btnLoader").show();
        $(".fetch-btn").attr('disabled', true);
        $(".sidenav-overlay").css("display","none");
        $(".sidenav-overlay").css("opacity","0");
        $(".sidenav").css('transform','translateX(-105%)');
        $.ajax({
            url: 'https://<?php echo env("APP_URL") . "fetchLatestVideos" ?>',
            method: 'GET',
            success: function (response) {
                if(response['error'])
                {
                    $(".btnLoader").hide();
                    $(".fetch-btn").attr('disabled', false);
                    swal("", "An error occurred while fetching the latest videos...", "error");
                }
                else
                {
                    swal("", "Latest videos fetched. Redirecting to home...", "success");
                    setTimeout(() => {
                        window.location.href = 'https://<?php echo env("APP_URL")?>';
                    }, 3000);
                }
            },
            error: function (error) {
                $(".btnLoader").hide();
                $(".fetch-btn").attr('disabled', false);
                swal("", "An error occurred while fetching the latest videos...", "error");
            }
        });
    }
    const getBestQualityThumbnail = (thumbnails) => {
        if (thumbnails['high']) {
            return thumbnails['high']['url'];
        }
        if (thumbnails['medium']) {
            return thumbnails['medium']['url'];
        }
        if (thumbnails['standard']) {
            return thumbnails['standard']['url'];
        }
        return '/defaultThumbnail.png';
    }
</script>
@yield('script')
