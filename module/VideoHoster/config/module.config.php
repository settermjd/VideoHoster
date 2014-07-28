<?php
return array(
    'router' => array(
        'routes' => array(
            'business-pages' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '[/pages[/:action]]',
                    'constraints' => array(
                        'action'     =>
                            'about|faq|testimonials|support|privacy|copyright|disclaimer|terms',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'VideoHoster\Controller',
                        'controller'    => 'BusinessPages'
                    ),
                ),
            ),
            'tutorials' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/tutorials',
                    'defaults' => array(
                        '__NAMESPACE__' => 'VideoHoster\Controller',
                        'controller'    => 'Videos',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'by-category' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/category/:category',
                            'constraints' => array(
                                'category' => '[a-zA-Z][a-zA-Z_-]*[a-zA-Z]'
                            ),
                            'defaults' => array(
                                'action'     => 'ByCategory',
                            ),
                        ),
                    ),
                    'by-skill-level' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/skill/:skill-level',
                            'constraints' => array(
                                'skill-level' => 'beginner|intermediate|advanced'
                            ),
                            'defaults' => array(
                                'action'     => 'BySkillLevel',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'VideoHoster\Controller\BusinessPages' =>
                'VideoHoster\Controller\BusinessPagesController',
            'VideoHoster\Controller\Videos' => 'VideoHoster\Controller\VideosController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);