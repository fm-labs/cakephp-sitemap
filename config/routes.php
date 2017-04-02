<?php
use Cake\Routing\Router;

// Sitemap index route
Router::connect('/sitemap.xml',
    ['plugin' => 'Sitemap', 'controller' => 'Sitemap', 'action' => 'index'],
    ['_name' => 'sitemap:index']);

// Sitemap view routes
Router::connect('/sitemap-:sitemap-:page.xml',
    ['plugin' => 'Sitemap', 'controller' => 'Sitemap', 'action' => 'view'],
    ['pass' => ['sitemap', 'page']]
);
Router::connect('/sitemap-:sitemap.xml',
    ['plugin' => 'Sitemap', 'controller' => 'Sitemap', 'action' => 'view'],
    ['pass' => ['sitemap']]
);
