<?php
/*
 * This file is part of WPForms project.
 *
 * (c) Louis-Michel Raynauld <louismichel@pweb.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPForms;

use WPCore\Forms\FieldInterface;

abstract class AbstractField implements FieldInterface
{
    protected $prefix = '';

    protected $attributes = array();

    protected $enqueueAdminPages = array();

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
        if (!isset($this->attributes['before'])) {
            $this->attributes['before'] = '';
        }

        if (!isset($this->attributes['after'])) {
            $this->attributes['after'] = '';
        }

        if (!isset($this->attributes['description'])) {
            $this->attributes['description'] = '';
        }

        if (!isset($this->attributes['props'])) {
            $this->attributes['props'] = '';
        } elseif (is_array($this->attributes['props'])) {
            $props = '';
            foreach ($this->attributes['props'] as $prop => $value) {
                $props.= $prop.'="'.$value.'" ';
            }
            $this->attributes['props'] = $props;
        }
        if (!isset($this->attributes['default'])) {
            $this->attributes['default'] = "";
        }

        if (!isset($this->attributes['extrafields'])) {
            $this->attributes['extrafields'] = array();
        }
        
        if (empty($this->attributes['value']) && isset($this->attributes['default'])) {
            $this->attributes['value'] = $this->attributes['default'];
        }
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        $this->attributes['name'] = $this->prefix.$this->attributes['name'];
        return $this;
    }

    public function setEnqueueAdminPages($adminPages)
    {
        $this->enqueueAdminPages = $adminPages;

        return $this;
    }

    public function enqueueCheck($page)
    {
        if (empty($this->enqueueAdminPages)) {
            return true;
        }
        
        return in_array($page, $this->enqueueAdminPages);
    }

    public function init()
    {
        add_action('admin_enqueue_scripts', array($this, 'baseScripts'), 10);
    }
    public function baseScripts()
    {
         wp_enqueue_style('wpforms-base', $this->getBaseUrl().'/assets/css/base.css');
    }

    public function getBaseUrl()
    {
        $pos = strpos(dirname(dirname(__FILE__)), 'wp-content')+strlen('wp-content');
        return content_url().'/'.substr(dirname(dirname(__FILE__)), $pos);
    }

    public function attr($name, $value = null)
    {
        if ($name === null) {
            return $this->attributes;
        }

        if (is_array($name)) {
            foreach ($name as $name => $value) {
                $this->attr($name, $value);
            }

            return $this;
        }

        if ($value === null) {
            return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
        }

        $this->attributes[$name] = $value;

        return $this;
    }

    public function removeAttr($name)
    {
        unset($this->attributes[$name]);
    }
}
