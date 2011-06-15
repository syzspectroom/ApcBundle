ApcBundle - A bundle to use and manage APC cache for Symfony2 project
=====================================================================

The ApcBundle provides service to set/get/delete and more PHP APC Cache.

	Basic example :	
	$cache = $this->get('apc_cache');
	if(!$cache->exist('my_cache_var')){
		$cache->add('my_cache_value', [ int $ttl ]);
	}
	echo $cache->get();


The ApcBundle also provides a command to clear APC cache from CLI.

Clear opcode cache:
	
	php app/console apc:clear file

Clear user cache:
	
	php app/console apc:clear user


## Installation

### Requirements

Check that you have the APC extension installed and enabled on your server (see phpinfo())

### Get the bundle

To install the bundle, place it in the `src/Kelu95/ApcBundle` directory of your project
You can do this by adding the bundle as a submodule, cloning it, or simply downloading the source.

    git submodule add https://kelu95@github.com/kelu95/ApcBundle.git src/Kelu95/ApcBundle

### Add the namespace to your autoloader

If it is the first Kelu95 bundle you install in your Symfony 2 project, you
need to add the `Kelu95` namespace to your autoloader:

    // app/autoload.php
    $loader->registerNamespaces(array(
        'Kelu95'                       => __DIR__.'/../src'
        // ...
    ));

### Initialize the bundle

To start using the bundle, initialize the bundle in your Kernel:
	
	// app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Kelu95\ApcBundle\ApcBundle(),
        );
    )

### Declare the service

	//app/config.yml
	services: 
		// ...
	    apc_cache:
	        class: Kelu95\ApcBundle\Controller\Cache
	        arguments: [@doctrine,%apc_enabled%,%apc_ttl%]


### Add this parameters to your parameters
	
	//app\config\config.yml
	parameters:
		// ...
		apc_enabled=true
    	apc_ttl=1800 

	apc_ttl is the default time to live, in second. 1800 : 30 minutes
