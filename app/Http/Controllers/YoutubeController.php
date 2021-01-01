<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Video;
use Exception;
use App\Libraries\Youtube;

class YoutubeController extends Controller{
    private $youtube;
	public function __construct(Youtube $youtube)
	{
		//do initialization here
        $this->youtube = $youtube;
	}

	public function getTrendingVideos()
	{
		return view('youtubetrending');

	}

	public function fetchLatestVideos()
	{
	    try
        {
            $videos = $this->youtube->addVideos();
            return response()->json($videos,200);
        }
        catch (Exception $exception)
        {
            \Log::error($exception);
            return response()->json(['error' => true],500);
        }

	}

	public function latestVideos()
    {
        try
        {
            $videos = Video::getVideos();
            return response()->json($videos,200);
        }
        catch (Exception $exception)
        {
            \Log::error($exception);
            return response()->json(['error' => true],500);
        }
    }

    public function videoDetailView($id)
    {
        return view('videoDetail',compact('id'));
    }

    public function getVideoDetails($id)
    {
        try
        {
            $videoDetails = Video::getDetails($id);
            return response()->json($videoDetails,200);
        }
        catch (Exception $exception)
        {
            \Log::error($exception);
            return response()->json(['error' => true],500);
        }
    }
}
