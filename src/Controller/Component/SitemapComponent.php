<?php
namespace Sitemap\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Core\Exception\Exception;
use Sitemap\Exception\InvalidSitemapProviderException;
use Sitemap\Exception\MissingSitemapProviderClassException;
use Sitemap\Sitemap\SitemapProviderInterface;

/**
 * Class SitemapComponent
 * @package Sitemap\Controller\Component
 *
 * @deprecated Use SitemapXmlView directly for rendering.
 *     For sitemap location discovery use event system and/or sitemap providers.
 */
class SitemapComponent extends Component
{
    const TYPE_INDEX = 'index';
    const TYPE_SITEMAP = 'sitemap';

    public $locations = [];

    public $cache = '+1 day';

    /**
     * @var Controller
     */
    public $controller;

    protected $_type;

    public function initialize(array $config)
    {
        $this->controller = $this->getController();

        //if ($this->_registry->has('Auth')) {
        //    $this->Auth->allow();
        //}
    }

    public function beforeRender()
    {
        $this->controller->set('type', $this->_type);
        $this->controller->set('locations', $this->locations);
    }

    protected function _createSitemap($type)
    {
        $this->_type = $type;

        //$this->controller->autoRender = false;
        $this->controller->viewBuilder()->setClassName('Sitemap.SitemapXml');
        $this->controller->viewBuilder()->autoLayout(false);

        //@TODO Set response parameters in SitemapXml view
        $this->controller->response->type('application/xml');
        $this->controller->response->cache(mktime(0, 0, 0, date('m'), date('d'), date('Y')), $this->cache);
    }

    public function create()
    {
        $this->_createSitemap(self::TYPE_SITEMAP);
    }

    public function createIndex()
    {
        $this->_createSitemap(self::TYPE_INDEX);
    }

    public function addLocations(array $urlations)
    {
        foreach ($urlations as $url) {
            $this->addLocation($url);
        }

        return $this;
    }

    public function addLocation($url, $priority = 0.5, $lastmod = null, $changefreq = null)
    {
        if (is_array($url) && isset($url['url'])) {
            extract($url, EXTR_IF_EXISTS);
        }

        // build full location url
        $url = Router::url($url, true);

        // validate priority
        $priority = (is_numeric($priority) && $priority >= 0 && $priority <= 1) ? $priority : 0.5;
        $priority = ($priority == 0 || $priority == 1) ? number_format($priority, 0) : number_format($priority, 1);

        // w3c time format
        // @link http://www.w3.org/TR/NOTE-datetime
        //$lastmod = (is_numeric($lastmod)) ? $lastmod : strtotime($lastmod);
        $lastmod = ($lastmod) ? date(DATE_W3C, strtotime($lastmod)) : null;

        // validate changefreq
        $changefreq = (in_array($changefreq, ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'])) ? $changefreq : null;

        array_push($this->locations, ['loc' => $url, 'priority' => $priority, 'lastmod' => $lastmod, 'changefreq' => $changefreq]);

        return $this;
    }

    /**
     * @param $sitemap
     * @return SitemapProviderInterface
     * @deprecated
     */
    public function getProvider($sitemap)
    {
        $providerClass = Configure::read('Sitemap.' . $sitemap);
        if (!class_exists($providerClass)) {
            throw new MissingSitemapProviderClassException(['class' => $providerClass]);
        }
        $provider = new $providerClass();

        if (!($provider instanceof SitemapProviderInterface)) {
            throw new InvalidSitemapProviderException(['class' => $providerClass]);
        }

        return $provider;
    }
}
