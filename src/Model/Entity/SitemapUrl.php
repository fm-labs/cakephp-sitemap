<?php
namespace Sitemap\Model\Entity;

use Cake\ORM\Entity;

/**
 * SitemapUrl Entity.
 *
 * @property int $id
 * @property string $scope
 * @property string $model
 * @property int $foreignKey
 * @property string $loc
 * @property string $changefreq
 * @property int $priority
 * @property \Cake\I18n\Time $lastmod
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class SitemapUrl extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
