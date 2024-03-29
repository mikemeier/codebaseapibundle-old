<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Store\Adapter;

class FileAdapter extends AbstractAdapter
{
    
    /**
     * @var string 
     */
    protected $path;
    
    /**
     * @var type 
     */
    protected $file;

    public function __construct($path)
    {
        $dirname = dirname($path);
        
        if(!is_dir($dirname)){
            mkdir($dirname);
        }
        
        if(!is_dir($dirname) || !is_writable($dirname)){
            throw new \InvalidArgumentException('Dir "'. $dirname .'" invalid');
        }
        
        $this->path = $path;
        $this->loadData();
    }
    
    protected function loadData()
    {
        if(!file_exists($this->path)){
            return;
        }
        
        $this->data = unserialize(file_get_contents($this->path));
    }
    
    public function flush()
    {
        file_put_contents($this->path, serialize($this->data));
    }
    
}