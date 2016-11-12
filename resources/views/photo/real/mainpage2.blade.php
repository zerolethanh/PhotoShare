<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta name="event_id" content="<?= $event->id; ?>">
    <meta name="photo_index" content="">
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    {{--<link href="css/bootstrap.min.css" rel="stylesheet">--}}
    <link href="/css/christmas_button.css" rel="stylesheet">
    <link href="/css/photoshare.css" rel="stylesheet">
    <link href="/js/ajaxcombo/jquery.ajax-combobox.css" rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link rel="stylesheet" href="/css/blueimp-gallery.min.css">
    <link rel="stylesheet" href="/css/bootstrap-image-gallery.min.css">
    <style>
        .scrollable-menu {
            height: auto;
            max-height: 300px;
            overflow-x: hidden;
        }

        body {
            /* modal closed not show scroll bar 解決方法 */
            overflow-y: scroll !important;
        }
    </style>
</head>

<body>
<!--nav bar-->
<nav class="navbar navbar-default navbar-static-top">
    <div class="container-fluid bg">

        <div class="nav navbar-btn">
            <ul class="nav">
                <li>
                    <button type="button" class="btn btn-default"
                            id="self_events_button"
                            onclick="getLastSelfEvent()">
                        <span aria-hidden="true"><i class="glyphicon glyphicon-user"></i>自作</span>
                    </button>

                    <button type="button" class="btn btn-default"
                            id="shared_events_button"
                            onclick="getLastSharedEvent()">
                        <span aria-hidden="true"><i class="glyphicon glyphicon-heart"></i>共有</span>
                    </button>

                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#newAlbum">
                        <span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true">ALBUM</span>
                    </button>

                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#searchAlbum">
                        <span class="glyphicon glyphicon glyphicon-search" aria-hidden="true"></span>
                    </button>

                    <button class="btn btn-default dropdown-toggle pull-right" type="button" id="dropdownMenu1"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="caret"></span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="dropdownMenu1">
                        <li><a href="/auth/logout">ログアウト</a></li>
                    </ul>

                    <label class="pull-right navbar-btn"
                           style="color: white;"><?= ($user->name ?: str_limit($user->login_name, 8)) . "&nbsp;さん&nbsp;"; ?>
                    </label>

                    <!--                    <button type="button" class="btn btn-default pull-right" onclick="location.href='/auth/logout'">-->
                    <!--                        <span class="glyphicon glyphicon glyphicon-search" aria-hidden="true"></span>-->
                    <!--                    </button>-->
                </li>

            </ul>
        </div>

    </div>
</nav>
<!--create new album modal-->
<div id="newAlbum" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">新規アルバム</h5>
            </div>

            <div class="modal-body text-center">
                <form class="form-inline" role="form" method="post" action="/events/create">
                    <div class="form-group">
                        <input type="hidden" name="_token" value="<?= csrf_token() ?>"/>
                        <input type="text" name="event_name" class="form-control" placeholder="イベント名">
                        <input type="date" name="or_time" id="or_time" class="form-control"/>
                    </div>
                    <button type="submit" class="btn btn-primary">作成</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!--search  album modal-->
<div id="searchAlbum" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">アルバム検索</h5>
            </div>

            <div class="modal-body text-center">
                <div class="form-group">
                    <input type="text" id="searchBox" class="form-control" placeholder="イベント名、または#先頭でタグを検索">
                </div>

                <div id="searchResultTable"></div>

            </div>
        </div>
    </div>
</div>

<!--events and buttons-->
<div class="container-fluid">
    <div class="row container-fluid" id="buttons">

    </div>
</div>
<br>

<!-- images container-->
<div class="container" id="photos_links">

</div>


<br>
<!--members-->
<div class="container" id="members">

</div>
<!--download & 退室ボタン-->
<div class="container">

    <button class="btn btn-info btn-sm" style="margin-left:5px"
            type="button" onclick="location.href='/photos/download-all/'+getEventId();">
        <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Download
    </button>

    <button class="btn btn-default btn-sm" onclick="event_leave()">退室 <i class=" glyphicon glyphicon-log-out"></i>
    </button>
</div>

<hr>

<!--他のアルバム-->
<div class="container" id="other_albums">

</div>

<hr>
<!-- コメント-->
<div class="container" id="comment_form">

    <div class="form-group">
        <label> コメント：</label>

        <div id="commentList">
        </div>
    </div>

    <div class="form-group">
        <textarea name="body"
                  id="commentBody"
                  class="form-control">

        </textarea>
    </div>

    <div class="form-group">
        <button type="button" id="commentButton" class="btn btn-primary">投稿する</button>
    </div>

</div>

<hr>
{{--<br>--}}
{{--<div class="footer">--}}
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <script type="text/javascript">var a8 = 'a16092429141_2NT6DR_ESUZ76_2HOM_BUB81';
                    var rankParam = '_9OBrvsG4MFc0Zir4LF56qFJ3MFJW';
                    var bannerType = '0';
                    var bannerKind = 'item.fix.kind1';
                    var frame = '1';
                    var ranking = '1';
                    var category = '総合';</script>
                <script type="text/javascript" src="//rws.a8.net/rakuten/ranking.js"></script>
            </div>
        </div>
        <div class="row">
            <p class="col-md-offset-1 col-sm-12 col-lg-12 col-md-12">
                <a href={{url('progress')}}>@2015 - IE4A2班 -卒制</a>
                <a href="https://github.com/zerolethanh/PhotoShare">&nbsp;&nbsp; @Code on GitHub</a>
            </p>
        </div>
    </div>
{{--</div>--}}


<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script src="/js/jquery.blueimp-gallery.min.js"></script>
<script src="/js/bootstrap-image-gallery.min.js"></script>

<!--socket io チャット notification (more:photoshare-control.js)-->
<script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/1.3.7/socket.io.min.js"></script>

<!--modal無し用-->
<?php
if (request()->user()->blueimp_gallery_controls) {
    echo '<script src = "/js/blueimp-helper.js" ></script>
           <script src = "/js/blueimp-gallery.min.js" ></script >';
}
?>

<script src="/js/moment-with-locales.min.js"></script>

<!--photoshare control js-->
<script src="/js/photoshare-control.js"></script>
<script src="/js/photoshare-socketio.js"></script>
<script src="/js/photoshare-comments.js"></script>
<script src="/js/photoshare-photoLinks.js"></script>
<script src="/js/photoshare-share-ways.js"></script>


<script src="/js/ajaxcombo/jquery.ajax-combobox.js"></script>

</body>

</html>
