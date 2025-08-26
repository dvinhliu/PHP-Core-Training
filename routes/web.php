<?php

use App\Core\App;
use App\Middleware\AuthMiddleware;

App::router()->get('/', 'UserController@index', ['name' => 'user.home', 'middleware' => [fn() => AuthMiddleware::check(['view_users'])]]);

App::router()->get('/login', 'Auth\AuthController@login', ['name' => 'auth.login']);
App::router()->post('/login', 'Auth\AuthController@loginPost', ['name' => 'auth.login.post']);
App::router()->get('/register', 'Auth\AuthController@register', ['name' => 'auth.register', 'middleware' => [fn() => AuthMiddleware::check(['register'])]]);
App::router()->post('/register', 'Auth\AuthController@registerPost', ['name' => 'auth.register.post', 'middleware' => [fn() => AuthMiddleware::check(['register'])]]);

App::router()->get('/logout', 'Auth\AuthController@logout', ['name' => 'auth.logout', 'middleware' => [fn() => AuthMiddleware::check()]]);

App::router()->get('/viewUser/{id}', 'UserController@viewUser', ['name' => 'user.view', 'middleware' => [fn() => AuthMiddleware::check(['view_any_user', 'view_self'])]]);

App::router()->get('/editUser/{id}', 'UserController@editUser', ['name' => 'user.edit', 'middleware' => [fn() => AuthMiddleware::check(['view_any_user', 'view_self'])]]);
App::router()->post('/editUser', 'UserController@editUserPost', ['name' => 'user.edit.post', 'middleware' => [fn() => AuthMiddleware::check(['update_any_user', 'update_self'])]]);

App::router()->post('/deleteUser/{id}', 'UserController@deleteUser', ['name' => 'user.delete', 'middleware' => [fn() => AuthMiddleware::check(['delete_any_user', 'delete_self'])]]);

App::router()->get('/404', 'UserController@page404', ['name' => 'page.404']);

App::router()->get('/forgot-password', 'Auth\AuthController@forgotPassword', ['name' => 'auth.forgot.password']);
App::router()->post('/forgot-password', 'Auth\AuthController@forgotPasswordPost', ['name' => 'auth.forgot.password.post']);

App::router()->get('/verify', 'Auth\AuthController@verify', ['name' => 'auth.verify']);
App::router()->post('/verify', 'Auth\AuthController@verifyPost', ['name' => 'auth.verify.post']);

App::router()->get('/reset-password', 'Auth\AuthController@resetPassword', ['name' => 'auth.reset']);
App::router()->post('/reset-password', 'Auth\AuthController@resetPasswordPost', ['name' => 'auth.reset.post']);
