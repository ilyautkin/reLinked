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

		return parent::beforeSet();
	}

}

return 'rldLinkCreateProcessor';