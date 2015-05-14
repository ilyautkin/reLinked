<?php

/**
 * Create a Link
 */
class rldLinkCreateProcessor extends modObjectCreateProcessor {
    public $objectType = 'rldLink';
	public $classKey = 'rldLink';
	public $languageTopics = array('relinked');
	//public $permission = 'create';


	/**
	 * @return bool
	 */
	public function beforeSet() {
        $reLinked = $this->modx->getService('reLinked');
        if ($page = $this->getProperty('page')) {
            $resourceId = $reLinked->findResource($page);
            $this->setProperty('resource', $resourceId);
        }
        
        if ($this->getProperty('resource')) {
            if ($resource = $this->modx->getObject('modResource', $this->getProperty('resource'))) {
                $poscount = count(explode('</p>', $resource->get('content')));
                $links = $this->modx->getCollection('rldLink', array('resource' => $resourceId));
                $positions = array();
                foreach($links as $link) {
                    if (isset($positions[$link->get('position')])) {
                        $positions[$link->get('position')]++;
                    } else {
                        $positions[$link->get('position')] = 1;
                    }
                }
                if (!empty($positions)) {
                    ksort($positions);
                    reset($positions);
                    foreach ($positions as $position => $posLinkCount) {
                        if ($posLinkCount <= rand(2, 6) && $position < $poscount) {
                            $this->setProperty('position', $position);
                            break;
                        }
                    }
                }
                if ($this->getProperty('position', false) == false) {
                    $this->setProperty('position', rand(1, $poscount));
                }
            }
        }
        
        $page = $this->getProperty('resource')
              ? $this->getProperty('resource')
              : trim($this->getProperty('page'));
		if (empty($page)) {
			$this->modx->error->addField('page', $this->modx->lexicon('relinked_item_err_page'));
			$this->modx->error->addField('resource', $this->modx->lexicon('relinked_item_err_page'));
		}
        
        if ($url = $this->getProperty('url')) {
            $resourceId = $reLinked->findResource($url);
            $this->setProperty('target', $resourceId);
        }
		$url = $this->getProperty('target')
              ? $this->getProperty('target')
              : trim($this->getProperty('url'));
		if (empty($url)) {
			$this->modx->error->addField('url', $this->modx->lexicon('relinked_item_err_url'));
			$this->modx->error->addField('target', $this->modx->lexicon('relinked_item_err_url'));
		}
        
        if (!$this->hasErrors()) {
            $find = array();
            if ($this->getProperty('resource')) {
                $find['resource'] = $this->getProperty('resource');
                $pagefield = 'resource';
            } else {
                $find['page'] = $this->getProperty('page');
                $pagefield = 'page';
            }
            if ($this->getProperty('target')) {
                $find['target'] = $this->getProperty('target');
                $urlfield = 'target';
            } else {
                $find['url'] = $this->getProperty('url');
                $urlfield = 'url';
            }
            // Добавить проверку на resource == target
            if($this->modx->getCount($this->classKey, $find)) {
        		$this->modx->error->addField($pagefield, $this->modx->lexicon('relinked_item_err_ae'));
        		$this->modx->error->addField($urlfield,  $this->modx->lexicon('relinked_item_err_ae'));
    		}
        }
        

		return parent::beforeSet();
	}

}

return 'rldLinkCreateProcessor';