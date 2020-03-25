<?php
namespace Sitemap\View;

use Cake\View\View;

class SitemapXmlView extends View
{
    public function initialize()
    {
        $this->response = $this->response
            ->withType('application/xml')
            ->withCache(mktime(0, 0, 0, date('m'), date('d'), date('Y')), '+1 day');
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
