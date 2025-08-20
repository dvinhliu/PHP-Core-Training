<?php

$router->get('/login', 'Auth\AuthController@login', ['name' => 'auth.login']);
