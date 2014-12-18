<?php
/**
 * Created by PhpStorm.
 * User: mattsetter
 * Date: 18/12/14
 * Time: 19:06
 */

namespace VideoHoster\Hydrator\Strategy;

use DateTime;
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class TimeStrategy extends DefaultStrategy
{
    /**
     * {@inheritdoc}
     *
     * Convert a string value into a DateTime object
     */
    public function hydrate($value)
    {
        if (is_string($value) && "" === $value) {
            $value = null;
        } elseif (is_string($value)) {
            $valueDate = new DateTime($value);
            $value = $valueDate->format('H:i');
        }

        return $value;
    }
}