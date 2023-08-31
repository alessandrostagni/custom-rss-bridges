<?php

class IlPostBridge extends BridgeAbstract
{
    const NAME = 'IlPost Bridge';
    const URI = 'https://www.ilpost.it';
    const DESCRIPTION = 'Italian news website';
    const MAINTAINER = 'alessandrostagni';

    public function collectData()
    {
	$url = 'https://www.ilpost.it/';
	$dom = getSimpleHTMLDOM($url);
	$dom = $dom->find('div[id="main-content"]', 0);
        $dom = $dom->find('section[id="content"]', 0);

        foreach ($dom->find('article') as $article) {
            $entryContent = $article->find('div[class="entry-content"]', 0);
	    if ($entryContent != null) {
	       $entryTitle = $entryContent->find('h2[class="entry-title"]', 0);
	       $aContent = $entryContent->find('p',0)->find('a', 0);
	    }
	    $aTitle = $entryTitle->find('a',0);
            $this->items[] = [
                 'title' => $aTitle->title,
                 'uri' => $aContent->href,
                 'content' => $aContent->title
             ];
        }
    }
}
