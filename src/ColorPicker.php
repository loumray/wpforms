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

class ColorPicker extends AbstractField
{

    public function init()
    {
        parent::init();

        add_action('admin_enqueue_scripts', array($this,'initScripts'), 20, 1);
    }

    public function initScripts($page)
    {
        if (!$this->enqueueCheck($page)) {
            return;
        }

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wpforms-colorpicker-setup', $this->getBaseUrl().'/assets/js/colorpicker-setup.min.js', array('wp-color-picker'), false, true);
         
    }

    /**
     * to_html
     *
     * @return string
     */
    public function render()
    {
        $default = '';
        $value  = '';
        if (!empty($this->attributes['default'])) {
            $this->attributes['default'] = esc_attr($this->attributes['default']);
            if (false === strpos($this->attributes['default'], '#')) {
                $this->attributes['default'] = '#' . $this->attributes['default'];
            }
            $default = ' data-default-color="'.$this->attributes['default'].'"';
        }

        if (empty($this->attributes['value'])) {
            $value = ' value="'.$this->attributes['default'].'"';
        } else {
            $value = ' value="'.$this->attributes['value'].'"';
        }

        $return = "";
        $return.= "<label>";
        $return.= "<span class=\"title\">".$this->attributes['label']."</span>";
        $return.= "<div class=\"customize-control-content\">";
        $return.= "<input class=\"color-picker-hex\" type=\"text\" id=\"".$this->attributes['id']."\" name=\"".$this->attributes['name']."\" maxlength=\"7\" placeholder=\"". esc_attr__('Hex Value')."\"".$default.$value." />";
        $return.= "</div>";
        $return.= "</label>";
      
        echo $return;
    }
}
