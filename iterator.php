<?php

class myIterator implements Iterator 
{
    private $nodes;
    private $position;

    function __construct(DOMNodeList $nodes)
    {
        return $this->nodes = $nodes;
    }
    

    public function rewind(): void
    {
        $this->position = 0;
    }


    public function current()
    {
        return $this->nodes->item($this->position);
    }

    public function key()
    {
        return $this->current()->nodeName;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return $this->position < $this->nodes->length;
    }


}
$html = file_get_contents(__DIR__ . '/file.html');
$doc = new DOMDocument;
@$doc->loadHTML("\xEF\xBB\xBF" . $html);

$nodes = $doc->getElementsByTagName('meta');
$iterator = new myIterator($nodes);
$removeTegs = [];
foreach ($iterator as $item) {

    if ($item->getAttribute('name') === 'description' or $item->getAttribute('name') === 'keywords') {
        $removeTegs[] = $item;
    }
}

foreach ($removeTegs as $removeTeg) {
    $removeTeg->remove();
}

file_put_contents('result.html', html_entity_decode($doc->saveHTML()));