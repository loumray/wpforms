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

class Slider extends AbstractField
{
    protected static $params = array();
    protected static $isSetup = false;

    public function __construct($attributes)
    {
        parent::__construct($attributes);

        if (!isset($attributes['step'])) {
            $this->attributes['step'] = 1;
        }
        if (!isset($attributes['min'])) {
            $this->attributes['min'] = 0;
        }
        if (!isset($attributes['max'])) {
            $this->attributes['max'] = 10;
        }
    }
    public function init()
    {
        parent::init();
        
        add_action('admin_enqueue_scripts', array($this, 'initScripts'), 20);
        add_action('admin_enqueue_scripts', array($this, 'setupScripts'), 1000);
    }

    public function initScripts($page)
    {
        if (!$this->enqueueCheck($page)) {
            return;
        }
        
        $libJsUrl = $this->getBaseUrl().'/assets/js/slider-setup.min.js';
        if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG === true) {
            $libJsUrl = $this->getBaseUrl().'/assets/js/slider-setup.js';
        }
        wp_enqueue_style('wpforms-jquery-ui', $this->getBaseUrl().'/assets/css/slider.css');
        wp_enqueue_script('wpforms-slider-setup', $libJsUrl, array('jquery-ui-slider'), false, true);

        self::$params[$this->attributes['id']] = array(
            'container' => $this->attributes['id'],
            // 'value' => $this->attributes['value'],
            'step' => $this->attributes['step'],
            'min' => $this->attributes['min'],
            'max' => $this->attributes['max']
        );
    }

    public function setupScripts()
    {
        if (!self::$isSetup) {
            wp_localize_script('wpforms-slider-setup', 'wpforms_slider_setup', self::$params);
            self::$isSetup = true;
        }
    }
    /**
     * to_html
     *
     * @return string
     */
    public function render()
    {
        $return = "";
        $return.= "<div id=\"".$this->attributes['id']."\" class=\"customize-control-slider\">";
        $return.= " <label>";
        $return.= "     <span class=\"title\">".esc_html($this->attributes['label'])."</span>";
        $return.= " </label>";
        $return.= " <div class=\"wpforms-slider-wrap\">";
        $return.= " <div class=\"wpforms-slider wp-slider ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all\"></div>";
        $return.= " <input class=\"wpforms-slider-input\" type=\"text\" name=\"".$this->attributes['name']."\" value=\"".$this->attributes['value']."\"/>";
        $return.= "</div><div>";

        echo $return;
    }
}
