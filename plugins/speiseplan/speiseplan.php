<?php
/*
Plugin Name: speiseplan
Version: 1.0
Author: Daniel Hauk
Description: Zeigt den Speiseplan der TH-Nünberg Fakultät Informatik an
*/
if(!defined('ABSPATH')) {
    die;
}

class SpeiseplanPlugin extends WP_Widget
{

    private static $instance;

    public static function getInstance(){
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        parent::__construct(
            'SpeiseplanPlugin', __('Speiseplan', 'SpeiseplanPlugin'),
            array('description' => __('Zeigt den Speiseplan der TH-Nürnberg an', 'SpeiseplanPlugin'),)
        );
    }

    public function toFrontend($atts = []) 
    {
        $showDays = $atts['days'];
        $dayCount = 0;
        $meals = $this->getMeals();
        $html = "<table class=\"speiseplan\">";
        $date = "";
        $lastDate = "";
        foreach ($meals->meals as $meal) {

            if ($dayCount >= $showDays) break;

            $translate = array(
                'Mon'       => 'Montag',
                'Tue'       => 'Dienstag',
                'Wed'       => 'Mittwoch',
                'Thu'       => 'Donnerstag',
                'Fri'       => 'Freitag',
                'Sat'       => 'Samstag',
                'Sun'       => 'Sonntag',
            );
            $date = $meal->date;
            $timestamp = strtotime($date);
            $dayofWeek = strtr(date("D",$timestamp), $translate);
            $day = date("d", $timestamp);
            $month = date("m", $timestamp);

            if($date !== $lastDate) {
                $html .= "<tr class=\"date\"><td colspan=\"2\">$dayofWeek&nbsp;&nbsp;-&nbsp;&nbsp; $day.$month</td></tr><tr class=\"whitespace\"><td colspan=\"2\"></td></tr>";
                $dayCount++;
            }
            $html .= "<tr><td class=\"title\">" . $meal->title . "</td>";
            $html .= "<td class=\"price\">" . $meal->prices[0] . "</td></tr>";
            $lastDate = $date;
        }
        return $html . "</table>";
    }


    private function getMeals()
    {
        global $wpdb;
        $date = date("Y-m-d");
        $tableName = $wpdb->prefix . 'meal';
        $meals = $wpdb->get_row("SELECT meals FROM `$tableName` WHERE lastUpdate =  '$date';");

        if ($meals) { // meals are in DB
            return json_decode($meals->meals);

        } else { // meals are not in DB -> fetch from API and store in DB
        
            $url = "https://api.fachschaft.in/scrapi/meals/nbg-hohfederstrasse.json";
            try {
                $req = curl_init();
                curl_setopt($req, CURLOPT_URL, $url);
                curl_setopt($req, CURLOPT_RETURNTRANSFER, 1);
                $res = curl_exec($req);
                curl_close($req);
                $meals = $res;
            } catch (Exception $e) {
                return array();
            }
            //save to DB
            $wpdb->insert($tableName, array('meals' => $meals, 'lastUpdate' => $date));
            return json_decode($meals);
        }

    }


    /* WIDGET STUFF */

    public function loadWidget()
    {
        register_widget($this);
        wp_enqueue_style('speiseplan', plugins_url( 'public/css/speiseplan.css', __FILE__ ));
    }

    # create widget front-end
    public function widget( $args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        if (!empty($title))
        {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Speiseplan abrufen und ausgeben

        $json = $this->getMeals();
        
        $html = "<table class=\"speiseplanWidget\">";
        $meals = $json->meals;
        if(!$meals) {
            $html .= "Nicht erreichbar!";
        } else {
            $date = date("Y-m-d");
            $count = 0;
            foreach ($meals as $meal) {
                if($meal->date == $date) {
                    $count++;
                    $html .= "<tr><td class=\"title\">" . $meal->title . "</td>";
                    $html .= "<td class=\"price\">" . $meal->prices[0] . "</td></tr>";
                }
            }
            if($count == 0){
                $html .= "Cafeteria heute geschlossen!";
            }
        }

        $html .= "</table>";

        echo $html;
        echo $args['after_widget'];
    }

    # create widget back-end
    public function form($instance)
    {
        if (isset($instance['title']))
        {
            $title = $instance['title'];
        } 
        else {
            $title = 'Speiseplan';
        }
        ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
        <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

    # update widget
    public function update( $new_instance, $old_instance )
    {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }

    public function onActivation()
    {
        # create DB table
        global $wpdb;
        $tableName = $wpdb->prefix . "meal";
        $charsetCollate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $tableName (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            meals text,
            lastUpdate text,
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
add_action('widgets_init', array($speiseplanPlugin, 'loadWidget'));
register_activation_hook( __FILE__, array($speiseplanPlugin, 'onActivation'));
register_deactivation_hook(__FILE__, array($speiseplanPlugin, 'onDeactivation'));
?>