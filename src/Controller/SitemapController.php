<?php
namespace Sitemap\Controller;

class SitemapController extends AppController
{
    public function initialize()
    {
        $this->loadComponent('Sitemap.Sitemap');

        //$this->Auth->allow();
    }
    /**
     * Create Sitemap index XML
     */
    public function index()
    {
        $this->Sitemap->createIndex();
        $this->Sitemap->addLocation(['action' => 'pages']);
        $this->Sitemap->addLocation(['action' => 'categories']);
        $this->Sitemap->addLocation(['action' => 'products_de']);
    }

    public function pages()
    {
        $this->loadModel('Banana.Pages');
        $pages = $this->Pages
            ->find('published')
            ->contain([]);

        $this->Sitemap->create();
        foreach ($pages as $page) {

            if ($page->hide_in_sitemap) {
                continue;
            }

            $this->Sitemap->addLocation(
                $page->url,
                0.8, // priority
                null, // last modified date
                'monthly' // change frequency
            );
        }

    }

    /**
     * Create Sitemap XML from model
     */
    public function categories()
    {
        $this->Sitemap->create();

        $this->loadModel('Shop.ShopCategories');
        foreach ($this->ShopCategories->find('published')->contain([]) as $shopCategory) {
            $this->Sitemap->addLocation(
                $shopCategory->url,
                1, // priority
                null, // last modified date
                'monthly' // change frequency
            );
        }
    }

    public function products_de()
    {
        $this->Sitemap->create();

        $this->loadModel('Shop.ShopProducts');
        $this->ShopProducts->locale('de');
        $this->ShopProducts->ShopCategories->locale('de');

        $query = $this->ShopProducts->find('published')->find('translations')->contain(['ShopCategories'  => function ($query) {
            return $query->find('translations');
        }]);

        foreach ($query as $shopProduct) {
            $this->Sitemap->addLocation(
                $shopProduct->url,
                0.9, // priority
                null, // last modified date
                'monthly' // change frequency
            );
        }
    }

}