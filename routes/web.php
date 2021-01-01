<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/youtubeTrending');
});


Route::get('/youtubeTrending','YoutubeController@getTrendingVideos');

Route::get('/fetchLatestVideos','YoutubeController@fetchLatestVideos');

Route::get('/latestVideos','YoutubeController@latestVideos');

Route::get('/videoDetails/{id}','YoutubeController@videoDetailView');

Route::get('/getVideoDetails/{id}','YoutubeController@getVideoDetails');
