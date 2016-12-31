<?php
namespace Sitemap\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Network\Exception\BadRequestException;
use Sitemap\Controller\Component\SitemapComponent;

/**
 * Class SitemapController
 * @package Sitemap\Controller
 *
 * @property SitemapComponent $Sitemap
 */
class SitemapController extends Controller
{
    public function initialize()
    {
        Configure::load('sitemap');
        $this->loadComponent('Sitemap.Sitemap');
    }

    /**
     * Create Sitemap index XML
     */
    public function index()
    {

        $this->Sitemap->createIndex();

        $sitemaps = Configure::read('Sitemap');
        foreach ($sitemaps as $sitemap => $provider) {
            $this->Sitemap->addLocation(['url' => ['action' => 'view', $sitemap]]);
        }
    }

    public function view($sitemap = null)
    {
        if (!$sitemap) {
            throw new BadRequestException();
        }

        $provider = $this->Sitemap->getProvider($sitemap);

        $this->Sitemap->create();
        foreach ($provider->getSitemapLocations() as $location) {
            $location += ['url' => null, 'priority' => null, 'lastmod' => null, 'changefreq' => null];
            $this->Sitemap->addLocation(
                $location['url'],
                $location['priority'],
                $location['lastmod'],
                $location['changefreq']
            );
        }
    }
}