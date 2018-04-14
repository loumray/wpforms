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

class SelectSites extends Select
{
    public function render()
    {
        $args = array();
        if (!empty($this->attributes['args'])) {
            $args = $this->attributes['args'];
        }
        if (empty($this->attributes['displayText'])) {
            $this->attributes['displayText'] = '';
        }
        
        $options = array();
        if (!empty($this->attributes['options'])) {
            $options = $this->attributes['options'];
        } else {
            $sites = get_sites();
            $currentBlogId = get_current_blog_id();

            $options = array();

            foreach ($sites as $site) {
                if ($site->blog_id == $currentBlogId) {
                    continue;
                }

                $options[$site->blog_id] = $site->domain.$site->path;
                if ($this->attributes['displayText'] === 'blogname') {
                    $details = get_blog_details(array('blog_id' => $site->blog_id));
                    $options[$site->blog_id] = $details->blogname;
                }
            }
        }
        
        $this->attributes['options'] = $options;

        parent::render();
    }
}
