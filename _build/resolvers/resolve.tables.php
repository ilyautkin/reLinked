<?php

if ($object->xpdo) {
	/** @var modX $modx */
	$modx =& $object->xpdo;

	switch ($options[xPDOTransport::PACKAGE_ACTION]) {
		case xPDOTransport::ACTION_INSTALL:
			$modelPath = $modx->getOption('relinked_core_path', null, $modx->getOption('core_path') . 'components/relinked/') . 'model/';
			$modx->addPackage('relinked', $modelPath);

			$manager = $modx->getManager();
			$objects = array(
				'rldLink',
			);
			foreach ($objects as $tmp) {
				$manager->createObjectContainer($tmp);
			}
            
            $level = $modx->getLogLevel();
            $modx->setLogLevel(xPDO::LOG_LEVEL_FATAL);
            
            $manager->addField('rldLink', 'createdon');

            $modx->setLogLevel($level);
			break;

		case xPDOTransport::ACTION_UPGRADE:
			break;

		case xPDOTransport::ACTION_UNINSTALL:
			break;
	}
}
return true;
