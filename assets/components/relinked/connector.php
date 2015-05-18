<?php
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var reLinked $reLinked */
$reLinked = $modx->getService('relinked', 'reLinked', $modx->getOption('relinked_core_path', null, $modx->getOption('core_path') . 'components/relinked/') . 'model/relinked/');
$modx->lexicon->load('relinked:default');

// handle request
$corePath = $modx->getOption('relinked_core_path', null, $modx->getOption('core_path') . 'components/relinked/');
$path = $modx->getOption('processorsPath', $reLinked->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
	'processors_path' => $path,
	'location' => '',
));