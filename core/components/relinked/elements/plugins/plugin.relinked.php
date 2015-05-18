<?php
switch ($modx->event->name) {
    case 'OnWebPagePrerender':

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
            $chunk = $modx->newObject('modChunk');
            $chunk->set('content', $modx->resource->get('content'));
            $chunk->set('name', 'chunk');
            $replace = $chunk->process();
            
            $chunk2 = $modx->newObject('modChunk');
            $chunk2->set('name', 'chunk2');
            $chunk2->set('content', implode('</p>', $content));
            $replace2 = $chunk2->process();
            
            $modx->resource->_output = str_replace($replace, $replace2, $modx->resource->_output);
        }
        break;
    default:
        break;
}
return '';