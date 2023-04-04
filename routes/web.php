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

Route::get('/login', 'Auth\AuthController@index');
Route::get('/user', 'Client\UserController@index');

Route::namespace('Auth')->group(function () {
    Route::get('login/{provider}', 'AuthController@redirectToProvider');
    Route::get('auth/{provider}/callback', 'AuthController@handleProviderCallback');
});

Route::group([
    'namespace' => 'Client',
    'middleware' => 'throttle:60,1'
], function () {
    Route::get('series/{serie}/posts', 'Series\GetPost');
    Route::resource('series', 'SerieController')->only(['index', 'show']);

    Route::get('tags/{tag}/posts', 'Tags\GetPost');
    Route::resource('tags', 'TagController')->only(['index', 'show']);

    Route::get('/posts/{post}/get-comment', 'Posts\GetComment');
    Route::resource('posts', 'PostController')->only(['index', 'show']);

    Route::post('votes/{post}', 'VoteController@store')->middleware('authenticate');

    Route::group([
        'prefix' => 'bookmarks',
        'middleware' => 'authenticate',
    ], function () {
        Route::get('/', 'BookmarkController@index');
        Route::post('{post}', 'BookmarkController@store');
        Route::delete('{post}', 'BookmarkController@destroy');
    });

    Route::post('/feedbacks', 'FeedbackController@store')->middleware('authenticate');

    Route::group([
        'prefix' => 'notifications',
        'middleware' => 'authenticate',
    ], function () {
        Route::get('/', 'NotificationController@index');
        Route::post('/mark-all-as-read', 'Notifications\MarkAllAsRead');
    });

    Route::group([
        'prefix' => 'comments',
        'middleware' => 'authenticate',
    ], function () {
        Route::post('/{post}', 'CommentController@store');
        Route::put('/{post}/{comment}', 'CommentController@update');
        Route::delete('/{post}/{comment}', 'CommentController@destroy');
    });

    Route::group([
        'prefix' => 'user',
        'middleware' => 'authenticate',
    ], function () {
        Route::post('/', 'UserController@logout');
        Route::put('/', 'UserController@update');
    });
});

Route::group([
    'namespace' => 'Admin',
    'prefix' => 'admin',
    'middleware' => ['throttle:60,1', 'authenticate'],
], function () {
    Route::resource('images', 'ImageController')->only(['index', 'store'])
        ->middleware('verify-admin');

    Route::get('series/{serie}/posts', 'Series\GetPost')->middleware('verify-admin');
    Route::resource('series', 'SerieController')->only(['index', 'show', 'store', 'update', 'destroy'])
        ->middleware('verify-admin');

    Route::put('posts/{post}/add-to-serie', 'Posts\AddToSerie')->middleware('verify-admin');
    Route::put('posts/{post}/remove-from-serie', 'Posts\RemoveFromSerie')->middleware('verify-admin');
    Route::put('posts/{post}/update-status', 'Posts\UpdateStatus')->middleware('verify-admin');
    Route::resource('posts', 'PostController')->only(['index', 'show', 'store', 'update', 'destroy'])
        ->middleware('verify-admin');

    Route::get('tags/{tag}/get-post', 'Tags\GetPost');
    Route::resource('tags', 'TagController')->only(['store', 'update', 'destroy'])
        ->middleware('verify-admin');

    Route::resource('users', 'UserController')->only(['index', 'update'])
        ->middleware('verify-admin');

    Route::resource('feedbacks', 'FeedbackController')->only(['index', 'update', 'destroy'])
        ->middleware('verify-admin');

    Route::get('comments', 'CommentController@index')->middleware('verify-admin');

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('verify-admin');
});

if (env('APP_ENV') === 'production') {
    Route::get('{any}', 'WebController')->where('any', '.*')->middleware(['authenticate', 'verify-admin']);
}
