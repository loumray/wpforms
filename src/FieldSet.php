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

use WPCore\Forms\FieldSetInterface;

class FieldSet implements FieldSetInterface, \IteratorAggregate
{
    protected $prefix = 'wpform-';

    protected $fieldPrefix = '';

    protected $fields = array();

    protected $adminPages = array('post.php');

    public function __construct()
    {
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
            $field = FieldFactory::create($field);
        }
        $field->setEnqueueAdminPages($this->adminPages);
        $field->setPrefix($this->fieldPrefix);
        $this->fields[] = $field;

        return $field;
    }

    public function setFieldNamePrefix($prefix)
    {
        $this->fieldPrefix = $prefix;

        return $this;
    }
    public function render()
    {
        echo '<ul class="'.$this->prefix.'fieldset">';
        foreach ($this->fields as $field) {
            $html = '<li class="'.$this->prefix.'field '.$this->prefix.strtolower($field->attr('type')).'">';
            $html.= $field->attr('before');
            echo $html;
            $field->render();
            $html = $field->attr('after');
            $html.= '</li>';
            echo $html;
        }
        echo "</ul>";
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