<?php

/**
 * Disable a Link
 */
class rldLinkDisableProcessor extends modObjectProcessor {
	public $objectType = 'rldLink';
	public $classKey = 'rldLink';
	public $languageTopics = array('relinked');
	//public $permission = 'save';


	/**
	 * @return array|string
	 */
	public function process() {
		if (!$this->checkPermissions()) {
			return $this->failure($this->modx->lexicon('access_denied'));
		}

		$ids = $this->modx->fromJSON($this->getProperty('ids'));
		if (empty($ids)) {
			return $this->failure($this->modx->lexicon('relinked_item_err_ns'));
		}

		foreach ($ids as $id) {
			/** @var rldLink $object */
			if (!$object = $this->modx->getObject($this->classKey, $id)) {
				return $this->failure($this->modx->lexicon('relinked_item_err_nf'));
			}

			$object->set('active', false);
			$object->save();
		}

		return $this->success();
	}

}

return 'rldLinkDisableProcessor';
