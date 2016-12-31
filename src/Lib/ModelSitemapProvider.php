<?php

namespace Sitemap\Lib;


use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use Cake\ORM\ResultSet;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

abstract class ModelSitemapProvider implements SitemapProviderInterface
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

        try {
            $this->_table = TableRegistry::get($this->modelClass);

            // If the SitemapProvider behavior is not already attached (e.g. defined in model class)
            // try to attach it. Will throw an exception if initalization fails
            //if (!$Model->behaviors()->has('SitemapProvider')) {
            //    $Model->behaviors()->load('Sitemap.SitemapProvider');
            //}
            //if ($Model->behaviors()->has('SitemapProvider')) {
            //    $query = $Model->find('sitemap');
            //} else {
                $query = $this->_table->find();
            //}

            $query = $this->find($query);
            return $this->compile($query->all());

        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param Query $query
     * @return mixed
     */
    abstract public function find(Query $query);

    /**
     * @param ResultSet $result
     * @return array
     */
    abstract public function compile(ResultSetInterface $result);
}