<?php

interface T9_IDictionary {
    public function setSource($source);
    public function search($words);
}

/**
 * @todo think of more abstract implementation, eg what if the source is database. In that case we cant store all the words in memory & db calls number should be minimal
 */
class T9_Array_Dictionary implements T9_IDictionary {

    /*
    protected $keymap = [
        '2' => ['a', 'b', 'c'],
        '3' => ['d', 'e', 'f'],
        '4' => ['g', 'h', 'i'],
        '5' => ['j', 'k', 'l'],
        '6' => ['m', 'n', 'o'],
        '7' => ['p', 'q', 'r', 's'],
        '8' => ['t', 'u', 'v'],
        '9' => ['w', 'x', 'y', 'z']
    ];
    */
    
    /** @var array */
    protected $words = [];
    /** @var array */
    protected $paths = [];
    
    /** @var integer */
    protected $search_threshold = 4;

    /**
     * 
     * @param string $source
     */
    public function setSource($source) {
        $this->paths = array_keys($source);
        $this->words = array_values($source);
    }
    
    /**
     * @todo maybe add levenstein check, move $this->words[array_search($path, $this->paths)] to separate func
     * @param string $path
     * @return array
     */
    public function search($path) {
        
        if (array_search($path, $this->paths, true) !== false) {
            return [$this->words[array_search($path, $this->paths)]];
        }
        
        $variants = [];

        foreach ($this->paths as $j => $p) {
            if (strpos($p, $path) !== false) {
                $variants[] = $this->words[array_search($p, $this->paths, true)];
                
                if (count($variants) >= $this->search_threshold) {
                    break;
                }
            }
        }

        return $variants;
    }
    
}

$dic = new T9_Array_Dictionary();
$dic->setSource(include_once(__DIR__ . '/words.php'));

var_dump($dic->search('25279')); /** [Zackary, Zakary]  */ 
var_dump($dic->search('95'));    /** [Baylee,Darryl,Daryl,Doyle] */
