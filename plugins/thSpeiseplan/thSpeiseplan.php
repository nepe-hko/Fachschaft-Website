<?php
/*
Plugin Name: ThSpeiseplan
Version: 1.0
Author: Daniel Hauk
Description: Zeigt den Speiseplan der TH-Nünberg Fakultät Informatik an
*/

include 'Meal.php';

class SpeiseplanPlugin
{

    private static $instance;

    public static function getInstance()
    {
        if (self::$instance === null) 
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}

    public function toFrontend($atts) 
    {
        wp_enqueue_style('menu', plugins_url( 'css/menu.css', __FILE__ ));
        global $wpdb;
        $meals = array();
        $daystoFetch = $atts['daycount'];
        $noApiDataCount = 0;
        
        for ($i=0; $i < $daystoFetch ; $i++)  # for every Day
        {
            $date = date("Y-m-d", strtotime("+" . $i . " days"));

            # get meals from DB
            $mealsOfDay = $wpdb->get_results("SELECT * FROM `wp_meal` WHERE theDate =  '$date';");
            foreach ($mealsOfDay as $mealFromDB)
            {
                $meal = new Meal();
                $meal   ->setTheDate($mealFromDB->theDate)
                        ->setTitle($mealFromDB->title)
                        ->setPrice($mealFromDB->price)
                        ->setNoPig($mealFromDB->noPig)
                        ->setBeef($mealFromDB->beef)
                        ->setVegetarian($mealFromDB->vegetarian)
                        ->setRating($mealFromDB->rating)
                        ->setRatingCount($mealFromDB->ratingCount);
                array_push($meals, $meal);
            }

            # get meals from API (if they are not in DB)
            if(empty($mealsOfDay))
            { 
                $mealsOfDayXML = $this->fetchFromApi($date);
                if (!empty($mealsOfDayXML)) 
                {
                    foreach ($mealsOfDayXML->Tagesmenue->Mensaessen as $mealXML)
                    {
                        $meal = new Meal();
                        $meal   ->setTheDate($mealsOfDayXML->firstDate)
                                ->setTitle($mealXML->hauptgericht->bezeichnung)
                                ->setPrice(($mealXML->hauptgericht->preisstud) /100 . " €")
                                ->setNoPig($mealXML['moslem'])
                                ->setBeef($mealXML['rind'])
                                ->setVegetarian($mealXML['vegetarisch'])
                                ->setRating($mealXML->hauptgericht->bewertung->schnitt)
                                ->setRatingCount($mealXML->hauptgericht->bewertung->anzahl)
                                ->save();
                        array_push($meals, $meal);
                    }
                }
                
            }

        }
        return  $this->toHTML($meals);
    }

    private function fetchFromApi($date)
    {
        try {
            $req = curl_init();
            curl_setopt($req, CURLOPT_URL, "https://www.sigfood.de/?do=api.gettagesplan&datum=" .  $date);
            curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
            $res = curl_exec($req);
            curl_close($req);
            return new SimpleXMLElement($res);
        } catch (Exception $e) {
            return array();
        }

    }

    public function toHTML($meals)
    {
        if (empty($meals))
        {
            return "Vorübergehend nicht verfügbar";
        }
        # insert table header
        $html = "<table class='menu'><tr class='header'>
            <td>Titel</td>
            <td>Preis</td>
            <td>Kategorie</td>
            <td>Bewertung (Anzahl)</td>
        </tr>";

        $lastDate = 0;
        foreach($meals as $meal)
        {
            # add date to html
            $date = $meal->getTheDate();
            if($lastDate !== $date)
            {
                $html .= "<tr><td colspan=\"100%\" class='date'>" . $date . "</td></tr>";
            }
            $lastDate = $date;

            # create html for icons
            $categorysHtml = "";
            $categorysHtml .= $meal->getNoPig() == "true"   ?  "<img src=\"" . plugins_url( 'img/iconnopig.png', __FILE__ ) . "\">"     : "";
            $categorysHtml .= $meal->getBeef() == "true"    ? "<img src=\"" . plugins_url( 'img/iconrind.png', __FILE__ ) . "\">"       : "";
            $categorysHtml .= $meal->getVegetarian() == "true" ? "<img src=\"" . plugins_url( 'img/iconveg.png', __FILE__ ) . "\">"     : "";

            # create html for rating stars
            $ratingHtml = "";
            $starCount = round($meal->getRating());
            for($i=0; $i<$starCount; $i++)
            {
                $ratingHtml .= "<img src=\"" . plugins_url( 'img/star.png', __FILE__ ) . "\">";
            }
            $ratingHtml .= " (" . $meal->getRatingCount() . ")";
            
            # add meal to html
            $html .= "<tr>
            <td>" . $meal->getTitle() . "</td>
            <td>" . $meal->getPrice() . "</td>
            <td>" . $categorysHtml ."</td>
            <td>" . $ratingHtml . "</td>
            </tr>";
        }
        return $html . "</table>";
    }

    public function onActivation()
    {
        # create DB table
        global $wpdb;
        $meal = $wpdb->prefix . "meal";
        $charsetCollate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $meal (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            theDate text,
            title text,
            price text,
            noPig int,
            beef int,
            vegetarian int,
            rating float,
            ratingCount int,
            PRIMARY KEY  (id)
        ) $charsetCollate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    public function onDeactivation()
    {
        # drop DB table
        global $wpdb;
        $meal = $wpdb->prefix . "meal";
        $wpdb->query("DROP TABLE IF EXISTS $meal");
    }

}

$speiseplanPlugin = SpeiseplanPlugin::getInstance();
add_shortcode('speiseplan', array($speiseplanPlugin, 'toFrontend'));
register_activation_hook( __FILE__, array('SpeiseplanPlugin', 'onActivation'));
register_deactivation_hook(__FILE__, array('SpeiseplanPlugin', 'onDeactivation'))

?>