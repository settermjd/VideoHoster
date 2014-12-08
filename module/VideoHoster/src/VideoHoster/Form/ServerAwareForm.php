<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * A base form which allows for setting a base action prefix dynamically based on the current request
 *
 * PHP version 5.4
 *
 * @category   VideoHoster
 * @package    Form
 * @author     Matthew Setter <matthew@maltblue.com>
 * @copyright  2014 Matthew Setter, http://www.matthewsetter.com
 */
namespace VideoHoster\Form;

use Zend\Form\Form;

class ServerAwareForm extends Form
{
    /**
     * This function will return a base url path that will work regardless of VHost configuration
     * being available or not.
     *
     * @return string
     */
    public function setActionBaseUri($uri)
    {
        if (!empty($uri)) {
            // prefix the existing action with the generated base path
            $this->setAttribute('action',
                substr($uri, 0, stripos($uri, $this->getAttribute('action'))) . $this->getAttribute('action')
            );
        }

        return $this;
    }
}
