<?php
return array(
    'controllers'=>array(
        'invokables'=>array(
            'Modulo\Controller\Index'=>'Modulo\Controller\IndexController',
            'Modulo\Controller\Crud'=>'Modulo\Controller\CrudController'
         ),
     ),
      
     'router'=>array(
        'routes'=>array(
            'modulo'=>array(
                 'type'=>'Segment',
                    'options'=>array(
                        'route' => '/modulo[/[:action]]',
                        'constraints' => array(
                                'action'  =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                                'controller' => 'Modulo\Controller\Index',
                                'action'     => 'index'
                          
                        ),
                    ),
           ),
           //Nueva ruta para el nuevo controlador
           'crud'=>array(
                 'type'=>'Segment',
                    'options'=>array(
                        'route' => '/crud[/[:action][/:id]]',
                        'constraints' => array(
                                'action'  =>  '[a-zA-Z][a-zA-Z0-9_-]*',
                        ),
                        'defaults'  =>  array(
                                'controller' => 'Modulo\Controller\Crud',
                                'action'     => 'index'
                          
                        ),
                    ),
           ),
       ),
    ),
     
   //Cargamos el view manager
   'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'numpaginacion' => __DIR__ . '/../view/layout/numpaginacion.phtml',
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'modulo/index/index' => __DIR__ . '/../view/modulo/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
          'modulo' =>  __DIR__ . '/../view',
        ),
    ), 
 );  