<?php 
namespace Kelu95\ApcBundle\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;

/*
 * Call example
 * 
 * 	{% if not cache('my_cache_var') %}
 *		my html code
 * 	{% endif %}
 *	{{ cache_save()|raw }}
 * */
class ApcCacheExtension extends \Twig_Extension
{
    protected $loader;
    protected $controller;
    protected $environment;
    protected $container;
    protected $cache;
    protected $cache_exist=false;

    public function __construct($container)
    {
        $this->container = $container;
		$this->cache = $this->container->get('apc_cache'); //load apc cache service
    }
    
    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }
	
    public function getName()
    {
        return 'apc_cache';
    }

    
     /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
	public function getFunctions()
    {
        return array(
            'cache' => new \Twig_Function_Method($this, 'cache'),
            'cache_save' => new \Twig_Function_Method($this, 'cacheSave'),
        );
    }

	/*
	 * Test is a cache variable exist and start output buffering if needed
	 * 
	 * @param string $name
	 * @return true or false
	 * 
	 * */
    public function cache($name) 
    {
		if(!$this->cache->exist($name)){
			ob_start();
			return false;
		}
		$this->cache_exist=true;
		return true;
    }
	
	/*
	 * Save the cahce from output buffering or return cached datas
	 * 
	 * @return mixed value
	 * 
	 * */
	public function cacheSave() 
    {
    	if(!$this->cache_exist){
	    	$out = ob_get_contents();
	    	ob_end_clean();
			$this->cache->add($out);
		}
		return $this->cache->get();
    }
}