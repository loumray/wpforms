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

use pweb\domlib\Asset\StringAsset;
use pweb\domlib\Asset\FileAsset;

class WPControl extends AbstractField
{
  public function __construct($WPControlClass)
  {
    $this->attributes['settings'] = "";
    parent::__construct($attributes);

  }

  public function init()
  {
    parent::init();

    if(empty($this->attributes['id']))
    {
      throw new InvalidArgumentException("The field of type date must have a valid ID");
    }

    $this->addAsset(new FileAsset('datepicker_js', $this->assetsPath.'/libs/jquery-ui-1.9.2.datepicker.js','js',true));
    $this->addAsset(new FileAsset('timepicker_js', $this->assetsPath.'/libs/datepicker-time-addon/jquery-ui-timepicker-addon.js','js',true));
    $this->addAsset(new FileAsset('datepicker_css', $this->assetsPath.'/css/ui.datepicker.css', 'css', true));
    $this->addAsset(new FileAsset('datepicker_css', $this->assetsPath.'/libs/datepicker-time-addon/jquery-ui-timepicker-addon.css', 'css', true));

    $js = "jQuery( '#".$this->attributes['id']."' ).datetimepicker({".$this->attributes['settings']."});\n";
    $this->addAsset(new StringAsset('datepicker-'.$this->attributes['id'], $js ));
  }
  /**
   * to_html
   *
   * @return string
   */
  public function __toString()
  {

    $html = "";
    if(!empty($this->attributes['label']))
    {
      $html = "<label>".$this->attributes['label']."</label>";
    }
    $html.= '<input type="text" '.(isset($this->attributes['id']) ? 'id="'.$this->attributes['id'].'"': "").' name="'.$this->attributes['name'].'" value="'.$this->attributes['value'].'" />';

    return $html;
  }

}
