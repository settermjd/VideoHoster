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
                            'about|faq|testimonials|support|privacy|copyright|disclaimer|terms
                            |impressum',
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
                    'view-video' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:episode-name',
                            'constraints' => array(
                                'skill-level' => '[a-zA-Z][a-zA-Z-]*[a-zA-Z]'
                            ),
                            'defaults' => array(
                                'action'     => 'ViewVideo',
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
        ),
        'abstract_factories' => array(
            'VideoHoster\ServiceManager\AbstractFactory\ControllerAbstractFactory',
        )
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => array(
            'video-results' => __DIR__ . '/../view/video-hoster/videos/partials/video-results.phtml',
        )
    ),
);