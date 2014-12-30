<?php
namespace VideoHoster;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'abstract_factories' => array(
                'VideoHoster\ServiceManager\AbstractFactory\TableAbstractFactory',
                'VideoHoster\ServiceManager\AbstractFactory\TableGatewayAbstractFactory'
            )
        );
    }

    public function onBootstrap($e)
    {
        $events = $e->getApplication()->getEventManager()->getSharedManager();
        $events->attach('ZfcUser\Form\Register','init', function($e) {
            $form = $e->getTarget();

            $form->setAttribute('class', 'form-horizontal');

            $elements = array(
                'username', 'email', 'password', 'passwordVerify'
            );
            foreach($elements as $element) {
                $form->get($element)->setLabelAttributes(
                    array('class'  => 'col-sm-2 control-label')
                );
                $form->get($element)->setAttribute('class', 'form-control');
            }

            $form->get('submit')->setAttribute('class', 'btn btn-primary');
            $form->get('submit')->setLabel('Subscribe');
        });

        $events->attach('ZfcUser\Form\Login','init', function($e) {
            $form = $e->getTarget();

            $form->setAttribute('class', 'form-horizontal');

            $form->get('submit')->setAttribute('class', 'btn btn-primary');
            $form->get('submit')->setLabel('Subscribe');

            $elements = array(
                'identity', 'credential'
            );
            foreach($elements as $element) {
                $form->get($element)->setLabelAttributes(
                    array('class'  => 'col-sm-2 control-label')
                );
                $form->get($element)->setAttribute('class', 'form-control');
            }

            // Do what you please with the form instance ($form)
        });
    }
}
