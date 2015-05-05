<?php

/**
 * Class reLinkedMainController
 */
abstract class reLinkedMainController extends modExtraManagerController {
	/** @var reLinked $reLinked */
	public $reLinked;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('relinked_core_path', null, $this->modx->getOption('core_path') . 'components/relinked/');
		require_once $corePath . 'model/relinked/relinked.class.php';

		$this->reLinked = new reLinked($this->modx);
		$this->addCss($this->reLinked->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->reLinked->config['jsUrl'] . 'mgr/relinked.js');
		$this->addHtml('
		<script type="text/javascript">
			reLinked.config = ' . $this->modx->toJSON($this->reLinked->config) . ';
			reLinked.config.connector_url = "' . $this->reLinked->config['connectorUrl'] . '";
		</script>
		');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('relinked:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends reLinkedMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}