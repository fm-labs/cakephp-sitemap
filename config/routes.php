<?php
use Cake\Routing\Router;

/**
 * Default sitemap routes
 */
Router::connect('/sitemap.:action_:page.xml', ['controller' => 'Sitemap'], ['pass' => ['page']]);
Router::connect('/sitemap.:action.xml', ['controller' => 'Sitemap']);
Router::connect('/sitemap.xml', ['controller' => 'Sitemap', 'action' => 'index']);

