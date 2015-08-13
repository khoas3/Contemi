<?php
/**
 * Created by PhpStorm.
 * User: khoa.nguyen
 * Date: 8/11/2015
 * Time: 1:01 PM
 */

namespace Controllers;

require_once('Libraries/XMLReader.php');
require_once('Libraries/WordChain.php');
require_once('Database/Database.php');
require_once('Models/Dictionary.php');

use Database\Database;
use Libraries\XMLReader;
use Libraries\WordChain;
use Models\Dictionary;


class DefaultController {
    /**
     * @throws \Exception
     */
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
        if($conn){
            $db->disconnect();
        }
    }

    /**
     *
     */
    public function wordChain()
    {
        $db = new Database();
        $dict = new Dictionary($db);
        $wordChain = new WordChain($dict);
        $shortestChain = $wordChain->getShortestChain('cat', 'dog');
        var_dump($shortestChain);
    }
}