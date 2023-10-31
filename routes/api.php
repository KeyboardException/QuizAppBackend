<?php

use Blink\Router;
use Controller\Attempt;
use Controller\Auth;
use Controller\QuestionBank;
use Controller\Sandbox;
use Controller\User;

Router::ANY("/hello", [ Sandbox::class, "hello" ]);
Router::ANY("/test", [ Sandbox::class, "test" ]);
Router::ANY("/sync", [ Sandbox::class, "sync" ]);

Router::ANY("/api/session", [ Auth::class, "session" ]);
Router::POST("/api/login", [ Auth::class, "login" ]);
Router::POST("/api/register", [ Auth::class, "register" ]);

Router::GET("/api/banks", [ QuestionBank::class, "list" ]);
Router::GET("/api/attempts", [ Attempt::class, "list" ]);
Router::GET("/api/user/{id}", [ User::class, "get" ]);
Router::GET("/api/ranking", [ User::class, "ranking" ]);
Router::POST("/api/attempt/start/{bankId}", [ Attempt::class, "start" ]);
Router::POST("/api/attempt/complete/{attemptId}", [ Attempt::class, "complete" ]);
Router::GET("/api/attempt/info/{attemptId}", [ Attempt::class, "info" ]);
Router::POST("/api/attempt/{attemptId}/answer/{qaId}", [ Attempt::class, "answer" ]);
