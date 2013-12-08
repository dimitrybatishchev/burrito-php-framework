<?php

$routes = array(
    'user_login' => array(
        'url' => '/login',
        'controller' => 'Security::User',
        'action' => 'login'
    ),
    'user_registration' => array(
        'url' => '/registration',
        'controller' => 'Security::User',
        'action' => 'registration'
    ),
    'list' => array(
        'url' => '/posts/$',
        'controller' => 'Blog::Page',
        'action' => 'list'
    ),
    'detail' => array(
        'url' => '/posts/([0-9]+)/$',
        'controller' => 'Blog::Page',
        'action' => 'detail'
    ),
    'edit' => array(
        'url' => '/posts/edit/([0-9]+)/$',
        'controller' => 'Blog::Page',
        'action' => 'edit'
    ),
    'create' => array(
        'url' => '/posts/create/$',
        'controller' => 'Blog::Page',
        'action' => 'create'
    ),
    'main' => array(
        'url' => '^/$',
        'controller' => 'Blog::Page',
        'action' => 'mainPage'
    )
);

return $routes;