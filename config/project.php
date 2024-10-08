<?php

return [
    "title" => env("APP_NAME", ""),
    "emails" => [
        "admin" => env("ADMIN_EMAIL", "sm.ali10@yahoo.com"),
    ],
    "shopify" => [
        "access_token" => env("SHOPIFY_ACCESS_TOKEN", ""),
        "app_key" => env("SHOPIFY_APP_KEY", ""),
        "app_secret" => env("SHOPIFY_APP_SECRET", ""),
        "domain" => env("SHOPIFY_DOMAIN", ""),
        "base_url" => env("SHOPIFY_BASE_URL", ""),
        "app_version" => env("SHOPIFY_API_VERSION", ""),
        "store_currency" => env("STORE_CURRENCY", "AED"),
        "location_id" => env("LOCATION_ID", ""),
        "meta" => [
            "namespace" => env("DEFINATION_NAMESPACE", ""),
            "key" => env("DEFINATION_KEY", ""),
        ],


    ],

    "logo" => "assets/logo/",
    "currency" => [
        "base_url" => "https://openexchangerates.org/api/",
        "token" => env("CURRENCY_TOKEN", "40f99d19403742058794063fdb122ca9"),
    ],
];
