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
    
    public function __construct($attributes)
    {
        parent::__construct($attributes);

        //default
        $this->attributes['suggestedWidth'] = !isset($attributes['suggestedWidth']) ? 300:$attributes['suggestedWidth'];
        $this->attributes['suggestedHeight'] = !isset($attributes['suggestedHeight']) ? 200:$attributes['suggestedHeight'];
        $this->attributes['allowCropping'] = !isset($attributes['allowCropping']) ? true:$attributes['allowCropping'];
        $this->attributes['flexWidth'] = !isset($attributes['flexWidth']) ? 1:$attributes['flexWidth'];
        $this->attributes['flexHeight'] = !isset($attributes['flexHeight']) ? 1:$attributes['flexHeight'];

        $this->attributes['flexWidth'] = (int) $this->attributes['flexWidth'];
        $this->attributes['flexHeight'] = (int) $this->attributes['flexHeight'];
    }
    public function init()
    {
        parent::init();

        add_action('admin_enqueue_scripts', array($this,'initScripts'), 20);
        add_action('admin_enqueue_scripts', array($this,'setupScripts'), 1000);
        

        $this->attributes['container'] = 'medialib-ui-'.$this->attributes['id'];
        $this->attributes['input']     = 'medialib-input-'.$this->attributes['id'];
        $this->attributes['AttachIdInput'] = 'medialib-inputattach-'.$this->attributes['id'];
        $this->attributes['AttachIdName'] = $this->attributes['name'].'AttachmentId';
        $this->attributes['mediabutton'] = __('Select', 'wpforms');
        $this->attributes['mediatype']   = 'image';
        $this->attributes['preview_thumb_id'] = 'preview-thumb-'.$this->attributes['id'];
    }

    public function initScripts($page)
    {
        if (!$this->enqueueCheck($page)) {
            return;
        }

        $isCropScriptOn = wp_script_is('customize-models', 'registered') &&
            get_theme_support('custom-header') &&
            wp_script_is('customize-controls', 'registered');

        $libJsUrl = $this->getBaseUrl().'/assets/js/library-setup.min.js';
        if (SCRIPT_DEBUG === true) {
            $libJsUrl = $this->getBaseUrl().'/assets/js/library-setup.js';
        }
        if ($isCropScriptOn === true) {
            wp_enqueue_script('wpforms-medialibrary-setup', $libJsUrl, array('media-upload', 'customize-controls', 'customize-models'), false, true);
        } else {
            wp_enqueue_script('wpforms-medialibrary-setup', $libJsUrl, array('media-upload'), false, true);
        }
        
        wp_enqueue_style('wpforms-plupload', $this->getBaseUrl().'/assets/css/uploadlibrary.css');

        self::$params[$this->attributes['id']] = array(
            'container' => $this->attributes['container'],
            'input'     => $this->attributes['input'],
            'AttachIdInput' => $this->attributes['AttachIdInput'],
            'preview'   => $this->attributes['preview_thumb_id'],
            'allowCropping' => $this->attributes['allowCropping'],
            'suggestedWidth'  => $this->attributes['suggestedWidth'],
            'suggestedHeight' => $this->attributes['suggestedHeight'],
            'flexWidth'   => $this->attributes['flexWidth'],
            'flexHeight'  => $this->attributes['flexHeight'],
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

        $return.= "  <span class=\"title\">". esc_html($this->attributes['label'])."</span>";
        $return.= "  <div class=\"customize-control-content\">";
        $return.= "      <div class=\"dropdown preview-thumbnail\" tabindex=\"0\">";
        $return.= "          <div class=\"dropdown-content\">";
        $return.= "                <input id=\"".$this->attributes['AttachIdInput']."\" type=\"hidden\" name=\"".$this->attributes['AttachIdName']."\" />";
        $return.= "                <input class=\"imgurl\" id=\"".$this->attributes['input']."\" type=\"hidden\" name=\"".$this->attributes['name']."\" value=\"".$this->attributes['value']."\"/>";
        if (empty( $this->attributes['value'])) {
            $return.= "                <img id=".$this->attributes['preview_thumb_id']." style=\"display:none;\" />";
        } else {
            $return.= "                <img id=".$this->attributes['preview_thumb_id']." src=\"". esc_url(set_url_scheme($this->attributes['value']))."\" />";
        }
        
        $displayStatus = "";
        $displayRemove = "style=\"display: none;\"";
        if (!empty($this->attributes['value'])) {
            $displayStatus = "style=\"display: none;\"";
            $displayRemove = "";
        }
        $return.=               "<div class=\"dropdown-status\"$displayStatus>";
        $return.=                 __('No Image', 'wpforms');
        $return.=               "</div>";
        $return.=            "</div>";
        $return.= "          <div class=\"dropdown-arrow\"></div>";
        $return.= "    </div>";
        $return.= "  </div>";

        $return.= "  <div class=\"actions\">";
        $return.= "      <a href=\"#\" $displayRemove class=\"remove button\">".__('Remove', 'wpforms')."</a>";
        $return.= "  </div>";

        $return.= "</div>";
        echo $return;
    }
}
