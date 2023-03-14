<?php
/** @var \Cake\Routing\RouteBuilder $routes */

// Sitemap index route
$routes->connect(
    '/sitemap.xml',
    ['plugin' => 'Sitemap', 'controller' => 'Sitemap', 'action' => 'index'],
    ['_name' => 'sitemap:index']
);

// Sitemap view routes
$routes->connect(
    '/sitemap-:sitemap-:page.xml',
    ['plugin' => 'Sitemap', 'controller' => 'Sitemap', 'action' => 'view'],
    ['pass' => ['sitemap', 'page']]
);
$routes->connect(
    '/sitemap-:sitemap.xml',
    ['plugin' => 'Sitemap', 'controller' => 'Sitemap', 'action' => 'view'],
    ['pass' => ['sitemap']]
);
