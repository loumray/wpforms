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

class FieldSet implements \IteratorAggregate
{
    protected $prefix = 'wpform-';

    protected $fields = array();
    protected $fieldFactory;

    public function __construct()
    {
        $this->fieldFactory  = new FieldFactory();
        add_action('init',array($this,'init'));
    }

    public function init()
    {
        foreach ($this->fields as $field) {
            $field->init();
        }
    }

    public function addField($field)
    {
        if (!$field instanceof AbstractField) {
            $field = $this->fieldFactory->create($field);
        }
        $this->fields[] = $field;

        return $field;
    }

    public function __toString()
    {
        $echo = '<ul class="'.$this->prefix.'fieldset">';
        foreach ($this->fields as $field) {
            $echo.= $field->toHtml();
        }
        $echo.="</ul>";
        return $echo;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->fields);
    }
}