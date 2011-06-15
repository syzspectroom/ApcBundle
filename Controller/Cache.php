<?php

namespace Kelu95\ApcBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
* Service to manage your cache APC
 * 
 * Call example :
 * 
 * 	$cache = $this->get('apc_cache');
 *	if(!$cache->exist('my_cache_var')){
 *		$cache->add('my_cache_value', [ int $ttl ]);
 *	}
 *	echo $cache->get();
 */

class Cache
{
	protected $apc_enabled; //conf parameter value
	protected $apc_ttl;  //conf parameter value
	protected $cache_name;  //var cache name for instance
	
	/**
	 * @param int $apc_enabled (conf parameter value)
	 * @param int $apc_ttl (conf parameter default time to leave)
	 */
	public function __construct($apc_enabled,$apc_ttl)
    {
		$this->apc_enabled=$apc_enabled;
		$this->apc_ttl=$apc_ttl;
		
		$this->isApcEnabled(); //check if apc is enabled and correctly configured
    }
	
	/**
	 * Test if a cache user var exist
	 * 
	 * @param string $name
	 * @return true or false
	 */
	public function exist($name)
    {
		$this->cache_name=$name;
		if (apc_exists($name)) {
			return true;
		}
		return false;
    }
	
	/**
	 * Set a cache user var name, not really used but can be helpfull
	 * 
	 * @param string $name
	 */
	public function setName($name)
	{
		$this->cache_name=$name;
	}

	/**
	 * Add a cache user var
	 * 
	 * @param mixed $value
	 * @param int $ttl or empty : cache time to leave in seconds. If empty get the conf parameter ttl value 
	 * @return string cache variable value or false
	 */
	public function add($value,$ttl='')
    {
		if(empty($this->cache_name) || empty($value)){
			throw new \Exception("The APC Cache need a cache name and cache value");
			return false;
		}
		if($ttl=='') $ttl=$this->apc_ttl;
		apc_add($this->cache_name, $value,$ttl);
		return $this->cache_name;
    }
	
	/**
	 * Get a cache user var
	 * 
	 * @param string $name variable name, can be empty, so get the pre defined cache_name var
	 * @return string cache variable value
	 */
	public function get($name='')
	{
		if($name=='') $name=$this->cache_name;
		return apc_fetch($name);
	}
	
    /**
	 * Delete cache user var
	 * 
	 * @param string $name variable name
	 */
 	public function delete($name)
	{
		apc_delete($name);
	}   
	
	 /**
	 * Delete cache user or filehits
	 * 
	 * @param string $cache_type user or empty
	 */
	public function clearAll($cache_type='')
	{
		apc_clear_cache($cache_type);
	}
	
	 /**
     * Get apc cache infos by type (user or filehits)
	 * 
	 * @param string $cache_type user or empty
	 * @return array
     */
	public function showInfos($cache_type='user')
	{
		return apc_cache_info($cache_type);
	} 
	
	 /**
     * Check if apc is enabled
	 * 
	 * @return true or false
     */
	private function isApcEnabled()
	{
		if(function_exists('apc_add') && $this->apc_enabled==1){
			return true;
		}
		throw new \Exception("The APC Extension is not enabled.\nPlease, check your server configuration.\nThe parameter \"apc_enabled\" must set to true\nThe parameter \"apc_ttl\" must be int val");
		return false;
	}
}