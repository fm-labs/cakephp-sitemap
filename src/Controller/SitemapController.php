<?php
namespace Sitemap\Controller;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Event\Event;
use Cake\Network\Exception\NotFoundException;
use Sitemap\Controller\Component\SitemapComponent;
use Cake\ORM\Table;

/**
 * Class SitemapController
 * @package Sitemap\Controller
 *
 * @property SitemapComponent $Sitemap
 * @property Table $Model
 */
class SitemapController extends AppController
{
    public $modelClass = false;

    /**
     * @param Event $event
     */
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->loadComponent('Sitemap.Sitemap');

        // Disable authentication for sitemap index
        if ($this->components()->has('Auth')) {
            $this->Auth->allow('index', 'view');
        }

        $action = $this->request->params['action'];
        //if ($action != 'index') {
        //    $this->request->params['action'] = 'view';
        //    $this->request->params['index'] = $action;
        //}
    }

    public function index()
    {
        $this->Sitemap->startSitemapIndex();

        $sitemaps = (array)Configure::read('Sitemap');
        foreach ($sitemaps as $index => $sitemap) {
            $this->Sitemap->addLocation(['controller' => 'Sitemap', 'action' => 'view', 'index' => $index]);
        }

        $this->Sitemap->render();
    }

    public function view($index = null)
    {
        $sitemaps = (array)Configure::read('Sitemap');
        $index = ($index) ?: $this->request->param('index');
        $sitemap = ($index && isset($sitemaps[$index])) ? $sitemaps[$index] : null;

        if (!$sitemap) {
            throw new NotFoundException(sprintf('Sitemap %s not found', $index));
        }

        $this->Sitemap->startSitemap();

        switch ($sitemap['source']) {
            case 'table':
                $tables = (isset($sitemap['tables'])) ? (array)$sitemap['tables'] : [];
                foreach ($tables as $table) {
                    $this->Model = $this->loadModel($table);
                    if (!$this->Model->behaviors()->has('Sitemap')) {
                        //throw new Exception(sprintf("Model %s does not have the Sitemap Behaviour attached", $table));
                        continue;
                    }

                    $_sitemap = $this->Model->getSitemap();
                    $this->Sitemap->addLocations($_sitemap);
                }
                break;

            case 'list':
                $this->Sitemap->addLocations($sitemap['locations']);
                break;

            case 'controller':
                list($plugin, $controller) = pluginSplit($sitemap['controller']);
                $plugin = ($plugin) ?: false;
                //$this->redirect(['plugin' => $plugin, 'controller' => $controller, 'action' => 'sitemap']);
                $_sitemap = $this->requestAction(['plugin' => $plugin, 'controller' => $controller, 'action' => 'sitemap']);
                $this->Sitemap->addLocations($_sitemap);
                break;
        }

        $this->Sitemap->render();
    }

}
