<?php

namespace Sitemap\Sitemap;


use Cake\I18n\Time;
use Cake\Routing\Router;

class SitemapLocation implements \ArrayAccess
{
    protected $_fields = [];

    public function __construct($loc = null, $priority = null, $lastmod = null, $changefreq = null)
    {
        if (is_array($loc) && isset($loc['loc'])) {
            extract($loc, EXTR_IF_EXISTS);
        }

        // build full location url
        $loc = Router::url($loc, true);

        // validate priority
        $priority = (is_numeric($priority) && $priority >= 0 && $priority <= 1) ? $priority : 0.5;
        $priority = ($priority == 0 || $priority == 1) ? number_format($priority, 0) : number_format($priority, 1);

        // w3c time format
        // @link http://www.w3.org/TR/NOTE-datetime
        //$lastmod = (is_numeric($lastmod)) ? $lastmod : strtotime($lastmod);
        $lastmod = (is_object($lastmod) && $lastmod instanceof Time) ? $lastmod->toW3cString() : $lastmod;
        $lastmod = (is_object($lastmod) && $lastmod instanceof \DateTime) ? $lastmod->format(DATE_W3C) : $lastmod;
        //$lastmod = ($lastmod) ? date(DATE_W3C, strtotime($lastmod)) : null;

        // validate changefreq
        $changefreq = (in_array($changefreq, ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'])) ? $changefreq : null;

        $this->_fields = compact('loc', 'priority', 'lastmod', 'changefreq');
    }

    /*
    public function __set($key, $val)
    {
        if (!array_key_exists($key, $this->_fields)) {
            throw new \InvalidArgumentException(sprintf("Invalid sitemap key '%s'"));
        }

        $this->_fields[$key] = $val;
    }
    */

    public function __get($key)
    {
        if (!array_key_exists($key, $this->_fields)) {
            throw new \InvalidArgumentException(sprintf("Invalid sitemap key '%s'"));
        }

        return $this->_fields[$key];
    }

    public function toArray()
    {
        return $this->_fields;
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->_fields[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        if (isset($this->_fields[$offset])) {
            return $this->_fields[$offset];
        }
        return null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
    }
}