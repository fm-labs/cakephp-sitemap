<?php
namespace Sitemap\Controller\Component;

use Cake\Controller\Component;
use Cake\Routing\Router;
use Cake\Core\Exception\Exception;

class SitemapComponent extends Component
{
    const TYPE_INDEX = 1;

    const TYPE_SITEMAP = 2;

    public $locations = [];

    public $cache = '+1 day';

    protected $_type;

    public function startSitemap()
    {
        $this->_type = self::TYPE_SITEMAP;
    }

    public function startSitemapIndex()
    {
        $this->_type = self::TYPE_INDEX;
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

    public function render()
    {
        $controller = $this->_registry->getController();
        $view = null;

        switch ($this->_type) {
            case self::TYPE_INDEX:
                $view = 'Sitemap.index';
                break;

            case self::TYPE_SITEMAP:
            default:
                $view = 'Sitemap.sitemap';
                break;
        }

        $controller->set('locations', $this->locations);

        $controller->autoRender = false;
        $controller->autoLayout = false;
        $controller->viewClass = 'Sitemap.SitemapXml';
        //@TODO Set response parameters from SitemapXml view
        $controller->response->type('application/xml');
        $controller->response->cache(mktime(0, 0, 0, date('m'), date('d'), date('Y')), $this->cache);
        $controller->render($view);
    }

}