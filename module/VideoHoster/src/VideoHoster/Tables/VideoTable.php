<?php

namespace VideoHoster\Tables;

use VideoHoster\Models\VideoModel;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where as WherePredicate;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Exception\InvalidArgumentException;

class VideoTable
{
    const DATETIME_FORMAT = 'Y-m-d';

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Fetches a single video record based on the supplied slug
     *
     * @param string $videoSlug
     * @return bool
     */
    public function fetchBySlug($videoSlug)
    {
        if (!empty($videoSlug)) {
            $select = $this->tableGateway->getSql()->select();
            $select->where(array("slug" => $videoSlug));
            $results = $this->tableGateway->selectWith($select);

            return ($results->count() == 1) ? $results->current() : false;
        }

        throw new InvalidArgumentException('invalid slug supplied');
    }

    /**
     * Return all active videos in reverse order of published date
     *
     * @return bool|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function fetchActiveVideos($resultLimit = null)
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join(
            'tblstatus',
            'tblstatus.statusId = tblvideo.statusId',
            array()
        )
            ->where(array('tblstatus.name' => 'active'))
            ->order('publishDate DESC');

        if (!is_null($resultLimit)) {
            $select->limit((int)$resultLimit);
        }

        $results = $this->tableGateway->selectWith($select);

        if (!$results->count() || is_null($results)) {
            $results = new ResultSet();
            $results->initialize(array());
        }

        return $results;
    }

    /**
     * Create a new or update an existing record
     *
     * @param VideoModel $video
     * @return int
     */
    public function save(VideoModel $video)
    {
        $data = $video->getArrayCopy();
        unset($data['videoId']);

        if ((int)$video->videoId == 0) {
            if ($this->tableGateway->insert($data)) {
                return $this->tableGateway->getLastInsertValue();
            }
        } else {
            $retstat = $this->tableGateway->update(
                $data, array('videoId' => (int)$video->videoId)
            );
            if ($retstat) {
                return $retstat;
            }
        }

        return false;
    }
}
