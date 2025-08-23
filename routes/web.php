<?php

use App\Core\App;

App::router()->get('/', 'UserController@index', ['name' => 'user.home']);

App::router()->get('/login', 'Auth\AuthController@login', ['name' => 'auth.login']);
App::router()->post('/login', 'Auth\AuthController@loginPost', ['name' => 'auth.login.post']);

App::router()->get('/register', 'Auth\AuthController@register', ['name' => 'auth.register']);
App::router()->post('/register', 'Auth\AuthController@registerPost', ['name' => 'auth.register.post']);

App::router()->get('/logout', 'Auth\AuthController@logout', ['name' => 'auth.logout']);

App::router()->get('/viewUser/{id}', 'UserController@viewUser', ['name' => 'user.view']);
App::router()->get('/editUser/{id}', 'UserController@editUser', ['name' => 'user.edit']);
App::router()->post('/editUser/{id}', 'UserController@updateUser', ['name' => 'user.update']);
