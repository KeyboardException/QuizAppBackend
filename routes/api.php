<?php

use Blink\Router;
use Controller\Attempt;
use Controller\Auth;
use Controller\Sandbox;

Router::ANY("/hello", [ Sandbox::class, "hello" ]);
Router::ANY("/test", [ Sandbox::class, "test" ]);
Router::ANY("/sync", [ Sandbox::class, "sync" ]);

Router::ANY("/api/session", [ Auth::class, "session" ]);
Router::POST("/api/login", [ Auth::class, "login" ]);
Router::POST("/api/register", [ Auth::class, "register" ]);

Router::POST("/api/attempt/start/{bankId}", [ Attempt::class, "start" ]);
Router::POST("/api/attempt/{attemptId}/answer/{qaId}", [ Attempt::class, "answer" ]);
