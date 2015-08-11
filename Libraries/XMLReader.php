<?php
/**
 * Created by PhpStorm.
 * User: khoa.nguyen
 * Date: 8/11/2015
 * Time: 11:25 AM
 */

namespace Libraries;


class XMLReader {
    /**
     * @param $source_folder
     * @param $ext
     * @return array
     */
    public function getFileList($source_folder, $ext)
    {
        if( !is_dir( $source_folder ) ) {
            die ( "Invalid directory.\n\n" );
        }

        $source_files = glob($source_folder. DIRECTORY_SEPARATOR. "*.".$ext);

        return $source_files;
    }

    /**
     * @param $xmlFile
     * @throws \Exception
     */
    public function filterXML($xmlFile)
    {
        if(!file_exists($xmlFile)){
            throw new \Exception('File does not exist.');
        }
        $content = @file_get_contents($xmlFile);
        $results = array();
        if($content){
            preg_match_all("/<hw>(.*?)<\/hw>(.*?)<def>(.*?)<\/def>/", $content, $matches);
            $blocks = $matches[0];
            $blocks_num = count($blocks);
            for($j = 0; $j<=$blocks_num - 1; $j++){
                /* filter word string */
                preg_match_all("/<hw>(.*?)<\/hw>/", $blocks[$j], $word_array);
                $words = $word_array[0];
                $word = $this->sanitize($words[0]);
                $results[$j]['w'] = $word;

                /* filter definition string */
                preg_match_all("/<def>(.*?)<\/def>/", $blocks[$j], $def_array);
                $definitions = $def_array[0];
                $definition = $this->sanitize($definitions[0]);
                $results[$j]['def'] = $definition;
            }
        }

        return $results;
    }

    /**
     * @param $string
     * @return mixed|string
     */
    private function sanitize($string)
    {
        $string = addslashes(strip_tags($string));
        $string = preg_replace('{-}', ' ', $string);
        $string = preg_replace("/[^a-zA-Z0-9\s]/", "", $string);

        return $string;
    }
}