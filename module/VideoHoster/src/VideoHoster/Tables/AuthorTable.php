<?php

namespace VideoHoster\Tables;

use Zend\Db\TableGateway\TableGateway;

class AuthorTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getSelectList()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array("user_id", "display_name"));
        $results = $this->tableGateway->selectWith($select);
        $data = array();
        foreach ($results as $result) {
            $data[$result->user_id] = $result->display_name;
        }
        return $data;
    }
}
