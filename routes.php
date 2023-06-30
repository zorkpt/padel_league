<?php
use Pecee\SimpleRouter\SimpleRouter;


\Pecee\SimpleRouter\SimpleRouter::setDefaultNamespace('App');


SimpleRouter::group(['exceptionHandler' => \Demo\Handlers\CustomExceptionHandler::class], function () {

    SimpleRouter::get('/', 'DefaultController@home')->name('home');
    SimpleRouter::get('/contact', 'DefaultController@contact')->name('contact');
    SimpleRouter::basic('/companies/{id?}', 'DefaultController@companies')->name('companies');

    // API

    SimpleRouter::group(['prefix' => '/api', 'middleware' => \Demo\Middlewares\ApiVerification::class], function () {
        SimpleRouter::resource('/demo', 'ApiController');
    });

    // CALLBACK EXAMPLES

    SimpleRouter::get('/foo', function() {
        return 'foo';
    });

    SimpleRouter::get('/foo-bar', function() {
        return 'foo-bar';
    });

});

//
//
//
//SimpleRouter::get('/', 'HomeController@index');
//
//SimpleRouter::get('/register', 'UserController@register');
//SimpleRouter::get('/login', 'UserController@login');
//SimpleRouter::get('/logout', 'UserController@logout');
//SimpleRouter::get('/user/updatePassword', 'UserController@changePassword');
//SimpleRouter::get('/user/updateEmail', 'UserController@changeEmail');
//SimpleRouter::get('/user/updateAvatar', 'UserController@updateAvatar');
//SimpleRouter::get('/dashboard', 'UserController@dashboard');
//SimpleRouter::get('/leagues/create', 'LeagueController@create');
//SimpleRouter::get('/league', 'LeagueController@viewLeague');
//SimpleRouter::get('/league/join', 'LeagueController@joinLeague');
//SimpleRouter::get('/game_schedule', 'GameController@schedule');
//SimpleRouter::get('/game', 'GameController@show');
//SimpleRouter::get('/game/create', 'GameController@addGame');
//SimpleRouter::get('/game/subscribe', 'GameController@subscribe');
//SimpleRouter::get('/unsubscribe', 'GameController@unsubscribe');
//SimpleRouter::get('/game/lock', 'GameController@handleLockGame');
//SimpleRouter::get('/game/register_results', 'GameController@registerResults');
//SimpleRouter::get('/game/submit_results', 'GameController@submitResults');
//SimpleRouter::get('/game/finish', 'GameController@finishGame');
//SimpleRouter::get('/settings', 'UserController@settings');
//SimpleRouter::get('/profile', 'UserController@profile');
//

// ... Continue definindo todas as suas rotas ...


SimpleRouter::start();
