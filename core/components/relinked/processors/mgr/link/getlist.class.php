<?php

/**
 * Get a list of Links
 */
class rldLinkGetListProcessor extends modObjectGetListProcessor {
    public $objectType = 'rldLink';
    public $classKey = 'rldLink';
	public $defaultSortField = 'id';
	public $defaultSortDirection = 'DESC';
	//public $permission = 'list';


	/**
	 * * We doing special check of permission
	 * because of our objects is not an instances of modAccessibleObject
	 *
	 * @return boolean|string
	 */
	public function beforeQuery() {
		if (!$this->checkPermissions()) {
			return $this->modx->lexicon('access_denied');
		}

		return true;
	}


	/**
	 * @param xPDOQuery $c
	 *
	 * @return xPDOQuery
	 */
	public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns($this->classKey, $this->classKey).',
                `rldResource`.`id` as `rid`, `rldResource`.`pagetitle` as `r_title`, `rldResource`.`uri` as `r_uri`,
                `rldTarget`.`id` as `tid`, `rldTarget`.`pagetitle` as `t_title`, `rldTarget`.`uri` as `t_uri`,
                `rldResource`.`context_key` as `r_context`, `rldTarget`.`context_key` as `t_context`');
        $c->leftJoin('modResource', 'rldResource', array('`'.$this->classKey.'`.`resource` = `rldResource`.`id`'));
        $c->leftJoin('modResource', 'rldTarget',   array('`'.$this->classKey.'`.`target` = `rldTarget`.`id`'));
        $query = trim($this->getProperty('query'));
    	if ($query) {
			$c->where(array(
				'page:LIKE' => "%{$query}%",
                'OR:url:LIKE' => "%{$query}%",
                'OR:anchor:LIKE' => "%{$query}%",
                'OR:rldResource.pagetitle:LIKE' => "%{$query}%",
                'OR:rldTarget.pagetitle:LIKE' => "%{$query}%",
                'OR:rldResource.uri:LIKE' => "%{$query}%",
                'OR:rldTarget.uri:LIKE' => "%{$query}%",
			));
		}
        $context = trim($this->getProperty('linkctx'));
    	if ($context) {
			$c->where(array(
                'rldResource.context_key' => $context,
                'OR:rldTarget.context_key:=' => $context
			));
		}
		return $c;
	}


	/**
	 * @param xPDOObject $object
	 *
	 * @return array
	 */
	public function prepareRow(xPDOObject $object) {
		$array = $object->toArray();
        if (!$array['r_title'] && $array['page']) {
            $array['r_title'] = '<span class="red">'.$array["page"].'</span>';
        }
        if (!$array['t_title'] && $array['url']) {
            $array['t_title'] = '<span class="red">'.$array["url"].'</span>';
        }
        
		$array['actions'] = array();

		// Edit
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-edit',
			'title' => $this->modx->lexicon('relinked_item_update'),
			//'multiple' => $this->modx->lexicon('relinked_items_update'),
			'action' => 'updateItem',
			'button' => true,
			'menu' => true,
		);

		if (!$array['active']) {
			$array['actions'][] = array(
				'cls' => '',
				'icon' => 'icon icon-power-off action-green',
				'title' => $this->modx->lexicon('relinked_item_enable'),
				'multiple' => $this->modx->lexicon('relinked_items_enable'),
				'action' => 'enableItem',
				'button' => true,
				'menu' => true,
			);
		}
		else {
			$array['actions'][] = array(
				'cls' => '',
				'icon' => 'icon icon-power-off action-gray',
				'title' => $this->modx->lexicon('relinked_item_disable'),
				'multiple' => $this->modx->lexicon('relinked_items_disable'),
				'action' => 'disableItem',
				'button' => true,
				'menu' => true,
			);
		}

		// Remove
		$array['actions'][] = array(
			'cls' => '',
			'icon' => 'icon icon-trash-o action-red',
			'title' => $this->modx->lexicon('relinked_item_remove'),
			'multiple' => $this->modx->lexicon('relinked_items_remove'),
			'action' => 'removeItem',
			'button' => true,
			'menu' => true,
		);

		return $array;
	}

}

return 'rldLinkGetListProcessor';