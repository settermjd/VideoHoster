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
        $select->columns(array("userId", "displayName"));
        return $this->tableGateway->selectWith($select);
    }
}
