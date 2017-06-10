<?php
namespace Sitemap\View;

use Cake\Network\Exception\NotFoundException;
use Cake\View\View;
use Sitemap\Controller\Component\SitemapComponent;

class SitemapXmlView extends View
{
    public function initialize()
    {
        $cacheDuration = '+1 day';

        $this->response->type('application/xml');
        $this->response->cache(mktime(0, 0, 0, date('m'), date('d'), date('Y')), $cacheDuration);
    }

    public function render($view = null, $layout = null)
    {
        $this->viewPath = 'Sitemap';
        $this->subDir = 'xml';

        if ($view === null) {
            $type = $this->get('type');
            if (!$type) {
                throw new \LogicException('Sitemap: Unknown view type');
            }
            $view = 'Sitemap.' . $type;
        }

        return parent::render($view, false);
    }
}
