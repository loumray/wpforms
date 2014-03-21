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

abstract class AbstractField
{
    protected $attributes = array();

    protected $prefix = 'wpform-';

    public function __construct($attributes)
    {
        $this->attributes = $attributes;
        if (!isset($this->attributes['before'])) {
            $this->attributes['before'] = '';
        }

        if(!isset($this->attributes['after'])) {
            $this->attributes['after'] = '';
        }

        if (!isset($this->attributes['description'])) {
            $this->attributes['description'] = '';
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

    public function init() {}
    abstract public function __toString();

    public function getBaseUrl()
    {
        return get_bloginfo('url').'/'.substr(dirname(dirname(__FILE__)), strlen(ABSPATH));
    }

    public function attr($name, $value = null) {
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

    public function removeAttr($name) {
        unset($this->attributes[$name]);
    }

    public function toHtml()
    {
        $html = "";
        $html.= '<li class="'.$this->prefix.'field '.$this->prefix.strtolower($this->attributes['type']).'">';
        $html.= $this->attributes['before'];
        $html.= $this->__toString();
        if (!empty($this->attributes['description'])) {
            $echo.= '<div class="'.$this->prefix.'field_desc">';
            $echo.= $this->attributes['description'];
            $echo.= '</div>';
        }
        $html.= $this->attributes['after'];
        $html.= '</li>';

        return $html;
    }
}