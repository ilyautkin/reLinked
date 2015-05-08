<?php

/**
 * Create a Link
 */
class rldImportProcessor extends modProcessor {
    public $languageTopics = array('relinked');

    public function process() {
        $object = array();
        
        if (!empty($_FILES['csv-file']['name']) && !empty($_FILES['csv-file']['tmp_name'])) {
            $data = false;
            $handle = fopen($_FILES['csv-file']['tmp_name'], "r");
            while (($csv = fgetcsv($handle, 1000, ";")) !== FALSE) {
                $row = $csv; //array('anchor' => $csv[0],'url' => $csv[1],'page' => $csv[2]);
                $data[] = $row;
            }
            fclose($handle);

            if ($data === false) {
                //return $this->log('error',$this->modx->lexicon('importx.err.fileuploadfailed'));
                //$this->modx->error->addField('csv-file-btn', $this->modx->lexicon('relinked_import_fileparsefailed'));
                $this->modx->error->addField('csv-file-btn', $this->modx->lexicon('relinked_import_fileuploadfailed'));
            }
        }
        
        if ((!isset($data) || $data === false) && $this->getProperty('csv')) {
            $data = explode(PHP_EOL, trim($this->getProperty('csv')));
            foreach($data as $k => $csv) {
                $data[$k] = array('anchor' => $csv[0],'url' => $csv[1],'page' => $csv[2]);
            }
        }
        if (!isset($data) || ($data === false) || empty($data)) {
            $this->modx->error->addField('csv-file-btn', $this->modx->lexicon('relinked_import_fileuploadfailed'));
        }
        
        $object['data'] = $this->modx->toJSON($data);
        
        if ($this->hasErrors()) {
            $o = $this->failure();
        } else {
            $o = $this->success('', $object);
        }
        return $o;
    }

}

return 'rldImportProcessor';