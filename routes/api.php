<?php

use Blink\Router;
use Controller\Auth;
use Controller\Sandbox;

Router::ANY("/hello", [ Sandbox::class, "hello" ]);
Router::ANY("/test", [ Sandbox::class, "test" ]);

Router::ANY("/api/session", [ Auth::class, "session" ]);
Router::POST("/api/login", [ Auth::class, "login" ]);
Router::POST("/api/register", [ Auth::class, "register" ]);
