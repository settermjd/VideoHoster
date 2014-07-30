<?php

namespace VideoHoster\Tables;

use VideoHoster\Models\VideoModel;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Where as WherePredicate;

class VideoTable
{
    const DATETIME_FORMAT = 'Y-m-d';

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchBySlug($videoSlug)
    {
        if (!empty($videoSlug)) {
            $select = $this->tableGateway->getSql()->select();
            $select->where(array("slug" => $videoSlug));
            $results = $this->tableGateway->selectWith($select);

            if ($results->count() == 1) {
                return $results->current();
            }
        }

        return false;
    }
} 