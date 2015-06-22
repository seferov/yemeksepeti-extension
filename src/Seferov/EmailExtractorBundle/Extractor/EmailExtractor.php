<?php

namespace Seferov\EmailExtractorBundle\Extractor;

class EmailExtractor
{
    public function process($text)
    {
        $emails = [];

        $pattern = '/([a-zA-Z0-9+._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z._-]{2,10})/';
        preg_match_all($pattern, $text, $matches);
        if (count($matches) != 0) {
            foreach ($matches[1] as $item) {
                array_push($emails, $item);
            }
        };

        return array_unique($emails);
    }
}