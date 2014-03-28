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

class UploadLibrary extends AbstractField
{
    protected static $params = array();
    protected static $isSetup = false;
    
    public function init()
    {
        parent::init();

        add_action('admin_enqueue_scripts',array($this,'initScripts'), 20);
        add_action('admin_enqueue_scripts',array($this,'setupScripts'), 1000);
        

        $this->attributes['container'] = 'medialib-ui-'.$this->attributes['id'];
        $this->attributes['input']     = 'medialib-input-'.$this->attributes['id'];
        $this->attributes['mediabutton'] = __('Select');
        $this->attributes['mediatype']   = 'image';
        $this->attributes['preview_thumb_id'] = 'preview-thumb-'.$this->attributes['id'];

    }

    public function initScripts($page)
    {
        if (!$this->enqueueCheck($page)) {
            return;
        }

        wp_enqueue_script('wpforms-medialibrary-setup', $this->getBaseUrl().'/assets/js/library-setup.js',array('media-upload'), false, true);
        wp_enqueue_style('wpforms-plupload', $this->getBaseUrl().'/assets/css/plupload.css');

        self::$params[$this->attributes['id']] = array(
            'container' => $this->attributes['container'],
            'input'     => $this->attributes['input'],
            'preview'   => $this->attributes['preview_thumb_id'],
            'mediaBox'  => array(
                'title' => $this->attributes['label'],
                'type'  => $this->attributes['mediatype'],
                'button'=> $this->attributes['mediabutton']
            )
        );
    }

    public function setupScripts()
    {
        if (!self::$isSetup) { 
            wp_localize_script('wpforms-medialibrary-setup', 'wpforms_medialibrary_setup', self::$params);
            self::$isSetup = true;
        }
    }

    public function render()
    {
        $return = "";
        $return.= "<div id=\"".$this->attributes['container']."\" class=\"custom-media-upload hide-if-no-js\">";

        $return.= "  <span class=\"title\">". esc_html( $this->attributes['label'])."</span>";

        $return.= "  <div class=\"customize-control-content\">";
        $return.= "      <div class=\"dropdown preview-thumbnail\" tabindex=\"0\">";
        $return.= "          <div class=\"dropdown-content\">";
        $return.= "                <input class=\"imgurl\" id=\"".$this->attributes['input']."\" type=\"hidden\" name=\"".$this->attributes['name']."\" value=\"".$this->attributes['value']."\"/>";
        if (empty( $this->attributes['value'])) {
            $return.= "                <img id=".$this->attributes['preview_thumb_id']." style=\"display:none;\" />";
        } else {
            $return.= "                <img id=".$this->attributes['preview_thumb_id']." src=\"". esc_url(set_url_scheme($this->attributes['value']))."\" />";
        }
        $return.= "              <div class=\"dropdown-status\">";
        if (empty( $this->attributes['value'])) {
            $return.= __('No Image');
        }
        $return.= "              </div>";
        $return.= "          </div>";
        $return.= "          <div class=\"dropdown-arrow\"></div>";
        $return.= "    </div>";
        $return.= "  </div>";

        // $return.= "  <div class=\"actions\">";
        // $return.= "      <a href=\"#\" class=\"remove\">".__('Use Default Image', 'wpforms')."</a>";
        // $return.= "  </div>";

        $return.= "</div>";
        echo $return;
    }

}