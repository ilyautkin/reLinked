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
		$page = trim($this->getProperty('page'));
		if (empty($page)) {
			$this->modx->error->addField('page', $this->modx->lexicon('relinked_item_err_page'));
		}

		return parent::beforeSet();
	}

}

return 'rldLinkCreateProcessor';