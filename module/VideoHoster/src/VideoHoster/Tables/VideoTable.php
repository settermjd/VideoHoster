<?php

namespace VideoHoster\Tables;

use VideoHoster\Models\VideoModel;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Stdlib\Exception\InvalidArgumentException;
use Zend\Validator\Digits;
use Zend\Validator\Regex;

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
     * @throws InvalidArgumentException
     * @return VideoModel|null
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
     * Delete a video by its video id value
     *
     * @param int $videoId
     * @return mixed
     */
    public function deleteBySlug($slug)
    {
        // Using the Digits validator (and filter) to provide concise check for a valid value
        $validator = new Regex('/[a-zA-Z][a-zA-Z-]*[a-zA-Z]/');
        
        if (!$validator->isValid($slug)) {
            throw new InvalidArgumentException("Cannot delete record without a valid video slug");
        }
        return $this->tableGateway->delete(array('slug' => $slug));
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
     * Return all active videos in reverse order of published date
     *
     * @return bool|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function fetchFreeVideos()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->join(
            'tblstatus',
            'tblstatus.statusId = tblvideo.statusId',
            array()
        )
            ->join(
                'tblpaymentrequirement',
                'tblpaymentrequirement.paymentRequirementId = tblvideo.paymentRequirementId',
                array()
            )
            ->where(array('tblstatus.name' => 'active'))
            ->where(array('tblpaymentrequirement.name' => 'free'))
            ->order('publishDate DESC');

        $results = $this->tableGateway->selectWith($select);

        if (!$results->count() || is_null($results)) {
            $results = new ResultSet();
            $results->initialize(array());
        }

        return $results;
    }

    /**
     * Return all videos in reverse order of published date
     *
     * This function was created to use in the video administration backend. There's no
     * restrictions or filters on the videos returned, only an order clause so that the most
     * recent are the easiest to access.
     *
     * @return bool|\Zend\Db\ResultSet\ResultSetInterface
     */
    public function fetchAllVideos()
    {
        $select = $this->tableGateway->getSql()->select();
        $select->order('publishDate DESC');

        $results = $this->tableGateway->selectWith($select);

        if (!$results->count() || is_null($results)) {
            $results = new ResultSet();
            $results->initialize(array());
        }

        $results->buffer();

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
