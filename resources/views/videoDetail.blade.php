@extends('navbar')

@section('title',"- $id")

@section('content')
    <div style="margin-top: 10px;">
        <div class="row">
            <div id="player" class="col s12 m8 l8"></div>
            <div class="col s12 m4 l4 channel-card" style="display: none">
                <div class="card medium pull-right" style=" width: 100%;margin-top: 0px;">
                    <div class="card-image waves-effect waves-block waves-light" style="background-color: #383d41;text-align: center">
                        <img style="border-radius: 100%;max-height: 200px; max-width: 200px;margin-left: 25%" class="activator channel-image" src="" >
                    </div>
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4" style="font-weight: bold;">About this Channel<i class="fa fa-ellipsis-v right"></i></span>
                        <p id="channel-title" style="font-weight: bold"></p>
                        <p id="channel-subscribers"></p>
                    </div>
                    <div class="card-reveal">
                        <span class="card-title grey-text text-darken-4"><i class="fa fa-times right"></i></span>
                        <p id="channel-description"></p>
                    </div>
                </div>
            </div>
            <div class="col s12 m8 l8"><span id="videoTitle" style="font-weight: bold;"></span>
                <br/><span id="videoStatics"></span></div>
        </div>
        <div class="row">
            <div id="videoDescription" class="col s12 m8 l8">
            </div>

        </div>
        @endsection

        @section('script')
            <script>
                $(document).ready(function () {
                    fetchVideoDetails();
                })

                function fetchVideoDetails() {
                    $(".loader-div").show();
                    $.ajax({
                        url: 'https://<?php echo env("APP_URL") . "getVideoDetails/" ?><?php echo $id;?>',
                        method: 'GET',
                        success: function (response) {
                            $(".loader-div").hide();
                            $(".channel-card").css('display','block');
                            let result = JSON.parse(JSON.stringify(response));
                            let channelThumbnails = JSON.parse(result['channelThumbnails']);
                            $("#videoTitle").text(result['videoTitle']);
                            let videoStatics = '<i class="fa fa-eye"></i>' + convertNumberToStandardFormat(result['viewCount']);
                            videoStatics += '<i class="fa fa-thumbs-up" style="margin-left: 5px;"></i> ' + convertNumberToStandardFormat(result['videoLikes']);
                            videoStatics += '<i class="fa fa-thumbs-down" style="margin-left: 5px;"></i> ' + convertNumberToStandardFormat(result['videoDislikes']);
                            $("#videoDescription").html(result['videoDescription']);
                            $("#videoStatics").html(videoStatics);
                            $(".channel-image").attr('src',getBestQualityThumbnail( channelThumbnails));
                            $("#channel-title").text(result['channelTitle']);
                            let channelSubscribers = convertNumberToStandardFormat(result['channelSubscribers']) + ' subscribers';
                            $("#channel-subscribers").html(channelSubscribers);
                            $("#channel-description").html(result['channelDescription']);
                        },
                        error: function (error) {
                            $(".loader-div").hide();
                            swal( "","An error occurred. Please try again later.","error");
                        }
                    });
                }

                // 2. This code loads the IFrame Player API code asynchronously.
                var tag = document.createElement('script');

                tag.src = "https://www.youtube.com/iframe_api";
                var firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                // 3. This function creates an <iframe> (and YouTube player)
                //    after the API code downloads.
                var player;

                function onYouTubeIframeAPIReady() {
                    player = new YT.Player('player', {
                        height: '400',
                        width: '640',
                        videoId: '<?php echo $id?>',
                        events: {
                            'onReady': onPlayerReady,
                            'onStateChange': onPlayerStateChange
                        }
                    });
                }

                const getBestQualityThumbnail = (thumbnails) => {
                    if(thumbnails['high'])
                    {
                        return thumbnails['high']['url'];
                    }
                    if(thumbnails['medium'])
                    {
                        return thumbnails['medium']['url'];
                    }
                    if(thumbnails['standard'])
                    {
                        return thumbnails['standard']['url'];
                    }
                }

                // 4. The API will call this function when the video player is ready.
                function onPlayerReady(event) {
                    event.target.playVideo();
                }

                // 5. The API calls this function when the player's state changes.
                //    The function indicates that when playing a video (state=1),
                //    the player should play for six seconds and then stop.
                var done = false;

                function onPlayerStateChange(event) {

                }

                function stopVideo() {
                    player.stopVideo();
                }

                function convertNumberToStandardFormat(num) {
                    return num.toLocaleString('en-IN');
                }
            </script>
@endsection
