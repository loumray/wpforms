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

class Radio extends AbstractField
{
    public function __construct($attributes)
    {
        parent::__construct($attributes);

        // if(!empty($this->attributes['specifyoptions']))
    // {
    //   $this->factory = new FieldFactory();
    //   foreach($this->attributes['specifyoptions'] as $val => $field)
    //   {
    //     $this->attributes['extrafields'][$val] = $this->factory->create($field);
    //   }
    // }
    }

    public function render()
    {
        $html = "";
        if (!empty($this->attributes['label'])) {
            $html.= "<label for=\"".$this->attributes['name']."\">".$this->attributes['label']."</label>";
        }

//     $class.= '';
//     if(!empty($this->attributes['class']))
//     {
//       $class.= ' class="'.$this->attributes['class'].'"';
//     }
        $id = "";
        if (!empty($this->attributes['id'])) {
            $id.= ' id="'.$this->attributes['id'].'"';
        }
        $name = "";
        if (!empty($this->attributes['name'])) {
            $name.= ' name="'.$this->attributes['name'].'"';
        }

        if (!empty($this->attributes['options'])) {
            $html.= '<ul class="'.$this->attributes['id'].' field_radio_group">';
            $extraField = "";
            foreach ($this->attributes['options'] as $val => $text) {
                $selected = "";
                if ($this->attributes['value'] == $val) {
                    $selected = "checked=\"checked\"";
                }
                if (!empty($this->attributes['extrafields'][$val])) {
                    $extraField.= $this->attributes['extrafields'][$val]->toHtml();
                }
                $html .= "<li>";
                $html .= "<input type=\"radio\" $id $name value=\"$val\" $selected>";
                $html .= '<span class="'.$val.'radio_label">'.$text.'</span>';
                $html .= "</li>";
            }
            $html.= '</ul>';
            $html.= $extraField;
        }
        echo $html;
    }
}
