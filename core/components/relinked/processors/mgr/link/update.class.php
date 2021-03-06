<?php

/**
 * Update a Link
 */
class rldLinkUpdateProcessor extends modObjectUpdateProcessor {
    public $objectType = 'rldLink';
	public $classKey = 'rldLink';
	public $languageTopics = array('relinked');
	//public $permission = 'save';


	/**
	 * We doing special check of permission
	 * because of our objects is not an instances of modAccessibleObject
	 *
	 * @return bool|string
	 */
	public function beforeSave() {
		if (!$this->checkPermissions()) {
			return $this->modx->lexicon('access_denied');
		}

		return true;
	}


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$id = (int)$this->getProperty('id');
		if (empty($id)) {
			return $this->modx->lexicon('relinked_item_err_ns');
		}
		$reLinked = $this->modx->getService('reLinked');
        if ($page = $this->getProperty('page')) {
            $resourceId = $reLinked->findResource($page);
            $this->setProperty('resource', $resourceId);
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
            $find = array('id:!=' => $id);
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

return 'rldLinkUpdateProcessor';
