<?php
class CartonStatusException extends Exception
{
}
class CartonStatus
{
    private $id;
    private $cid;
    private $sid;
    private $uid;
    private $note;
    private $create_at;
    public function __construct($id, $cid, $sid, $uid, $note, $create_at)
    {
        $this->setId($id);
        $this->setCid($cid);
        $this->setSid($sid);
        $this->setUid($uid);
        $this->setNote($note);
        $this->setCreate_at($create_at);
    }
    function setId($id)
    {

        $this->id = $id;
    }
    function getId()
    {
        return $this->id;
    }
    function setCid($cid)
    {
        if (($cid != null) && (!is_numeric($cid) || $cid <= 0 || $this->_cid !== null)) {
            throw new CartonStatusException("CID Error");
        }
        $this->cid = $cid;
    }
    function getCid()
    {
        return $this->cid;
    }
    function setSid($sid)
    {
        if ($sid == '' || $sid <= 0 || $sid > 4) {
            throw new CartonStatusException("sid Error");
        }
        $this->sid = $sid;
    }
    function getSid()
    {
        return $this->sid;
    }
    function setUid($uid)
    {
        if (($uid != null) && (!is_numeric($uid) || $uid <= 0 || $this->_uid !== null)) {
            throw new CartonStatusException("uid Error");
        }
        $this->uid = $uid;
    }
    function getUid()
    {
        return $this->uid;
    }
    function setNote($note)
    {
        $this->note = $note;
    }
    function getNote()
    {
        return $this->note;
    }
    function setCreate_at($create_at)
    {
        $this->create_at = $create_at;
    }
    function getCreate_at()
    {
        return $this->create_at;
    }
    public function returnCartonStatusArray()
    {
        $cartonStatus = array();
        $cartonStatus['id'] = $this->getId();
        $cartonStatus['cid'] = $this->getCid();
        $cartonStatus['sid'] = $this->getSid();
        $cartonStatus['uid'] = $this->getUid();
        $cartonStatus['note'] = $this->getNote();
        $cartonStatus['create_at'] = $this->getCreate_at();
        return $cartonStatus;
    }
}
