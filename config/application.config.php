<?php
return array(
    // This should be an array of module namespaces used in the application.
    //se añaden los modulos creados, tener en cuenta que el ultimo modulo es el que tomara el layout por default, se pude cambiar variando el orden //pendiente de configuracion aidcional en caso se quiera cambiar de layout dinamicamente
    'modules' => array(
        'Anio',	
		'Dispositivo',
        'Estado',
		'Modelo',
        'Modulo', 
		'Ficha',
        'Mantenimiento',
        'Tipoatencion',
        'Unidad',
        'Ejecutora',
        'Accion',
        'Marca',
        'Area',
		'Personal',
        'Rangoip',
		'Docadquisicion',
        'Usuario',
        'Rol',
        'Situacion',
        'User',
        'Application',

    ),

    // These are various options for the listeners attached to the ModuleManager
    'module_listener_options' => array(
        // This should be an array of paths in which modules reside.
        // If a string key is provided, the listener will consider that a module
        // namespace, the value of that key the specific path to that module's
        // Module class.
        'module_paths' => array(
            './module',
            './vendor',
        ),

        // An array of paths from which to glob configuration files after
        // modules are loaded. These effectively override configuration
        // provided by modules themselves. Paths may use GLOB_BRACE notation.
        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
		
		// HABLITAR LA CACHE - OPTIMIZACION DE TIEMPO
		// Whether or not to enable a configuration cache.
        // If enabled, the merged configuration will be cached and used in
//        // subsequent requests.
//        'config_cache_enabled' => true,
//        // The key used to create the configuration cache file name.
//        'config_cache_key' => "2245023265ae4cf87d02c8b6ba991139",
// 
//        // Whether or not to enable a module class map cache.
//        // If enabled, creates a module class map cache which will be used
//        // by in future requests, to reduce the autoloading process.
//        'module_map_cache_enabled' => true,
//        // The key used to create the class map cache file name.
//        'module_map_cache_key' => "496fe9daf9baed5ab03314f04518b928",
//         
//         
//        // The path in which to cache merged configuration.
//        'cache_dir' => "./data/cache/modulecache",

        // Whether or not to enable a configuration cache.
        // If enabled, the merged configuration will be cached and used in
        // subsequent requests.
        //'config_cache_enabled' => $booleanValue,

        // The key used to create the configuration cache file name.
        //'config_cache_key' => $stringKey,

        // Whether or not to enable a module class map cache.
        // If enabled, creates a module class map cache which will be used
        // by in future requests, to reduce the autoloading process.
        //'module_map_cache_enabled' => $booleanValue,

        // The key used to create the class map cache file name.
        //'module_map_cache_key' => $stringKey,

        // The path in which to cache merged configuration.
        //'cache_dir' => $stringPath,

        // Whether or not to enable modules dependency checking.
        // Enabled by default, prevents usage of modules that depend on other modules
        // that weren't loaded.
        // 'check_dependencies' => true,
    ),

    // Used to create an own service manager. May contain one or more child arrays.
    //'service_listener_options' => array(
    //     array(
    //         'service_manager' => $stringServiceManagerName,
    //         'config_key'      => $stringConfigKey,
    //         'interface'       => $stringOptionalInterface,
    //         'method'          => $stringRequiredMethodName,
    //     ),
    // )

   // Initial configuration with which to seed the ServiceManager.
   // Should be compatible with Zend\ServiceManager\Config.
   // 'service_manager' => array(),
);
