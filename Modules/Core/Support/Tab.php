<?php
namespace Modules\Core\Support;

use Encore\Admin\Widgets\Tab as pTab;

class Tab extends pTab
{
    /**
     * Add a tab and its contents.
     *
     * @param string            $title
     * @param string|Renderable $content
     * @param bool              $active
     *
     * @return $this
     */
    public function add($title, $content, $active = false, $id= '')
    {
        $this->data['tabs'][] = [
            'id'      => $id ? $id : mt_rand(),
            'title'   => $title,
            'content' => $content,
            'type'    => static::TYPE_CONTENT,
        ];

        if ($active) {
            $this->data['active'] = count($this->data['tabs']) - 1;
        }

        return $this;
    }
}