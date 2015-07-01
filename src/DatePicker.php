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

class DatePicker extends Text
{

    protected static $params = array();
    protected static $isSetup = false;

    public function init()
    {
        parent::init();

        add_action('admin_enqueue_scripts', array($this,'initScripts'), 20, 1);
        add_action('admin_enqueue_scripts', array($this,'setupScripts'), 9999);
        $this->attributes['class'] = 'wpf-datepicker';

        if (empty($this->attributes['theme'])) {
            $this->attributes['theme'] = 'smoothness';
        }
    }

    public function initScripts($page)
    {
        if (!$this->enqueueCheck($page)) {
            return;
        }

        wp_enqueue_style(
            'jquery-ui-datepicker-css',
            'http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/' . $this->attributes['theme'] . '/jquery-ui.css',
            false,
            '1.0',
            false
        );
        $libJsUrl = $this->getBaseUrl().'/assets/js/datepicker-setup.min.js';
        if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG === true) {
            $libJsUrl = $this->getBaseUrl().'/assets/js/datepicker-setup.js';
        }
        wp_enqueue_script('wpforms-datepicker-setup', $libJsUrl, array('jquery-ui-datepicker'), false, true);

        self::$params[$this->attributes['id']] = array(
            'options' => $this->attributes['options']
        );
        
    }

    public function setupScripts()
    {
        if (!self::$isSetup) {
            wp_localize_script('wpforms-datepicker-setup', 'wpforms_datepicker_setup', self::$params);
            self::$isSetup = true;
        }
    }
}
