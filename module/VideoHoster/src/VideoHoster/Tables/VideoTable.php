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

    /**
     * Return all active videos in reverse order of published date
     *
     * @return bool|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function fetchActiveVideos()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join('tblstatus', 'tblstatus.statusId = tblvideo.statusId', array())
               ->where(array('tblstatus.name' => 'active'))
               ->order('publishDate DESC');
        $results = $this->tableGateway->selectWith($select);

        return ($results->count()) ? $results : false;
    }
} 