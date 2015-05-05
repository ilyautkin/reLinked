<?php

/**
 * The home manager controller for reLinked.
 *
 */
class reLinkedHomeManagerController extends reLinkedMainController {
	/* @var reLinked $reLinked */
	public $reLinked;


	/**
	 * @param array $scriptProperties
	 */
	public function process(array $scriptProperties = array()) {
	}


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('relinked');
	}


	/**
	 * @return void
	 */
	public function loadCustomCssJs() {
		$this->addCss($this->reLinked->config['cssUrl'] . 'mgr/main.css');
		$this->addCss($this->reLinked->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
		$this->addJavascript($this->reLinked->config['jsUrl'] . 'mgr/misc/utils.js');
		$this->addJavascript($this->reLinked->config['jsUrl'] . 'mgr/widgets/items.grid.js');
		$this->addJavascript($this->reLinked->config['jsUrl'] . 'mgr/widgets/items.windows.js');
		$this->addJavascript($this->reLinked->config['jsUrl'] . 'mgr/widgets/home.panel.js');
		$this->addJavascript($this->reLinked->config['jsUrl'] . 'mgr/sections/home.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			MODx.load({ xtype: "relinked-page-home"});
		});
		</script>');
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		return $this->reLinked->config['templatesPath'] . 'home.tpl';
	}
}