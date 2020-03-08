<?php

namespace Sitemap\Model\Behavior;

use Cake\Log\Log;
use Cake\ORM\Behavior;
use Cake\ORM\Query;

/**
 * Class SitemapBehavior
 *
 * @package Sitemap\Model\Behavior
 */
class SitemapBehavior extends Behavior
{

    protected $_defaultConfig = [
        'implementedMethods' => [
        ],
        'implementedFinders' => [
            'sitemap' => 'findSitemap',
        ],
    ];

    /**
     * Auto-wire models
     *
     * @param array $config
     * @throws \Exception
     */
    public function initialize(array $config)
    {
    }

    /**
     * @param Query $query
     * @return Query
     */
    public function findSitemap(Query $query)
    {
        if (!method_exists($this->_table, 'findSitemap')) {
            Log::warning('Table ' . $this->_table->getAlias() . ' has no method findSitemap()');

            return $query;
        }

        return call_user_func([$this->_table, 'findSitemap'], $query);
    }
}
