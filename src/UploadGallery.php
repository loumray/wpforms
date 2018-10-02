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

class UploadGallery extends AbstractField
{
    protected static $params = array();
    protected static $isSetup = false;
    
    public function __construct($attributes)
    {
        parent::__construct($attributes);

        //default
        // $this->attributes['suggestedWidth'] = !isset($attributes['suggestedWidth']) ? 300:$attributes['suggestedWidth'];
        // $this->attributes['suggestedHeight'] = !isset($attributes['suggestedHeight']) ? 200:$attributes['suggestedHeight'];
        // $this->attributes['allowCropping'] = !isset($attributes['allowCropping']) ? true:$attributes['allowCropping'];
        // $this->attributes['flexWidth'] = !isset($attributes['flexWidth']) ? 1:$attributes['flexWidth'];
        // $this->attributes['flexHeight'] = !isset($attributes['flexHeight']) ? 1:$attributes['flexHeight'];

        // $this->attributes['flexWidth'] = (int) $this->attributes['flexWidth'];
        // $this->attributes['flexHeight'] = (int) $this->attributes['flexHeight'];
    }
    public function init()
    {
        parent::init();

        add_action('admin_enqueue_scripts', array($this,'initScripts'), 20);
        add_action('admin_enqueue_scripts', array($this,'setupScripts'), 1000);
        

        $this->attributes['container'] = 'medialib-ui-'.$this->attributes['id'];
        $this->attributes['input']     = 'medialib-input-'.$this->attributes['id'];
        // $this->attributes['AttachIdInput'] = 'medialib-inputattach-'.$this->attributes['id'];
        // $this->attributes['AttachIdName'] = $this->attributes['name'].'AttachmentId';
        // $this->attributes['mediabutton'] = __('Select', 'wpforms');
        // $this->attributes['mediatype']   = 'image';
        // $this->attributes['preview_thumb_id'] = 'preview-thumb-'.$this->attributes['id'];
    }

    public function initScripts($page)
    {
        if (!$this->enqueueCheck($page)) {
            return;
        }

        $libJsUrl = $this->getBaseUrl().'/assets/js/gallery-setup.min.js';
        if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG === true) {
            $libJsUrl = $this->getBaseUrl().'/assets/js/gallery-setup.js';
        }

        wp_enqueue_script('wpforms-mediagallery-setup', $libJsUrl, array('media-editor', 'media-upload'), false, true);
        
        wp_enqueue_style('wpforms-mediagallery-css', $this->getBaseUrl().'/assets/css/uploadgallery.css');

        self::$params[$this->attributes['id']] = array(
            'container' => $this->attributes['container'],
            'input'     => $this->attributes['input'],
            // 'AttachIdInput' => $this->attributes['AttachIdInput'],
            // 'preview'   => $this->attributes['preview_thumb_id'],
            // 'allowCropping' => $this->attributes['allowCropping'],
            // 'suggestedWidth'  => $this->attributes['suggestedWidth'],
            // 'suggestedHeight' => $this->attributes['suggestedHeight'],
            // 'flexWidth'   => $this->attributes['flexWidth'],
            // 'flexHeight'  => $this->attributes['flexHeight'],
            // 'mediaBox'  => array(
            //     'title' => $this->attributes['label'],
            //     'type'  => $this->attributes['mediatype'],
            //     'button'=> $this->attributes['mediabutton']
            // )
        );
    }

    public function setupScripts()
    {
        if (!self::$isSetup) {
            wp_localize_script('wpforms-mediagallery-setup', 'wpforms_mediagallery_setup', self::$params);
            self::$isSetup = true;
        }
    }

    public function render() {
        ?>
        <div id="<?php echo $this->attributes['container'];?>" class="hide-if-no-js">
            <span class="title"><?php echo esc_html($this->attributes['label']); ?></span>
            <div class="gallery-preview"><?php
            if (! empty( $this->attributes['value'])) {
                $ids = explode( ',', $this->attributes['value'] );
                ?>
                
                <?php
                foreach ( $ids as $attachmentId ) {
                    $img = wp_get_attachment_image_src($attachmentId, 'thumbnail');
                    echo '<img src="' . $img[0] . '" />';
                }
                
            }
            ?></div>
            <button type="button" class="button edit-gallery"><span class=""><?php  _e('Add/Edit Gallery', 'wpforms'); ?></span></button>
            <button type="button" class="button clear-gallery" style="<?php echo empty($this->attributes['value']) ? 'display:none;':'';?>"><span class=""><?php  _e('Clear', 'wpforms'); ?></span></button>
            <input id="<?php echo $this->attributes['input'];?>" type="hidden" value="<?php echo esc_attr($this->attributes['value']); ?>" name="<?php echo $this->attributes['name'];?>" />
        </div>
        <?php
    }
}
