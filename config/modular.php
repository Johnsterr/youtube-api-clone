<?php

return [
    "path" => base_path() . "/app/Modules",
    "base_namespace" => "App\Modules",
    "groupWithoutPrefix" => "Public",
    "groupMiddleware" => [
        "Admin" => [
            "web" => ["auth"],
            "api" => ["auth.api"],
        ],
    ],
    "modules" => [
        "Admin" => [
            "User",
        ],
        "Public" => [
            "Auth",
        ],
    ],
];
