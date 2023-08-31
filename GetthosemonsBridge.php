<?php

class GetthosemonsBridge extends BridgeAbstract
{
    const NAME = 'Getthosemons';
    const URI = 'https://www.getthosemons.co.nz';
    const DESCRIPTION = 'Getthosemon TCG shop website';
    const MAINTAINER = 'alessandrostagni';
   
    public function collectData()
    {
	$url = 'https://www.getthosemons.co.nz/';
	$justRestockedUrl= $url.'just-re-stocked';
	$dom = getSimpleHTMLDOM($justRestockedUrl);
        foreach ($dom->find('article[class="card"]') as $article) {
            $entryContent = $article->find('div[class="card-body"]', 0);
	    if ($entryContent != null) {
	       $entryTitle = $entryContent->find('h4[class="card-title"]', 0)->find('a', 0);
	    }
	    
	    $figure = $article->find('figure[class="card-figure"]', 0);
	    $imgContainer = $figure->find('div[class="card-img-container"]', 0);
	    $img = $imgContainer->find('img', 0);
            $price = $entryContent->find('span[class="price price--withTax price--main"]', 0)->innertext;  
	    $this->items[] = [
                 'title' => $entryTitle->innertext,
                 'uri' => $entryTitle->href,
                 'content' => "<a href='$entryTitle->href'>$img</a><h2 style='color:#30aa55; font-size:35px';'>$price</h2>"
	       ];
	}
    }
    
}
