<?php

use App\Core\App;

App::router()->get('/login', 'Auth\AuthController@login', ['name' => 'auth.login']);
App::router()->get('/register', 'Auth\AuthController@register', ['name' => 'auth.register']);
