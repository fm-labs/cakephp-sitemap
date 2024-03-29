<?php
declare(strict_types=1);

namespace Sitemap\Controller;

use Cupcake\Exception\ClassNotFoundException;
use Cake\Cache\Cache;
use Cake\Controller\Controller;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;
use Sitemap\Sitemap\SitemapLocation;
use Sitemap\Sitemap\SitemapLocationsCollector;

/**
 * Class SitemapController
 * @package Sitemap\Controller
 *
 * @property \Sitemap\Controller\Component\SitemapComponent $Sitemap
 *
 * @todo Use RequestHandler
 */
class SitemapController extends Controller
{
    protected function _getSitemaps()
    {

        $cacheKey = 'sitemap_locations';
        $sitemaps = Cache::read($cacheKey);

        $this->getEventManager()->on('Sitemap.get', function (Event $event) {
            $event->getSubject()->add(new SitemapLocation('/'));
        });
        //$this->getEventManager()->on(new SitemapListener());

        if (!$sitemaps) {
            $collector = new SitemapLocationsCollector();
            $event = new Event('Sitemap.get', $collector);
            $this->getEventManager()->dispatch($event);

            // providers
            /*
            $providers = (isset($event->result['provider'])) ? $event->result['provider'] : [];
            foreach ($providers as $provider => $className) {

                $Provider = $this->_getSitemapProvider($className);
                $collector->add($Provider->getSitemapLocations(), $provider);
            }
            */

            $sitemaps = $collector->toArray();

            //@TODO Enable caching
            //Cache::write($cacheKey, $sitemaps);
        }

        return $sitemaps;
    }

    /**
     * @param $className
     * @return \Sitemap\Sitemap\SitemapProviderInterface
     */
    protected function _getSitemapProvider($className)
    {

        $class = App::className($className, 'Sitemap', 'SitemapProvider');
        if (!class_exists($class)) {
            throw new ClassNotFoundException(['class' => $class]);
        }

        $Provider = new $class();

        return $Provider;
    }

    /**
     * Sitemap index method
     * Renders a sitemap index xml of available sitemap scopes
     */
    public function index()
    {
        $sitemaps = $this->_getSitemaps();
        $indexUrls = [];
        foreach (array_keys($sitemaps) as $sitemap) {
            $indexUrls[] = ['loc' => Router::url(['action' => 'view', $sitemap])];
        }

        $this->viewBuilder()->setClassName('Sitemap.SitemapXml');

        $this->set('type', 'index');
        $this->set('locations', $indexUrls);
    }

    /**
     * Sitemap view method
     * Renders a list of sitemap locations for given scope in xml format
     *
     * @param null $scope
     * @todo Cache sitemaps for each scope
     */
    public function view($scope = null)
    {
        if (!$scope) {
            throw new BadRequestException();
        }

        $sitemaps = $this->_getSitemaps();
        if (!array_key_exists($scope, $sitemaps)) {
            throw new NotFoundException();
        }

        $this->viewBuilder()->setClassName('Sitemap.SitemapXml');

        $this->set('type', 'sitemap');
        $this->set('locations', $sitemaps[$scope]);
    }

    /**
     * Deprecated initialize method
     */
    public function _initialize()
    {
        Configure::load('sitemap');
        $this->loadComponent('Sitemap.Sitemap');
    }

    /**
     * Deprecated index method
     * Create Sitemap index XML
     * @deprecated
     */
    public function _index()
    {
        $this->Sitemap->createIndex();

        $sitemaps = Configure::read('Sitemap');
        foreach ($sitemaps as $sitemap => $provider) {
            $this->Sitemap->addLocation(['url' => ['action' => 'view', $sitemap]]);
        }
    }

    /**
     * Deprecated view method
     * @param null $sitemap
     * @deprecated
     */
    public function _view($sitemap = null)
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
