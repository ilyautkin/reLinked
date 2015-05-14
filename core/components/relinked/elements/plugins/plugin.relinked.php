<?php
switch ($modx->event->name) {
    case 'OnWebPagePrerender':
        $reLinked = $modx->getService('reLinked');
        /** @var array $scriptProperties */
        /** @var modExtra $modExtra */
        if (!$reLinked = $modx->getService('reLinked', 'reLinked', $modx->getOption('relinked_core_path', null, $modx->getOption('core_path') . 'components/relinked/') . 'model/relinked/', $scriptProperties)) {
            return 'Could not load reLinked class!';
        }
        if ($links = $modx->getCollection('rldLink', array('resource' => $modx->resource->get('id')))) {
            $content = explode('</p>', $modx->resource->get('content'));
            $positions = array();
            foreach ($links as $link) {
                $positions[$link->get('position')][] = $link;
            }
            foreach ($positions as $pos => $position) {
                $block = array();
                foreach($position as $link) {
                    $href = $link->get('target') ? $modx->makeURL($link->get('target')) : $link->get('url');
                    $block[] = '<a href="'.$href.'">'.$link->get('anchor').'</a>';
                }
                if (isset($content[$pos])) {
                    $content[$pos] = '<p>'.implode(', ', $block).'</p>'.$content[$pos];
                } else {
                    $content[] = '<p>'.implode(', ', $block).'</p>';
                }
            }
            $modx->resource->_output = str_replace($modx->resource->get('content'), implode('</p>', $content), $modx->resource->_output);
        }
        break;
    default:
        break;
}
return '';