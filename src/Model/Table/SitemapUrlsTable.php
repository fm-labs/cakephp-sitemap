<?php
namespace Sitemap\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Sitemap\Model\Entity\SitemapUrl;

/**
 * SitemapUrls Model
 *
 */
class SitemapUrlsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->table('sitemap_urls');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('scope');

        $validator
            ->requirePresence('model', 'create')
            ->notEmpty('model');

        $validator
            ->add('foreignKey', 'valid', ['rule' => 'numeric'])
            ->requirePresence('foreignKey', 'create')
            ->notEmpty('foreignKey');

        $validator
            ->allowEmpty('loc');

        $validator
            ->allowEmpty('changefreq');

        $validator
            ->add('priority', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('priority');

        $validator
            ->add('lastmod', 'valid', ['rule' => 'datetime'])
            ->allowEmpty('lastmod');

        return $validator;
    }
}
