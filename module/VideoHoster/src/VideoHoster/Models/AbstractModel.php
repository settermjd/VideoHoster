<?php
/**
 * Created by PhpStorm.
 * User: mattsetter
 * Date: 29/12/14
 * Time: 11:47
 */

namespace VideoHoster\Models;


class AbstractModel
{
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
}