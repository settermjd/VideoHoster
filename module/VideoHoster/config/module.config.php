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
                            'faq|testimonials|support|privacy|copyright|disclaimer|terms
                            |impressum',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'VideoHoster\Controller',
                        'controller'    => 'BusinessPages'
                    ),
                ),
            ),
            /**
             * Routes to administer videos/screencasts
             */
            'administration' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/administration',
                    'defaults' => array(
                        '__NAMESPACE__' => 'VideoHoster\Controller',
                        'controller'    => 'Administration',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'manage' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/manage[/:slug]',
                            'constraints' => array(
                                'slug' => '[a-zA-Z][a-zA-Z-]*[a-zA-Z]'
                            ),
                            'defaults' => array(
                                'action'     => 'manage',
                            ),
                        ),
                    ),
                )
            ),
            /**
             * Routes to view videos/screencasts
             */
            'videos' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/videos',
                    'defaults' => array(
                        '__NAMESPACE__' => 'VideoHoster\Controller',
                        'controller'    => 'Videos',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'view-video' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/:slug',
                            'constraints' => array(
                                'slug' => '[a-zA-Z][a-zA-Z-]*[a-zA-Z]'
                            ),
                            'defaults' => array(
                                'action'     => 'ViewVideo',
                            ),
                        ),
                    ),
                    'free' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/free',
                            'defaults' => array(
                                'action'     => 'Free',
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
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'paginator/default' => __DIR__ . '/../view/video-hoster/pagination/default.phtml',
        )
    ),
);
