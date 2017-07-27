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

class FieldFactory
{
    public static function create($field)
    {
        if (!isset($field['type'])) {
            throw new \InvalidArgumentException("Type of field must be set");
        }
        $fieldClass = __NAMESPACE__.'\\'.ucfirst($field['type']);
        if (class_exists($fieldClass)) {
            return new $fieldClass($field);
        }

        throw new \InvalidArgumentException("The type of field '".$field['type']."' is not supported");

    }
}
