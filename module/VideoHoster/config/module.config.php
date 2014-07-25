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
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'VideoHoster\Controller\BusinessPages' => 'VideoHoster\Controller\BusinessPagesController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
);