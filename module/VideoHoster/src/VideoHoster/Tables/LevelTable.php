<?php

namespace VideoHoster\Tables;

use VideoHoster\Models\LevelModel;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Exception\InvalidArgumentException;

class LevelTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function getSelectList()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->columns(array("levelId", "name"));
        $results = $this->tableGateway->selectWith($select);
        $data = array();
        foreach ($results as $result) {
            $data[$result->levelId] = $result->name;
        }
        return $data;
    }
}
