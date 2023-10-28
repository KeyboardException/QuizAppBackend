<?php

use Blink\Router;
use Controller\Auth;
use Controller\Sandbox;

Router::ANY("/hello", [ Sandbox::class, "hello" ]);
Router::ANY("/test", [ Sandbox::class, "test" ]);

Router::ANY("/api/login", [ Auth::class, "login" ]);
