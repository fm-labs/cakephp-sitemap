<?php

namespace Sitemap\Sitemap;


use Cake\Collection\Collection;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\ResultSet;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

abstract class AbstractTableSitemapProvider implements SitemapProviderInterface, EventListenerInterface
{
    /**
     * @var string Model classname
     */
    public $modelClass;

    /**
     * @var string Sitemap alias
     */
    public $name = 'default';

    /**
     * @var Table
     */
    protected $_table;

    protected $_locations = [];

    public function implementedEvents()
    {
        return [
            'Sitemap.get' => 'getSitemap',
        ];
    }

    public function getSitemap(Event $event)
    {
        $event->subject()->add($this->getSitemapLocations(), $this->name);
    }

    public function getSitemapLocations()
    {
        if (!$this->modelClass) {
            throw new \Exception('ModelSitemapProvider: Missing model class name');
        }

        try {
            $this->_table = TableRegistry::get($this->modelClass);

            $query = $this->_table->find();
            $query = $this->find($query);

            $result = $query->all();
            $this->compile($result);

        } catch (\Exception $ex) {
            Log::critical(sprintf('Sitemap: Error fetching sitemap locations for model \'%s\': %s',
                $this->modelClass,
                $ex->getMessage()));
        }

        return $this->_locations;
    }

    protected function _addLocation(SitemapLocation $loc)
    {
        $this->_locations[] = $loc;
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
     * @return
     * @internal param array $locations
     */
    abstract public function compile(ResultSetInterface $result);
}