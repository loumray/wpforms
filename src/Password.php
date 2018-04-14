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

class Password extends AbstractField
{
    /**
     * __toString
     *
     * @return string
     */
    public function render()
    {
        $html = "";
        if (!empty($this->attributes['label'])) {
            $html = "<label>".$this->attributes['label']."</label>";
        }
        $value = "";
        if (!empty($this->attributes['value'])) {
            $value = ' value="'.$this->attributes['value'].'"';
        }

        $class = "";
        if (!empty($this->attributes['class'])) {
            $class = ' class="'.$this->attributes['class'].'"';
        }
        $placeholder = "";
        if (!empty($this->attributes['placeholder'])) {
            $placeholder = ' placeholder="'.$this->attributes['placeholder'].'"';
        }

        $html.= '<input type="password" '.(isset($this->attributes['id']) ? 'id="'.$this->attributes['id'].'"': "").' name="'.$this->attributes['name'].'"'.$placeholder.$value.$class.$this->attributes['props'].' />';

        echo $html;
    }
}
