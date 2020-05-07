<?php

class MirrorExhibitPagePlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_filters = array('exhibit_layouts');

    public function filterExhibitLayouts($layouts)
    {
        $layouts['mirror'] = array( //ID: 'mirror'
            'name' => 'Mirror Layout', //name: 'Mirror Layout'
            'description' => 'A mirror layout.' //description
        );
        return $layouts;
    }
}
