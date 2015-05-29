<?php

/**
 * Get a Link
 */
class rldLinkGetProcessor extends modObjectGetProcessor {
    public $objectType = 'rldLink';
	public $classKey = 'rldLink';
	public $languageTopics = array('relinked:default');
	//public $permission = 'view';


	/**
	 * We doing special check of permission
	 * because of our objects is not an instances of modAccessibleObject
	 *
	 * @return mixed
	 */
	public function process() {
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		return parent::process();
	}

}

return 'rldLinkGetProcessor';