# Sitemap plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require fm-labs/cakephp-sitemap
```


## Usage

Enable Sitemap plugin with default sitemap routes in your config/bootstrap.php

```
Plugin::load('Sitemap', ['routes' => true]);
```

## Configuration

Create config/sitemap.php with a mapping of Sitemap providers

```
return [
    'Sitemap' => [
        'posts' => '\\App\\Sitemap\\MyPostsSitemapProvider'  
    ]
]
```

## Sitemap routes

- /sitemap.xml -> SitemapController::index()
- /sitemap_:sitemap.xml -> SitemapController::view($sitemap)
- /sitemap_:sitemap-:page.xml -> SitemapController::view($sitemap, $page)

## Create sitemap providers

Create a class implementing the Sitemap\\Lib\\SitemapProviderInterface

## Create sitemap providers for models

Create a class extending the Sitemap\\Lib\\ModelSitemapProvider

- Set $modelClass
- Implement abstract find() method to find model records
- Implement abstract compile() method to create/filter sitemap locations from result set


## Customized SitemapController

- copy plugin's SitemapController to your app's controller dir
- disable Sitemap plugin routes
- create your own sitemap routes pointing to your sitemap controller
- may use SitemapComponent to create sitemaps
- may use SitemapXmlView to render sitemaps