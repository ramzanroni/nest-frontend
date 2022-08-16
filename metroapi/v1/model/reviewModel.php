<?php

class ReviewException extends Exception
{
}
class Review
{
    private $comment;
    private $rating;
    private $productId;

    public function __construct($productId, $comment, $rating)
    {
        $this->setComment($comment);
        $this->setRating($rating);
        $this->setProductId($productId);
    }

    function setProductId($productId)
    {
        if (($productId != null) && (!is_numeric($productId) || $this->productId !== null)) {
            throw new ReviewException("Product id can not be null.");
        }
        $this->productId = $productId;
    }
    function getProductId()
    {
        return $this->productId;
    }
    function setComment($comment)
    {
        $this->comment = $comment;
    }
    function getComment()
    {
        return $this->comment;
    }
    function setRating($rating)
    {

        $this->rating = $rating;
    }
    function getRating()
    {
        return $this->rating;
    }
}
