<?php
/**
 * Created by PhpStorm.
 * User: Khoa
 * Date: 8/12/2015
 * Time: 11:35 PM
 */

namespace Libraries;


use Models\Dictionary;

class WordChain {
    private $dict;
    private $words = array();

    public function __construct(Dictionary $dict)
    {
        $this->dict = $dict;
        $this->dict->setDict();
        $this->words = $this->dict->getDict();
    }

    /**
     * @param $from
     * @param $to
     * @throws \Exception
     */
    public function getShortestChain($from, $to)
    {
        $shortestChain = array();
        /* If 2 words are not in dict */
        if(!$this->dictHas($from) && !$this->dictHas($to)) {
            return array();
        }

        if (!$this->sameLength($from, $to)) {
            return array();
        }

        $shortestChain = $this->recursion($from, $to);

        return $shortestChain;
    }

    /**
     * @param $from
     * @param $to
     * @param array $stack
     * @return array
     */
    public function recursion($from, $to, $stack = array())
    {
        array_push($stack, $from);
        if($from === $to){
            return $stack;
        }

        $adjacentWords = $this->getAdjacentWords($from, $this->words);
        foreach($adjacentWords as $w)
        {
            if(!in_array($w, $stack)){
                if($this->recursion($w, $to, $stack)){
                    return $stack;
                }
                array_pop($stack);
            }
        }

        return array();
    }

    /**
     * @param $a
     * @param $dictionary
     * @return array
     */
    public function getAdjacentWords($a, $dictionary)
    {
        $adjacentWords = array();
        foreach ($dictionary as $b) {

            // A word cannot be adjacent to itself
            if ($a === $b) {
                continue;
            }

            // Adjacent words must be the same length
            if (!$this->sameLength($a, $b)) {
                continue;
            }

            // Adjacent words must only have one letter different
            if (!$this->oneLetterApart($a, $b)) {
                continue;
            }

            $adjacentWords[] = $b;
        }

        return $adjacentWords;
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public function sameLength($a, $b)
    {
        return mb_strlen($a) === mb_strlen($b);
    }

    /**
     * @param $a
     * @param $b
     * @return bool
     */
    public function oneLetterApart($a, $b)
    {
        $a = mb_strtolower($a);
        $b = mb_strtolower($b);

        return levenshtein($a,$b, 2, 1, 2) === 1;
    }

    /**
     * @param $word
     * @return bool
     */
    public function dictHas($word)
    {
        foreach($this->words as $w){
            if($w === $word){
                return true;
            }
        }
        return false;
    }
}