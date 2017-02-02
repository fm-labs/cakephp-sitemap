<?php

namespace Sitemap\Sitemap;


use Cake\Collection\Collection;
use Cake\Datasource\ResultSetInterface;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\ResultSet;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

abstract class AbstractTableSitemapProvider implements SitemapProviderInterface
{
    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var Table
     */
    protected $_table;

    public function getSitemapLocations()
    {
        if (!$this->modelClass) {
            throw new \Exception('ModelSitemapProvider: Missing model class name');
        }

        $locations = [];

        try {
            $this->_table = TableRegistry::get($this->modelClass);

            $query = $this->_table->find();
            $query = $this->find($query);

            $result = $query->all();
            $this->compile($result, $locations);

        } catch (\Exception $ex) {
            Log::critical(sprintf('Sitemap: Error fetching sitemap locations for model \'%s\': %s',
                $this->modelClass,
                $ex->getMessage()));
        }

        return $locations;
    }

    /**
     * Find entities
     *
     * @param Query $query
     * @return Query
     */
    abstract public function find(Query $query);

    /**
     * @param ResultSetInterface|ResultSet $result
     * @param array $locations
     */
    abstract public function compile(ResultSetInterface $result, array &$locations);
}