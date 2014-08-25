<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
            'import' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/import[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Import\Controller\Index',
                        'action' => 'index',
                    ),
                ),
                //'may_terminate' => true,
                'child_routes' => array(
                    'wildcard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
                        'options' => array(
                            'key_value_delimiter' => '/',
                            'param_delimiter' => '/',
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'importslo' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/importslo[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Import\Controller\Slo',
                        'action' => 'index',
                    ),
                ),
                //'may_terminate' => true,
                'child_routes' => array(
                    'wildcard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
                        'options' => array(
                            'key_value_delimiter' => '/',
                            'param_delimiter' => '/',
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'manageslo' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/manageslo[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Import\Controller\Manageslo',
                        'action' => 'index',
                    ),
                ),
                //'may_terminate' => true,
                'child_routes' => array(
                    'wildcard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
                        'options' => array(
                            'key_value_delimiter' => '/',
                            'param_delimiter' => '/',
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'stone' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/stone[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Import\Controller\Stone',
                        'action' => 'index',
                    ),
                ),
                //'may_terminate' => true,
                'child_routes' => array(
                    'wildcard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
                        'options' => array(
                            'key_value_delimiter' => '/',
                            'param_delimiter' => '/',
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'containerlog' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/containerlog[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Import\Controller\Containerlog',
                        'action' => 'index',
                    ),
                ),
                //'may_terminate' => true,
                'child_routes' => array(
                    'wildcard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
                        'options' => array(
                            'key_value_delimiter' => '/',
                            'param_delimiter' => '/',
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'supplier' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/supplier[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Import\Controller\Supplier',
                        'action' => 'index',
                    ),
                ),
                //'may_terminate' => true,
                'child_routes' => array(
                    'wildcard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
                        'options' => array(
                            'key_value_delimiter' => '/',
                            'param_delimiter' => '/',
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'containerdrawing' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/containerdrawing[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Import\Controller\Containerdrawing',
                        'action' => 'index',
                    ),
                ),
                //'may_terminate' => true,
                'child_routes' => array(
                    'wildcard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
                        'options' => array(
                            'key_value_delimiter' => '/',
                            'param_delimiter' => '/',
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'slab' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/slab[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Import\Controller\Slab',
                        'action' => 'index',
                    ),
                ),
                //'may_terminate' => true,
                'child_routes' => array(
                    'wildcard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
                        'options' => array(
                            'key_value_delimiter' => '/',
                            'param_delimiter' => '/',
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
            'slabdrawing' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/slabdrawing[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ),
                    'defaults' => array(
                        'controller' => 'Import\Controller\Slabdrawing',
                        'action' => 'index',
                    ),
                ),
                //'may_terminate' => true,
                'child_routes' => array(
                    'wildcard' => array(
                        'type' => 'Zend\Mvc\Router\Http\Wildcard',
                        'options' => array(
                            'key_value_delimiter' => '/',
                            'param_delimiter' => '/',
                        ),
                        'may_terminate' => true,
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            //register model table classes here....
            '\My\Dhtmlx\Models\FormElements' => '\My\Dhtmlx\Models\FormElements'
        )
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Import\Controller\Index' => 'Import\Controller\IndexController',
            'Import\Controller\Slo' => 'Import\Controller\SloController',
            'Import\Controller\Manageslo' => 'Import\Controller\ManagesloController',
            'Import\Controller\Stone' => 'Import\Controller\StoneController',
            'Import\Controller\Containerlog' => 'Import\Controller\ContainerlogController',
            'Import\Controller\Supplier' => 'Import\Controller\SupplierController',
            'Import\Controller\Containerdrawing' => 'Import\Controller\ContainerdrawingController',
            'Import\Controller\Slab' => 'Import\Controller\SlabController',
            'Import\Controller\Slabdrawing' => 'Import\Controller\SlabdrawingController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);
