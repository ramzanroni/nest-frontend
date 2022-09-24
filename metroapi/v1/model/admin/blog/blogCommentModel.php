<?php
class BlogCommentException extends Exception
{
}

class BlogComment
{
    private $id;
    private $blog_id;
    private $comment;
    private $ratings;
    private $user_id;
    private $user_name;
    private $status;
    private $create_at;
    private $blog_comment_id;
    private $parent;
    private $title;

    public function __construct($id, $blog_id, $title, $comment, $ratings, $user_id, $user_name, $status, $create_at, $blog_comment_id, $parent)
    {
        $this->setId($id);
        $this->setBlog_id($blog_id);
        $this->setTitle($title);
        $this->setComment($comment);
        $this->setRatings($ratings);
        $this->setUser_id($user_id);
        $this->setUser_name($user_name);
        $this->setCreate_at($create_at);
        $this->setStatus($status);
        $this->setBlog_comment_id($blog_comment_id);
        $this->setParent($parent);
    }

    function setId($id)
    {
        $this->id = $id;
    }
    function getId()
    {
        return $this->id;
    }
    function setBlog_id($blog_id)
    {
        $this->blog_id = $blog_id;
    }
    function getBlog_id()
    {
        return $this->blog_id;
    }
    function setTitle($title)
    {
        $this->title = $title;
    }
    function getTitel()
    {
        return $this->title;
    }
    function setComment($comment)
    {
        $this->comment = $comment;
    }
    function getComment()
    {
        return $this->comment;
    }
    function setRatings($ratings)
    {
        $this->ratings = $ratings;
    }
    function getRatings()
    {
        return $this->ratings;
    }
    function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }
    function getUser_id()
    {
        return $this->user_id;
    }
    function setUser_name($user_name)
    {
        $this->user_name = $user_name;
    }
    function getUser_name()
    {
        return $this->user_name;
    }
    function setStatus($status)
    {
        $this->status = $status;
    }
    function getStatus()
    {
        return $this->status;
    }
    function setCreate_at($create_at)
    {
        $this->create_at = $create_at;
    }
    function getCreate_at()
    {
        return $this->create_at;
    }
    function setBlog_comment_id($blog_comment_id)
    {
        $this->blog_comment_id = $blog_comment_id;
    }
    function getBlog_comment_id()
    {
        return $this->blog_comment_id;
    }
    function setParent($parent)
    {
        $this->parent = $parent;
    }
    function getParent()
    {
        return $this->parent;
    }

    public function returnBlogCommentArray()
    {
        $blogComment = array();
        $blogComment['id'] = $this->getId();
        $blogComment['blog_id'] = $this->getBlog_id();
        $blogComment['title'] = $this->getTitel();
        $blogComment['comment'] = $this->getComment();
        $blogComment['ratings'] = $this->getRatings();
        $blogComment['user_id'] = $this->getUser_id();
        $blogComment['user_name'] = $this->getUser_name();
        $blogComment['status'] = $this->getStatus();
        $blogComment['blog_comment_id'] = $this->getBlog_comment_id();
        $blogComment['parent'] = $this->getParent();

        $blogComment['create_at'] = $this->getCreate_at();
        return $blogComment;
    }
}
