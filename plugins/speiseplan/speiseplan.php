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

    public static function get_instance(){
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        parent::__construct(
            'SpeiseplanPlugin', 
            __( 'Speiseplan', 'speiseplan'),
            array('description' => __( 'Zeigt den Speiseplan der TH-Nürnberg an', 'speiseplan'))
        );
        add_shortcode('speiseplan', array($this, 'print'));
        add_action('widgets_init', array($this, 'loadWidget'));
        register_activation_hook( __FILE__, array($this, 'on_activation'));
        register_deactivation_hook(__FILE__, array($this, 'on_deactivation'));
    }

    // callback function for shortcut, outputs meals for the next $atts['days'] days
    public function print($atts = []) 
    {
        $atts = shortcode_atts( array('days' => 5), $atts, 'speiseplan');
        $dayCount = 0;
        $meals = $this->getMeals();
        $html = "<table class=\"speiseplan\">";
        $date = "";
        $lastDate = "";
        $translate = array(
            'Mon'       => __( 'Montag', 'speiseplan'),
            'Tue'       => __( 'Dienstag', 'speiseplan'),
            'Wed'       => __( 'Mittwoch', 'speiseplan'),
            'Thu'       => __( 'Donnerstag', 'speiseplan'),
            'Fri'       => __( 'Freitag', 'speiseplan'),
            'Sat'       => __( 'Samstag', 'speiseplan'),
            'Sun'       => __( 'Sonntag', 'speiseplan'),
        );

        foreach ($meals->meals as $meal) {

            $date = $meal->date;
            $sameday = false;
            if($date == $lastDate) {
                $sameday = true;
            }

            // maximum displayed meals reached
            if ($dayCount >= $atts['days'] && !$sameday)  {
                break;
            }
            // date is in the past
            if ($date < date("Y-m-d")) {
                continue;
            }

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

    // returns all meals
    private function getMeals()
    {
        // get meals from DBN
        global $wpdb;
        $date = date("Y-m-d");
        $tableName = $wpdb->prefix . 'meal';
        $meals = $wpdb->get_row("SELECT meals FROM `$tableName` WHERE lastUpdate =  '$date';");

        if ($meals) { // meals are in DB
            return json_decode($meals->meals);

        } else { // meals are not in DB -> fetch from API
        
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
            //delete old entrys and save new to DB
            $wpdb->query("TRUNCATE TABLE $tableName");
            $wpdb->insert($tableName, array('meals' => $meals, 'lastUpdate' => $date));
            return json_decode($meals);
        }

    }


    /**************** WIDGET *******************/

    public function loadWidget()
    {
        register_widget($this);
        wp_enqueue_style('speiseplan', plugins_url( 'public/css/speiseplan.css', __FILE__ ));
    }

    // create widget front-end
    public function widget( $args, $instance)
    {
        $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        if (!empty($title))
        {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // retrieve meals and print meals from today
        $json = $this->getMeals();
        
        $html = "<table class='speiseplan-widget'>";
        $meals = $json->meals;
        if(!$meals) {
            $html .= __( 'Nicht erreichbar!', 'speiseplan');
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
                $html .= __( 'Cafeteria heute geschlossen!', 'speiseplan');
            }
        }

        $html .= "</table>";

        echo $html;
        echo $args['after_widget'];
    }

    // create widget back-end
    public function form($instance)
    {
        if (isset($instance['title']))
        {
            $title = $instance['title'];
        } 
        else {
            $title = __( 'Speiseplan', 'speiseplan');
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'speiseplan' ); ?>:</label> 
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" 
                name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <?php
    }

    public function update( $new_instance, $old_instance )
    {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }

    public function on_activation()
    {
        // create DB table
        global $wpdb;
        $tableName = $wpdb->prefix . "meal";
        $charsetCollate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $tableName (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            meals longtext,
            lastUpdate text,
            PRIMARY KEY  (id)
        ) $charsetCollate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    public function on_deactivation()
    {
        // drop DB table
        global $wpdb;
        $meal = $wpdb->prefix . "meal";
        $wpdb->query("DROP TABLE IF EXISTS $meal");
    }

}

$speiseplanPlugin = SpeiseplanPlugin::get_instance();
?>