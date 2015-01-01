<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * A form which allows admin staff to manage the records in the system
 *
 * PHP version 5.4
 *
 * @category   VideoHoster
 * @package    Form
 * @author     Matthew Setter <matthew@maltblue.com>
 * @copyright  2014 Matthew Setter, http://www.matthewsetter.com
 * @version    SVN: $Id$
 */
namespace VideoHoster\Form;

use Zend\Form\Element;
use VideoHoster\InputFilter\VideoInputFilter;

class DeleteVideoForm extends ServerAwareForm
{
    public function __construct()
    {
        parent::__construct('DeleteVideo');

        //$this->setInputFilter(new VideoInputFilter());

        // Add form elements
        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'slug',
            'options' => array(),
            'attributes' => array()
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'options' => array(
                'label' => 'Delete Video'
            ),
            'attributes' => array(
                'class' => 'btn btn-primary',
                'tabindex' => 19,
            )
        ));

        $this->get('submit')->setValue('Delete Video');

    }
}
