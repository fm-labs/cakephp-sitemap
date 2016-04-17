<?php

namespace Sitemap\Test\TestCase\Model\Behavior;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use DebugKit\Database\Log\DebugLog;

/**
 * @property \Cake\ORM\Table $table
 */
class SitemapBehaviorTest extends TestCase
{

    public $fixtures = [
        'plugin.banana.pages',
        'plugin.sitemap.sitemap_urls'
    ];

    /**
     * @var DebugLog
     */
    public $dbLogger;

    public function setUp()
    {
        parent::setUp();
        $this->table = TableRegistry::get('Banana.Pages');
        $this->table->primaryKey(['id']);
        $this->table->addBehavior('Sitemap.Sitemap');
        //$this->table->addBehavior('Tree.Tree');
        //debug($this->table->find('treeList')->toArray());

        $this->_setupDbLogging();
    }


    /**
     * Initialize hook - configures logger.
     *
     * This will unfortunately build all the connections, but they
     * won't connect until used.
     *
     * @return array
     */
    protected function _setupDbLogging()
    {

        $connection = ConnectionManager::get('test');

        $logger = $connection->logger();
        $this->dbLogger = new DebugLog($logger, 'test');

        $connection->logQueries(true);
        $connection->logger($this->dbLogger);
    }

    public function tearDown()
    {
        parent::tearDown();
        TableRegistry::clear();
    }

    public function testFindSitemap()
    {
        $entity = $this->table
            ->find('sitemap')
            ->where([ $this->table->alias() . '.id' => 1])
            ->first();

        $this->assertInstanceOf('Sitemap\Model\Entity\SitemapUrl', $entity->sitemap_url);
    }

    public function testUpdateSitemap()
    {

        //debug($this->table->association('SitemapUrls')->find()->all()->toArray());

        $data = [
            'loc' => 'http://localhost/test',
            'changefreq' => 'daily',
            'priority' => 9,
            'lastmod' => null
        ];

        $updated = $this->table->updateSitemap($this->table->get(1), $data);
        //debug($updated);

        $SitemapUrls = TableRegistry::get('Sitemap.SitemapUrls');
        $sitemap = $SitemapUrls->find()
            ->where(['model' => $this->table->alias(), 'foreignKey' => 1])
            ->first();

        $result = $sitemap->extract(array_keys($data));
        $this->assertSame($result, $data);
    }

    public function testSaveEntityWithVirtualSitemapFields()
    {

        $data = [
            'loc' => 'http://localhost/test',
            'changefreq' => 'daily',
            'priority' => 9,
            'lastmod' => null
        ];

        $entity = $this->table->get(1);
        $entity->sitemap_url = $data['loc'];
        $entity->sitemap_priority = $data['priority'];
        $entity->sitemap_changefreq = $data['changefreq'];

        $this->table->save($entity);

        $SitemapUrls = TableRegistry::get('Sitemap.SitemapUrls');
        $sitemap = $SitemapUrls->find()
            ->where(['model' => $this->table->alias(), 'foreignKey' => 1])
            ->first();

        $result = $sitemap->extract(array_keys($data));
        $this->assertSame($result, $data);
    }

}