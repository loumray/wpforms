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

class SelectTerm extends Select
{

    public function render()
    {
        $args = array();
        if (!empty($this->attributes['args'])) {
            $args = $this->attributes['args'];
        }
        $terms = get_terms($this->attributes['taxonomies'], $args);
        $options = array();
        if (!empty($this->attributes['options'])) {
            $options = $this->attributes['options'];
        }
        foreach ($terms as $term) {
            $options[$term->term_id] = $term->name;
        }

        $this->attributes['options'] = $options;
        parent::render();
    }
}
