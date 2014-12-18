<?php

namespace VideoHoster\Tables;

use Zend\Db\TableGateway\TableGateway;

class StatusTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getSelectList()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array("statusId", "name"));
        $results = $this->tableGateway->selectWith($select);
        $data = array();
        foreach ($results as $result) {
            $data[$result->statusId] = $result->name;
        }
        return $data;
    }
}
