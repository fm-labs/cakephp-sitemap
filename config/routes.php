<?php
use Cake\Routing\Router;

// Sitemap index route
Router::connect('/sitemap.xml',
    ['plugin' => 'Sitemap', 'controller' => 'Sitemap', 'action' => 'index']);

// Sitemap view routes
Router::connect('/sitemap_:sitemap-:page.xml',
    ['plugin' => 'Sitemap', 'controller' => 'Sitemap', 'action' => 'view'],
    ['pass' => ['sitemap', 'page']]
);
Router::connect('/sitemap_:sitemap.xml',
    ['plugin' => 'Sitemap', 'controller' => 'Sitemap', 'action' => 'view'],
    ['pass' => ['sitemap']]
);
