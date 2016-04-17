<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 4/17/16
 * Time: 7:44 PM
 */

namespace Sitemap\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class SitemapBehavior extends Behavior
{
    protected $_defaultConfig = [
        'fields' => [
            'sitemap_url' => 'sitemap_url',
            'sitemap_priority' => 'sitemap_priority',
            'sitemap_changefreq' => 'sitemap_changefreq',
            'sitemap_lastmod' => 'sitemap_lastmod',
        ],
        'implementedMethods' => [
            'updateSitemap' => 'updateSitemap',
            'getSitemapUrls' => 'getSitemapUrls'
        ],
        'implementedFinders' => [
            'sitemap' => 'findSitemap'
        ]
    ];


    /**
     * Auto-wire models
     *
     * @param array $config
     */
    public function initialize(array $config)
    {
        $this->_table->hasOne('SitemapUrls', [
            'className' => 'Sitemap.SitemapUrls',
            'conditions' => [
                'model' => $this->_table->alias(),
            ],
            'foreignKey' => 'foreignKey'
        ]);
    }

    public function findSitemap(Query $query, array $options)
    {
        /*
        $for = (isset($options['for'])) ? $options['for'] : null;
        if (!$for) {
            throw new \InvalidArgumentException('The option parameter \'for\' is required');
        }
        */

        return $query->contain('SitemapUrls');
    }

    /**
     * Set the sitemap data for an entity
     *
     * @param EntityInterface $entity
     * @param array $data
     * @return bool|EntityInterface|mixed
     */
    public function updateSitemap(EntityInterface $entity, array $data = [])
    {
        $Sitemaps = $this->_sitemapModel();

        $sitemapUrl = $Sitemaps->find()
            ->where(['model' => $this->_table->alias(), 'foreignKey' => $entity->id])
            ->first();

        if (!$sitemapUrl) {
            $sitemapUrl = $Sitemaps->newEntity(
                [
                    'foreignKey' => $entity->id,
                    'model' => $this->_table->alias()
                ],
                [
                    'validate' => false
                ]
            );
        }

        $sitemapUrl = $Sitemaps->patchEntity($sitemapUrl, $data, ['validate' => false]);
        $success = $Sitemaps->save($sitemapUrl);

        //debug($sitemapUrl->errors());
        return $success;
    }

    public function beforeSave(Event $event, EntityInterface $entity)
    {
        $url = $this->_config['fields']['sitemap_url'];
        $priority = $this->_config['fields']['sitemap_priority'];
        $changefreq = $this->_config['fields']['sitemap_changefreq'];
        $lastmod = $this->_config['fields']['sitemap_lastmod'];

        $sitemapUrl = [
            'loc' => $entity->get($url),
            'priority' => $entity->get($priority),
            'changefreq' => $entity->get($changefreq),
            'last_modified' => $entity->get($lastmod)
        ];

        $this->updateSitemap($entity, $sitemapUrl);

    }

    /**
     * Returns a ResultSet of SitemapUrl entities for the current model
     *
     * @return \Cake\Datasource\ResultSetInterface
     */
    public function getSitemapUrls()
    {
        $Sitemaps = $this->_sitemapModel();
        return $Sitemaps->find()->where(['model' => $this->_table->alias()])->all();
    }

    /**
     * @return \Cake\ORM\Table
     */
    protected function _sitemapModel()
    {
        //return TableRegistry::get('Sitemap.Sitemaps');

        return $this->_table->association('SitemapUrls')->target();
    }
}