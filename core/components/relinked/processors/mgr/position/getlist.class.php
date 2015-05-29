<?php

/**
 * Get a list of Links
 */
class rldLinkGetListProcessor extends modProcessor {
    
    public function process() {
        $resource = $this->modx->getObject('modResource', $this->getProperty('resID'));
        $positions = $resource->getProperty('positions', 'relinked');
        $list = array();
        foreach($positions as $id => $position) {
            $list[] = array('id' => $id, 'template' => $position);
        }
        return $this->outputArray($list);
    }

}

return 'rldLinkGetListProcessor';