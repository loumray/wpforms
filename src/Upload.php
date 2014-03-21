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

class WPUpload extends AbstractField
{

  public function init()
  {
    parent::init();
    wp_enqueue_script( 'wp-plupload' );
  }
  /**
   * render
   *
   * @return string
   */
  public function render()
  {

    ?>
    <label>
      <span class="customize-control-title"><?php echo esc_html($this->attributes['label']); ?></span>
      <div class="customize-control-content">
        <a href="#" class="button-secondary upload"><?php _e('Upload'); ?></a>
        <a href="#" class="remove"><?php _e('Remove'); ?></a>
      </div>
    </label>
    <?php
  }

}
