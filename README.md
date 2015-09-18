# Sitemap plugin for CakePHP

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require fm-labs/cakephp3-sitemap
```


## Usage

Enable Sitemap plugin with default sitemap routes in your config/bootstrap.php

```
Plugin::load('Sitemap', ['routes' => true]);
```

Create a SitemapController, like this:


```
namespace App\Controller;

class SitemapController extends AppController
{
    public function initialize()
    {
        $this->loadComponent('Sitemap.Sitemap');

        //$this->Auth->allow(['index', 'procucts']);
    }

    /**
     * Create Sitemap index XML
     */
    public function index()
    {
        $this->Sitemap->createIndex();

        $this->Sitemap->addLocation(['action' => 'products']);
    }

    /**
     * Create Sitemap XML from model
     */
    public function products()
    {
        $this->Sitemap->create();

        $this->loadModel('Shop.ShopProducts');
        foreach ($this->ShopProducts->find('list') as $id => $title) {
            $this->Sitemap->addLocation(
                ['plugin' => null, 'controller' => 'Products', 'action' => 'view', $id], // url
                0.5, // priority
                null, // last modified date
                'monthly' // change frequency
            );
        }
    }
}
```


## Default Routes


The default routes are:

/sitemap.[:action]_[:page].xml -> ['controller' => 'Sitemap', 'action' => [:action], 'page' => [:page]]
/sitemap.[:action].xml -> ['controller' => 'Sitemap', 'action' => [:action]]
/sitemap.xml -> ['controller' => 'Sitemap', 'action' => 'index']


Enable default routes when loading the plugin in your bootstrap.php file:
```
Plugin::load('Sitemap', ['routes' => true]);
```