<?php

namespace Ibrows\CodebaseApiBundle\StoreAndEncryption;

use Ibrows\CodebaseApiBundle\Store\StoreInterface;
use Ibrows\CodebaseApiBundle\Encryption\EncryptionInterface;

class StoreAndEncryption
{
    
    /**
     * @var StoreInterface 
     */
    protected $store;
    
    /**
     * @var EncryptionInterface 
     */
    protected $encryption;
    
    public function __construct(StoreInterface $store, EncryptionInterface $encryption)
    {
        $this->store = $store;
        $this->encryption = $encryption;
    }
    
    /**
     * @param string $pass
     * @param string $key
     * @param string $data 
     * @return StoreAndEncryption
     */
    public function set($pass, $key, $data){
        $dataEncrypted = $this->encryption->encrypt($data, $pass);
        $this->store->set($key, $dataEncrypted);
        
        return $this;
    }
    
    /**
     * @param string $pass
     * @param string $key
     * @param mixed $default 
     * @return string
     */
    public function get($pass, $key, $default = null){
        $data = $this->store->get($key, $default);
        
        if($data === $default){
            return $default;
        }
        
        return $this->encryption->decrypt($data, $pass);
    }
    
    /**
     * @param string $pass
     * @return array 
     */
    public function getData($pass){
        $dataArray = array();
        
        foreach($this->store->getData() as $key => $data){
            $dataArray[$key] = $this->encryption->decrypt($data, $pass);
        }
        
        return $dataArray;
    }
    
    /**
     * @return StoreAndEncryption 
     */
    public function flush(){
        $this->store->flush();
        
        return $this;
    }
    
    /**
     * @param string $key
     * @return StoreAndEncryption
     */
    public function remove($key){
        $this->store->remove($key);
        
        return $this;
    }
    
}