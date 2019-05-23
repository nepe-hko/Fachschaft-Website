<?php
class Meal {
    
    private $theDate;
    private $title;
    private $price;
    private $noPig;
    private $beef;
    private $vegetarian;
    private $rating;
    private $ratingCount;
    private $tableName;

    public function __construct()
    {
        global $wpdb;
        $this->tableName = $wpdb->prefix . 'meal';
    }

    public function save()
    {
        global $wpdb;
        $wpdb->insert(
            $this->tableName,
            array(
                'theDate' => $this->theDate,
                'title' => $this->title,
                'price' => $this->price,
                'noPig' => $this->noPig,
                'beef' => $this->beef,
                'vegetarian' => $this->vegetarian,
                'rating' => $this->rating,
                'ratingCount' => $this->ratingCount,
            )
        );
        $wpdb->show_errors();
    }

    public function getRatingCount()
    {
        return $this->ratingCount;
    }

    public function setRatingCount($ratingCount)
    {
        $this->ratingCount = $ratingCount;
        return $this;
    }

    public function getRating()
    {
        return $this->rating;
    }

    public function setRating($rating)
    {
        $this->rating = $rating;
        return $this;
    }

    public function getVegetarian()
    {
        return $this->vegetarian;
    }

    public function setVegetarian($vegetarian)
    {
        $this->vegetarian = $vegetarian;
        return $this;
    }

    public function getBeef()
    {
        return $this->beef;
    }

    public function setBeef($beef)
    {
        $this->beef = $beef;
        return $this;
    }

    public function getNoPig()
    {
        return $this->noPig;
    }

    public function setNoPig($noPig)
    {
        $this->noPig = $noPig;
        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getTheDate()
    {
        return $this->theDate;
    }

    public function setTheDate($theDate)
    {
        $this->theDate = $theDate;
        return $this;
    }
}
?>