<?php
/**
 * Created by PhpStorm.
 * User: khoa.nguyen
 * Date: 8/11/2015
 * Time: 1:01 PM
 */

namespace Controllers;

require('Libraries/XMLReader.php');
require('Database/Database.php');

use Database\Database;
use Libraries\XMLReader;


class DefaultController {
    public function dumpXML()
    {
        $xmlReader = new XMLReader();
        $db = new Database();
        $conn = $db->connect();

        $source_file = $xmlReader->getFileList('xml', 'xml');
        if(is_array($source_file)){
            foreach($source_file as $path){
                $filterXML = $xmlReader->filterXML($path);
                $db->insert('gcide', $filterXML, 'w,def');
            }
        }
    }
}