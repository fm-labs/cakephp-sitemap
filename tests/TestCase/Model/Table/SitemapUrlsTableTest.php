<?php
declare(strict_types=1);

namespace Sitemap\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * Sitemap\Model\Table\SitemapUrlsTable Test Case
 */
class SitemapUrlsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Sitemap\Model\Table\SitemapUrlsTable
     */
    public $SitemapUrls;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.sitemap.sitemap_urls',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('SitemapUrls') ? [] : ['className' => 'Sitemap\Model\Table\SitemapUrlsTable'];
        $this->SitemapUrls = TableRegistry::getTableLocator()->get('SitemapUrls', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->SitemapUrls);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
