<?php

namespace Fango\MainBundle\Generator;

/**
 * Class UniqueLinkGenerator
 * @todo improve it
 * @package Fango\MainBundle\Generator
 */
class UniqueLinkGenerator
{
    public function getNextUniqueLink($s)
    {
        $a = str_split($s);
        $c = count($a);
        if (preg_match('/^z*$/', $s)) {
            return str_repeat('a', $c + 1);
        }

        while ('z' == $a[--$c]) {
            $this->nextLetter($a[$c]);
        }

        $this->nextLetter($a[$c]);
        return implode($a);
    }

    private function nextLetter(&$str)
    {
        $str = ('z' == $str ? 'a' : ++$str);
    }
}