<?php
/**
 * Created by PhpStorm.
 * User: Khoa
 * Date: 8/12/2015
 * Time: 11:35 PM
 */

namespace Libraries;


class WordChain {

    /**
     * @param $from
     * @param $to
     * @throws \Exception
     */
    public function getShortestChain($from, $to)
    {
        $shortestChain = array();
        if (!$this->sameLength($from, $to)) {
            return array();
        }

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

        if(!in_array($from, $stack)){
            $this->recursion($from, $to, $stack);
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
}