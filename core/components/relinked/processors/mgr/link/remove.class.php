<?php

/**
 * Remove a Link
 */
class rldLinkRemoveProcessor extends modObjectProcessor {
	public $objectType = 'rldLink';
	public $classKey = 'rldLink';
	public $languageTopics = array('relinked');
	//public $permission = 'remove';


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

			$object->remove();
		}

		return $this->success();
	}

}

return 'rldLinkRemoveProcessor';