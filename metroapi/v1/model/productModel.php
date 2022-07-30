<?php
class ProductException extends Exception
{
}
class Product
{
    private $_stockid;
    private $_description;
    private $_category_id;
    private $_category;
    private $_longdescription;
    private $_units;
    private $_discountcategory;
    private $_taxcatid;
    private $_webprice;
    private $_img;
    private $_status;
    private $_multiImg;

    public function __construct($stockid, $description, $category_id, $category, $longdescription, $units, $discountcategory, $taxcatid, $webprice, $img, $multiImg)
    {
        $this->setStockid($stockid);
        $this->setDescription($description);
        $this->setCategoryID($category_id);
        $this->setCategory($category);
        $this->setLongdescription($longdescription);
        $this->setUnits($units);
        $this->setDiscountcategory($discountcategory);
        $this->setTaxcatid($taxcatid);
        $this->setWebprice($webprice);
        $this->setImg($img);
        $this->setMultiImg($multiImg);
    }


    public function getStockid()
    {
        return $this->_stockid;
    }
    public function getDescription()
    {
        return $this->_description;
    }
    public function getLongdescription()
    {
        return $this->_longdescription;
    }
    public function getUnits()
    {
        return $this->_units;
    }
    public function getDiscountcategory()
    {
        return $this->_discountcategory;
    }
    public function getCategoryID()
    {
        return $this->_category_id;
    }
    public function getWebprice()
    {
        return $this->_webprice;
    }
    public function getTaxcatid()
    {
        return $this->_taxcatid;
    }
    public function getImg()
    {
        return $this->_img;
    }
    // public function getStatus()
    // {
    //     return $this->_status;
    // }
    public function getCategory()
    {
        return $this->_category;
    }
    public function getMultiImg()
    {
        return $this->_multiImg;
    }

    public function setStockid($stockid)
    {
        if (($stockid != null) && (!is_numeric($stockid) || $stockid <= 0 || $this->_stockid !== null)) {
            throw new ProductException("Stock ID Error");
        }
        $this->_stockid = $stockid;
    }

    public function setDescription($description)
    {
        if (strlen($description) == 0 || strlen($description) > 255) {
            throw new ProductException("Description Name Error");
        }
        $this->_description = $description;
    }
    public function setLongdescription($longDescription)
    {
        if (strlen($longDescription) == 0) {
            throw new ProductException("Long Description Error");
        }
        $this->_longdescription = $longDescription;
    }
    public function setCategoryID($categoryID)
    {
        // if ($categoryID == null || !is_numeric($categoryID)) {
        //     throw new ProductException("Category ID Error");
        // }
        $this->_category_id = $categoryID;
    }
    public function setCategory($category)
    {
        if ($category == null) {
            throw new ProductException("Category  Error");
        }
        $this->_category = $category;
    }

    public function setUnits($units)
    {
        if (strlen($units) == 0) {
            throw new ProductException("Units value error");
        }
        $this->_units = $units;
    }

    public function setDiscountcategory($discountcategory)
    {
        $this->_discountcategory = $discountcategory;
    }

    public function setTaxcatid($taxcatid)
    {
        if ($taxcatid == null) {
            throw new ProductException("Tax Category ID error");
        }
        $this->_taxcatid = $taxcatid;
    }

    public function setWebprice($webprice)
    {
        if (!is_numeric($webprice)) {
            throw new ProductException("Web price must be numeric");
        }
        $this->_webprice = $webprice;
    }
    public function setImg($img)
    {
        // if (($img !== null) && (strlen($img) == 0 || strlen($img) > 16777215)) {
        //     throw new ProductException("Product Image error");
        // }
        $this->_img = $img;
    }
    // public function setStatus($status)
    // {
    //     if ($status == null) {
    //         throw new ProductException("product status error");
    //     }
    //     $this->_status = $status;
    // }

    public function setMultiImg($multiImg)
    {
        $this->_multiImg = $multiImg;
    }


    public function returnProducrArray()
    {
        $product = array();
        $product['stockid'] = $this->getStockid();
        $product['description'] = $this->getDescription();
        $product['category_id'] = $this->getCategoryID();
        $product['category'] = $this->getCategory();
        $product['longdescription'] = $this->getLongdescription();
        $product['units'] = $this->getUnits();
        $product['discountcategory'] = $this->getDiscountcategory();
        $product['taxcatid'] = $this->getTaxcatid();
        $product['webprice'] = $this->getWebprice();
        $product['img'] = $this->getImg();
        $product['multipleImg'] = $this->getMultiImg();
        // $product['status'] = $this->getStatus();
        return $product;
    }
}
