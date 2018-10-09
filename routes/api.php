<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->get('podcasts/{status}', 'App\API\V1\Controllers\PodcastController@index')->name('podcast.index');
    $api->get('podcast/{id}', 'App\API\V1\Controllers\PodcastController@show')->name('podcast.show');
    $api->post('podcast/create', 'App\API\V1\Controllers\PodcastController@store')->name('podcast.store');
    $api->patch('podcast/{id}', 'App\API\V1\Controllers\PodcastController@update')->name('podcast.update');
    $api->delete('podcast/{id}', 'App\API\V1\Controllers\PodcastController@destroy')->name('podcast.destroy');
    $api->post('approve-podcast/{id}', 'App\API\V1\Controllers\PodcastController@approvePodcast')->name('podcast.approve');

    $api->post('comment/{podcast_id}', 'App\API\V1\Controllers\CommentController@store')->name('comment.store');
    $api->post('flag-comment/{comment_id}', 'App\API\V1\Controllers\CommentController@flagComment')->name('comment.flag');
});
