<?php
namespace Sitemap\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\Controller;
use Cake\Routing\Router;
use Cake\Core\Exception\Exception;

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
        $this->controller = $this->_registry->getController();

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
        $this->controller->viewClass = 'Sitemap.SitemapXml';
        $this->controller->viewBuilder()->autoLayout(false);

        //@TODO Set response parameters from SitemapXml view
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

    public function addLocations(array $locations)
    {
        foreach ($locations as $loc) {
            $this->addLocation($loc);
        }
        return $this;
    }

    public function addLocation($loc, $priority = 0.5, $lastmod = null, $changefreq = null)
    {
        if (is_array($loc) && isset($loc['loc'])) {
            extract($loc, EXTR_IF_EXISTS);
        }

        // build full location url
        $loc = Router::url($loc, true);

        // validate priority
        $priority = (is_numeric($priority) && $priority >= 0 && $priority <= 1) ? $priority : 0.5;

        // w3c time format
        // @link http://www.w3.org/TR/NOTE-datetime
        //$lastmod = (is_numeric($lastmod)) ? $lastmod : strtotime($lastmod);
        $lastmod = ($lastmod) ? date(DATE_W3C, strtotime($lastmod)) : null;

        // validate changefreq
        $changefreq = (in_array($changefreq, array('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'))) ? $changefreq : null;

        array_push($this->locations, compact('loc', 'lastmod', 'changefreq', 'priority'));

        return $this;
    }

}