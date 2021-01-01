<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Video extends Model
{
    protected $fillable = ['video_id','likes','dislikes','views','channel_id',
        'video_id','description','title','url','thumbnails'];

    public static function getVideos()
    {
        return Video::select('video_id','thumbnails','title')->orderBy('created_at','desc')->take(30)->get()->toArray();
    }

    public static function getDetails($id)
    {
        $data =  Video::join('channels','channels.channel_id','=','videos.channel_id')
            ->select('videos.title as videoTitle','videos.description as videoDescription',
            'videos.url as videoUrl','videos.thumbnails as videoThumbnails','videos.views as viewCount',
            'videos.likes as videoLikes','videos.dislikes as videoDislikes','channels.title as channelTitle',
                'channels.description as channelDescription','channels.thumbnails as channelThumbnails',
            'channels.subscribers as channelSubscribers')
            ->where('video_id',$id)->get()->toArray();
        return $data[0];
    }


}
