<?php

use App\Core\App;

App::router()->get('/', 'HomeController@index', ['name' => 'home']);

App::router()->get('/login', 'Auth\AuthController@login', ['name' => 'auth.login']);
App::router()->post('/login', 'Auth\AuthController@loginPost', ['name' => 'auth.login.post']);

App::router()->get('/register', 'Auth\AuthController@register', ['name' => 'auth.register']);
App::router()->post('/register', 'Auth\AuthController@registerPost', ['name' => 'auth.register.post']);

App::router()->get('/logout', 'Auth\AuthController@logout', ['name' => 'auth.logout']);
