<?php
//require __DIR__ . '/../vendor/autoload.php';
//use JonnyW\PhantomJs\Client;

class DigimonCardIoBridge extends BridgeAbstract
{
    const NAME = 'DigimonCard.io';
    const URI = 'https://digimoncard.io';
    const DESCRIPTION = 'digimoncard.io bridge';
    const MAINTAINER = 'alessandrostagni';
    const COLOR_MAPPING = array(
    'Red' => '#ee2d48',
    'Purple' => '#af51d6',
    'Blue' => '#157ffb',
    'Black' => '#2c2d2d',
    'Yellow' => '#c4cd35',
    'Green' => '#30c12e',
    'White' => '#c7c7c7'
    );
    
    private function getDeckList($url) {
	$elements_to_remove = array();    
	$dom = getSimpleHTMLDOM($url);
	$infoArea = $dom->find('body', 0)->find('main', 0)->find('div[class=container]',0)->find('div[class=info-area]',0);
	$deckList = $infoArea->find('div[id=full-deck]', 0);
	$deckList->author = $infoArea->find('div[class=deck-metadata-container]', 0)->find('div[class=deck-metadata-info]', 0)->find('span', 0)->find('a')[1]->innertext;
	$deckList->find('div[id=main_deck_stack]', 0)->outertext = '';
	$deckList->find('div[id=main_deck_gallery]', 0)->outertext = '';
	$mainDeck = $deckList->find('div[id=main_deck]', 0);
	$mainDeck->setAttribute('style', '');
	foreach($mainDeck->find('div[class=cardGroupSingle]') as $cardGroup){
	    $deckCountNumber = $cardGroup->find('div[class=deckcountnumber]', 0);
	    $a = $cardGroup->find('a', 0);
	    $cardId = str_replace('/card/?search=', '', $a->href);
	    $deckCountNumber->innertext = $deckCountNumber->innertext.' '.$cardId;
	    $a->setAttribute('href', self::URI.$a->href);
	    $img = $a->find('img');
	    $img[0]->setAttribute('src', $img[0]->getAttribute('data-src'));
	    $img[1]->outertext = '';
	}
	
	$eggDeck = $deckList->find('div[id=egg_deck]', 0);
	$eggDeck->setAttribute('style', '');
        foreach($eggDeck->find('span[class=img-container]') as $cardGroup){
            $img = $cardGroup->find('a', 0)->find('img', 0);
	    $img->setAttribute('src', $img->getAttribute('data-src'));
	}
	$sideDeck = $deckList->find('div[id=side_deck]', 0);
	$sideDeck->setAttribute('style', '');
	foreach($sideDeck->find('span[class=img-container]') as $cardGroup){
            $img = $cardGroup->find('a', 0)->find('img', 0);
            $img->setAttribute('src', $img->getAttribute('data-src'));
        }
	$deckList->find('div[class=deck-breakdown]', 0)->outertext='';
	return $deckList;
    }
    public function collectData()
    {
	$url = self::URI;
    	$dom = getSimpleHTMLDOM($url);
	$dom = $dom->find('body', 0)->find('main', 0)->find('div[class=container]', 0)->find('div[id=latest-decks]', 0)->find('div[class="deck-layout-flex"]', 0);
	foreach ($dom->find('div[class="deck-layout-single-flex"]') as $deck) {
	    $color = $deck->find('span[class=ribbon-deck-text]', 0)->innertext;	
	    $color = self::COLOR_MAPPING[$color];
	    $a = $deck->find('a', 0);
	    $uri = $url.$a->href;
	    $deckList = $this->getDeckList($uri);
	    $div = $a->find('div', 0);
	    $imgUri = $div->getAttribute('data-src');
	    $entryTitle = $div->find('h4', 0);
	    $entryTitle->find('span', 0)->outertext = '';
	    $this->items[] = [
		 'title' => $entryTitle->innertext,   
		 'author' => $deckList->author,
		 'uri' => $uri,
		 'content' => "<a href=\"$uri\"><img align=center src=\"$imgUri\"></img></a><h1><font color=\"$color\">$entryTitle</font></h1>$deckList"
	    ];
	}
    }
    
}
