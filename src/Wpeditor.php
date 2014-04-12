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

class Wpeditor extends AbstractField
{
    public function __construct($attributes)
    {
        parent::__construct($attributes);

        if (!isset($attributes['media_buttons'])) {
            $this->attributes['media_buttons'] = false;
        }
        if (!isset($attributes['wpautop'])) {
            $this->attributes['wpautop'] = true;
        }
        if (!isset($attributes['rows'])) {
            $this->attributes['rows'] = 10;
        }
        if (!isset($attributes['editor_class'])) {
            $this->attributes['editor_class'] = '';
        }
        if (!isset($attributes['teeny'])) {
            $this->attributes['teeny'] = false;
        }
        if (!isset($attributes['dfw'])) {
            $this->attributes['dfw'] = false;
        }
        if (!isset($attributes['tinymce'])) {
            $this->attributes['tinymce'] = true;
        }
        if (!isset($attributes['quicktags'])) {
            $this->attributes['quicktags'] = true;
        }
    }

    public function render()
    {
        $settings = array(
            'textarea_name' => $this->attributes['name'],
            'media_buttons' => $this->attributes['media_buttons'],
            'wpautop' => $this->attributes['wpautop'],
            'textarea_rows' => $this->attributes['rows'],
            'editor_class' => $this->attributes['editor_class'],
            'teeny' => $this->attributes['teeny'],
            'dfw' => $this->attributes['dfw'],
            'tinymce' => $this->attributes['tinymce'],
            'quicktags' => $this->attributes['quicktags']
        );
        if (isset($this->attributes['tabindex'])) {
            $settings['tabindex'] = $this->attributes['tabindex'];
        }
        if (isset($this->attributes['editor_css'])) {
            $settings['editor_css'] = $this->attributes['editor_css'];
        }
        wp_editor($this->attributes['value'], $this->attributes['id'], $settings);
    }
}
