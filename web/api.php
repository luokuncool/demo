<?php

$container = require __DIR__ . '/../bootstrap.php';

$clientStorage  = $container['client_storage'];
$codeStorage    = new \Blog\OAuth2Storage\AuthorizationCodeStorage();
$refreshStorage = new \Blog\OAuth2Storage\RefreshTokenStorage();

$server = new \OAuth2\Server(
    [
        'client_credentials' => $clientStorage,
        'user_credentials'   => new \Blog\OAuth2Storage\UserStorage(),
        'access_token'       => new \Blog\OAuth2Storage\AccessTokenStorage(),
        'authorization_code' => $codeStorage,
        'refresh_token'      => $refreshStorage,
    ], [
    'auth_code_lifetime'     => 30,
    'refresh_token_lifetime' => 30,
]
);

$server->addGrantType(new OAuth2\GrantType\ClientCredentials($clientStorage));
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($codeStorage));
$server->addGrantType(new OAuth2\GrantType\RefreshToken($refreshStorage));

$server->addGrantType(new \OAuth2\GrantType\AuthorizationCode($codeStorage));
$server->addGrantType(
    new \OAuth2\GrantType\RefreshToken(
        $refreshStorage, [
        // the refresh token grant request will have a "refresh_token" field
        // with a new refresh token on each request
        'always_issue_new_refresh_token' => true,
    ]
    )
);

// handle the request
$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();