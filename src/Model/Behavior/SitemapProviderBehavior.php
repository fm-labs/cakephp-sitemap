<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 12/31/16
 * Time: 6:19 PM
 */

namespace Sitemap\Model\Behavior;


use Cake\ORM\Behavior;
use Cake\ORM\Query;

/**
 * Class SitemapProviderBehavior
 *
 * !!! Work in Progress !!!
 * !!! Use ModelSitemapProvider instead !!!
 *
 * @package Sitemap\Model\Behavior
 */
class SitemapProviderBehavior extends Behavior
{

    protected $_defaultConfig = [
        'implementedMethods' => [
        ],
        'implementedFinders' => [
            'sitemap' => 'findSitemap'
        ]
    ];


    /**
     * Auto-wire models
     *
     * @param array $config
     * @throws \Exception
     */
    public function initialize(array $config)
    {
        if (!method_exists($this->_table, 'findSitemap')) {
            throw new \Exception('Table ' . $this->_table->alias() . ' has no method findSitemap()');
        }
    }

    /**
     * @param Query $query
     * @return Query
     */
    public function findSitemap(Query $query)
    {
        return call_user_func([$this->_table, 'findSitemap'], $query);
    }
}