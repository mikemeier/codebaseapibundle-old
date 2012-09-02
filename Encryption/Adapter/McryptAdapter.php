<?php

namespace Ibrows\Bundle\CodebaseApiBundle\Encryption\Adapter;

class McryptAdapter extends AbstractAdapter
{
 
    /**
     * @var string 
     */
    protected $cypher;
    
    /**
     * @var string 
     */
    protected $mode;
    
    /**
     * @var string 
     */
    protected $iv;
    
    /**
     * @param string $cypher
     * @param string $mode
     * @param string $ivMode 
     */
    public function __construct($cypher = MCRYPT_RIJNDAEL_256, $mode = MCRYPT_MODE_ECB, $ivMode = MCRYPT_RAND){
        $this->cypher = $cypher;
        $this->mode = $mode;
        
        $ivSize = mcrypt_get_iv_size($cypher, $mode); 
        $this->iv = mcrypt_create_iv($ivSize, $ivMode);
    }
    
    /**
     * @param string $data
     * @param string $key
     * @return string 
     */
    public function encrypt($data, $key){ 
        return mcrypt_encrypt($this->cypher, $key, $data, MCRYPT_MODE_ECB, $this->iv); 
    } 

    /**
     * @param string $data
     * @param string $key
     * @return string 
     */
    public function decrypt($data, $key){ 
        return mcrypt_decrypt($this->cypher, $key, $data, MCRYPT_MODE_ECB, $this->iv); 
    }
    
}