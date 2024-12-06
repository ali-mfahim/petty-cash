<?php

return [
    "title" => env("APP_NAME", ""),
    "emails" => [
        "admin" => env("ADMIN_EMAIL", "sm.ali10@yahoo.com"),
    ],

    "logo" => "assets/logo/",

    "upload_path" => [

        "project_logo" => "/uploads/logo/",
        "project_logo_thumb" => "/uploads/logo/thumbnails/",



        "project_logo_black" => "/uploads/logoblack/",
        "project_logo_black_thumb" => "/uploads/logoblack/thumbnails/",


        "project_fav_icon" => "/uploads/favIcon/",
        "project_fav_icon_thumb" => "/uploads/favIcon/thumbnails/",
    ],
];
