<?php
/**
 * This file is part of WPForms project.
 *
 * (c) Louis-Michel Raynauld <louismichel@pweb.ca>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WPForms;

class UploadImage extends AbstractField
{
    protected static $params = array();
    protected static $isSetup = false;
    
    public function init()
    {
        parent::init();

        add_action('admin_enqueue_scripts', array($this, 'initScripts'), 20);
        add_action('admin_enqueue_scripts', array($this, 'setupScripts'), 1000);
        

        $this->attributes['container'] = 'plupload-upload-ui-'.$this->attributes['id'];
        $this->attributes['browse_button'] = 'plupload-browse-button-'.$this->attributes['id'];
        $this->attributes['drop_element'] = 'drag-drop-area-'.$this->attributes['id'];
        $this->attributes['file_data_name'] = 'async-upload-'.$this->attributes['id'];
        $this->attributes['extensions'] = array('jpg', 'jpeg', 'gif', 'png');
        $this->attributes['preview_thumb_id'] = 'preview-thumb-'.$this->attributes['id'];
        $this->attributes['ajax_action'] = 'upload-image'.$this->attributes['id'];

        add_action('wp_ajax_'.$this->attributes['ajax_action'], array($this, 'ajax'));
    }

    public function initScripts($page)
    {
        if (!$this->enqueueCheck($page)) {
            return;
        }
        wp_enqueue_script('wpforms-plupload-setup', $this->getBaseUrl().'/assets/js/plupload-setup.min.js', array('jquery','plupload-all'), false, true);
        wp_enqueue_style('wpforms-plupload', $this->getBaseUrl().'/assets/css/plupload.css');

        self::$params[$this->attributes['id']] = array(
            'runtimes'            => 'html5,silverlight,flash,html4',
            'browse_button'       => $this->attributes['browse_button'],
            'container'           => $this->attributes['container'],
            'drop_element'        => $this->attributes['drop_element'],
            'file_data_name'      => $this->attributes['file_data_name'],
            'preview_thumb_id'    => $this->attributes['preview_thumb_id'],
            'multiple_queues'     => false,
            'max_file_size'       => wp_max_upload_size().'b',
            'url'                 => admin_url('admin-ajax.php'),
            'flash_swf_url'       => includes_url('js/plupload/plupload.flash.swf'),
            'silverlight_xap_url' => includes_url('js/plupload/plupload.silverlight.xap'),
            'filters'             => array(array('title' => __('Allowed Files', 'wpforms'), 'extensions' => '*')),
            'multipart'           => true,
            'urlstream_upload'    => true,
         
            'multipart_params'    => array(
                '_ajax_nonce' => wp_create_nonce($this->attributes['ajax_action']),
                'action'      => $this->attributes['ajax_action'],
            ),
        );
    }

    public function setupScripts()
    {
        if (!self::$isSetup) {
            wp_localize_script('wpforms-plupload-setup', 'wpforms_plupload_setup', self::$params);
            self::$isSetup = true;
        }
    }

    public function ajax()
    {
        check_ajax_referer($this->attributes['ajax_action']);
        if (!current_user_can('upload_files')) {
            wp_die(__('You do not have permission to upload files.', 'wpforms'));
        }

        $status = wp_handle_upload($_FILES[$this->attributes['file_data_name']], array('test_form'=>true, 'action' => $this->attributes['ajax_action']));
       
        $return = array();
        $return['success'] = true;
        $return['data'] = $status;
        if (!empty($status['error'])) {
            $return['success'] = false;
            $return['msg'] = $status['error'];
        } else {
            // media_handle_upload
        }
        // print_r($status);
       
        echo htmlspecialchars(json_encode($return), ENT_NOQUOTES);
        die();
    }
    /**
     * to_html
     *
     * @return string
     */
    public function render()
    {
        $return = "";
        $return.= "<div id=\"".$this->attributes['container']."\" class=\"customize-image-picker hide-if-no-js\">";

        $return.= "  <span class=\"title\">". esc_html($this->attributes['label'])."</span>";

        $return.= "  <div class=\"customize-control-content\">";
        $return.= "      <div class=\"dropdown preview-thumbnail\" tabindex=\"0\">";
        $return.= "          <div class=\"dropdown-content\">";
        $return.= "                <input class=\"imgurl\" type=\"hidden\" name=\"".$this->attributes['name']."\" value=\"".$this->attributes['value']."\"/>";
        if (empty($this->attributes['value'])) {
            $return.= "                <img id=".$this->attributes['preview_thumb_id']." style=\"display:none;\" />";
        } else {
            $return.= "                <img id=".$this->attributes['preview_thumb_id']." src=\"". esc_url(set_url_scheme($this->attributes['value']))."\" />";
        }
        $return.= "              <div class=\"dropdown-status\">";
        if (empty($this->attributes['value'])) {
            $return.= __('No Image', 'wpforms');
        }
        $return.= "              </div>";
        $return.= "          </div>";
        $return.= "          <div class=\"dropdown-arrow\"></div>";
        $return.= "    </div>";
        $return.= "  </div>";
        $return.= "  <div class=\"library\">";

        // $return.= "   <div id=\"". $this->attributes['drop_element']."\">";
        $return.= "     <div class=\"library-content library-selected\">";
        // $return.= "      <p class=\"upload-dropzone\">". __('Drop files here')."</p>";
        // $return.= "      <p>"._x('or', 'Uploader: Drop files here - or - Select Files')."</p>";
        // $return.= "      <p class=\"drag-drop-buttons\"><input id=\"".$this->attributes['browse_button']."\" type=\"button\" value=\"".esc_attr__('Select File')."\" class=\"button\" /></p>";
        $return.= "         <div id=\"". $this->attributes['drop_element']."\" class=\"upload-dropzone supports-drag-drop\">";
        $return.=              __('Drop a file here or', 'wpforms');
        $return.= "         <input id=\"".$this->attributes['browse_button']."\" type=\"button\" value=\"".esc_attr__('Select File')."\" class=\"button\" />";
        $return.= "         </div>";
        $return.= "         <div class=\"upload-fallback\">";
        $return.= "            <span class=\"button-secondary\">".__('Select File')."</span>";
        $return.= "         </div>";
        $return.= "    </div>";
        $return.= "  </div>";
        // $return.= "   </div>";

        // $return.= "  <div class=\"library\">";
        // $return.= "      <ul>";
        // $return.= "              <li data-customize-tab='upload-new' tabindex='0'>".__('Upload New')."</li>";
        // $return.= "              <li data-customize-tab='uploaded' tabindex='0'>".__('Uploaded New')."</li>";
        // $return.= "      </ul>";
        // $return.= "      <div class=\"library-content\" data-customize-tab='upload-new'>";
        // if( !_device_can_upload()) {
        //     $return.=        '<p>' . sprintf( __('The web browser on your device cannot be used to upload files. You may be able to use the <a href="%s">native app for your device</a> instead.'), 'http://wordpress.org/mobile/' ) . '</p>';
        // } else {
        //   $return.= "         <div class=\"upload-dropzone\">";
        //   $return.=              __('Drop a file here or <a href="#" class="upload">select a file</a>.');
        //   $return.= "         </div>";
        //   $return.= "         <div class=\"upload-fallback\">";
        //   $return.= "            <span class=\"button-secondary\">".__('Select File')."</span>";
        //   $return.= "         </div>";
        // }
        // $return.= "      </div>";
        // $return.= "      <div class=\"library-content\" data-customize-tab='uploaded'>";
        // $return.= "        <div class=\"uploaded-target\"></div>";
        // $return.= "      </div>";
        // $return.= "  </div>";

        $return.= "  <div class=\"actions\">";
        $return.= "      <a href=\"#\" class=\"remove\">".__('Use Default Image', 'wpforms')."</a>";
        $return.= "  </div>";

        $return.= "</div>";
        echo $return;
    }
}
