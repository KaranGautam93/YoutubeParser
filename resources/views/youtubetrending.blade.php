@extends('navbar')
@section('content')
    <div class="flex position-ref" style="margin-top: 10px;">
        <div class="content video-list">
            <div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>

    $(document).ready(function () {
        getLatestYoutubeVideos()
    });

    function getLatestYoutubeVideos() {
        $(".loader-div").show();
        $.ajax({
            url: 'http://<?php echo env("APP_URL") . "latestVideos" ?>',
            method: 'GET',
            success: function (response) {
                $(".loader-div").hide();
                const result = JSON.parse(JSON.stringify(response));
                if(result.length == 0)
                {
                    swal("","We didn't find any trending video in our system. Please click on fetch latest videos button.","info");
                }
                let i = 0;
                for (i = 0; i < result.length; i = i + 4) {
                    let str = '<div class="row">';
                    var j = i;
                    for (; j < result.length && j < i + 4; j++) {
                        let defaultThumbNail = JSON.parse(result[j]['thumbnails']);
                        defaultThumbNail = defaultThumbNail['standard']['url'];
                        str += '<div class="col s12 m3 mt-auto">' +
                            '<div class="card small">' +
                            '<div class="card-image">' +
                            '<a href="http://<?php echo env("APP_URL")  ?>videoDetails/' + result[j]['video_id'] + '" > <img src="' + defaultThumbNail + '">' +
                            '</div>' +
                            '<div class="card-content">' +
                            '<p><a href="http://<?php echo env("APP_URL")  ?>videoDetails/' + result[j]['video_id'] + '" > ' + result[j]['title'] + '</a></p>' +
                            '</div>' +

                            '</div>' +
                            '</div>';
                    }
                    str += '</div>';
                    $('.video-list').append(str);
                }
            },
            error: function (error){
                $(".loader-div").hide();
                swal( "","An error occurred. Please try again later.","error");
            }
        });
    }
</script>
@endsection
