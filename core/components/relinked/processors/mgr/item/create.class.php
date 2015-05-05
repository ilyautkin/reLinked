<?php

/**
 * Create an Item
 */
class reLinkedItemCreateProcessor extends modObjectCreateProcessor {
	public $objectType = 'reLinkedItem';
	public $classKey = 'reLinkedItem';
	public $languageTopics = array('relinked');
	//public $permission = 'create';


	/**
	 * @return bool
	 */
	public function beforeSet() {
		$name = trim($this->getProperty('name'));
		if (empty($name)) {
			$this->modx->error->addField('name', $this->modx->lexicon('relinked_item_err_name'));
		}
		elseif ($this->modx->getCount($this->classKey, array('name' => $name))) {
			$this->modx->error->addField('name', $this->modx->lexicon('relinked_item_err_ae'));
		}

		return parent::beforeSet();
	}

}

return 'reLinkedItemCreateProcessor';