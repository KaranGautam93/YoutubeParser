<?php


namespace App\Libraries;
use App\Video;
use Illuminate\Support\Facades\DB;
use Exception;

class Youtube
{

    private $accessToken;

    public function __construct()
    {
        //do initialization here

    }

    private function setAccessToken()
    {
        $curl = curl_init();
        $url = env('YOUTUBE_OAUTH_URL');
        $postField = "client_id=".env('YOUTUBE_CLIENT_ID')."&client_secret="
            .env('YOUTUBE_CLIENT_SECRET')."&grant_type=refresh_token&refresh_token=".
            env('YOUTUBE_REFRESH_TOKEN');
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postField,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response,true);
        $this->accessToken = $response['access_token'];
        curl_close($curl);
    }


    public function fetchLatestVideos()
    {
        if (!isset($this->accessToken)) {
            $this->setAccessToken();
        }
        $result = ['error' => true];
        $url = env('YOUTUBE_API_URL');
        $url .= "videos?part=snippet%2CcontentDetails%2Cstatistics&chart=mostPopular&maxResults=50&regionCode=IN&key=" . env('YOUTUBE_API_KEY');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->accessToken));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpCode == 200) //success
        {
            $result['error'] = false;
            $result['response'] = json_decode($response,true);
        }
        return $result;
    }
    public function fetchChannel($channel)
    {
        $url = env('YOUTUBE_API_URL');
        $id = $channel['channel_id'];
        $key = env('YOUTUBE_API_KEY');
        $url.="channels?part=snippet%2CcontentDetails%2Cstatistics&id=$id&key=$key";
        $result = ['error' => true];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$this->accessToken));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpCode == 200) //success
        {
            $result['error'] = false;
            $result['response'] = json_decode($response,true);
        }
        return $result;
    }
    public function addChannel($channel)
    {
        $channelDetails = $this->fetchChannel($channel);
        if($channelDetails['error'])
        {
            return;
        }
        $channelItem = $channelDetails['response']['items'][0];
        $data = [];
        $data['channel_id'] = $channelItem['id'];
        $data['title'] = $channelItem['snippet']['title'];
        $data['description'] = $channelItem['snippet']['description'];
        $data['thumbnails'] = json_encode($channelItem['snippet']['thumbnails']);
        $data['subscribers'] = $channelItem['statistics']['subscriberCount'];
        DB::table('channels')->updateOrInsert(['channel_id' => $data['channel_id']],$data);
    }

    public function addVideos()
    {
        $videos = $this->fetchLatestVideos();
        if($videos['error'])
        {
            return $result['error'] = true;
        }
        $videoItems = $videos['response']['items'];
        foreach ($videoItems as $item)
        {
            try
            {
                $data = [];
                $data['video_id'] = $item['id'];
                $data['title'] = $item['snippet']['title'];
                $data['description'] = $item['snippet']['description'];
                $data['thumbnails'] = json_encode($item['snippet']['thumbnails']);
                $data['url'] = "https://www.youtube.com/watch?v=".$item['id'];
                $data['likes'] = $item['statistics']['likeCount'];
                $data['dislikes'] = $item['statistics']['dislikeCount'];
                $data['views'] = $item['statistics']['viewCount'];
                $data['channel_id'] = $item['snippet']['channelId'];
                DB::table('videos')->updateOrInsert(['video_id' => $data['video_id'],
                    'channel_id' => $data['channel_id']],$data);
                $channel = [];
                $channel['channel_id'] = $data['channel_id'];
                $channel['title'] = $item['snippet']['channelTitle'];
                $this->addChannel($channel);
            }
            catch (Exception $exception)
            {
                \Log::error($item);
                \Log::error($exception);
            }

        }
        return ['error' => false];
    }
}
