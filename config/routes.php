<?php
use Cake\Routing\Router;

/**
 * Default sitemap routes
 */
Router::connect('/sitemap.xml', ['plugin' => 'Sitemap', 'controller' => 'Sitemap', 'action' => 'index']);
Router::connect('/sitemap.:index.xml', ['plugin' => 'Sitemap', 'controller' => 'Sitemap', 'action' => 'view']);

/**
 * Default route for controller sitemaps
 */
Router::connect('/:plugin/:controller/sitemap.xml', ['action' => 'sitemap']);
Router::connect('/:controller/sitemap.xml', ['action' => 'sitemap']);