<?php

class CreateCalendar{

  private $month;
  private $year;
  private $day_of_week;
  private $num_days;
  private $date_info;
  private $days_of_week;

  public function __construct($month, $year, $days_of_week= array('Mo', 'Di', 'Mi', 'Do', 'Fr', 'Sa', 'So'))
    {
      $this->month = $month;
      $this->year = $year;
      $this->days_of_week = $days_of_week;
      $this->num_days = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
      $this->date_info = getdate(strtotime('first day of', mktime(0,0,0, $this->month, 1, $this->year)));
      $this->day_of_week = $this->date_info['wday']-1;
    }


  public function markeDay($events){

    $event_days = array();
    $event_months = array();
    $event_years = array();

    foreach ($events as $date) {
      $parts = explode('.', $date[0]);
      array_push($event_days, $parts[0]);
      array_push($event_months, $parts[1]);
      array_push($event_years, $parts[2]);
    }
    $time = current_time( 'mysql' );
    list( $current_year, $current_month, $current_day, $hour, $minute, $second ) = preg_split( '([^0-9])', $time );

    $output = '<div class="fachschaft_calendar_plugin_calendar_table">';
    $output .= '<h1>'.__('Anstehende Veranstaltungen') .'</h1>';
    $output .= '<table class="calendar">';
    $current_month_name = date_i18n( 'F', false, false);
    $output .='<caption>'.$current_month_name.' '.$current_year.'</caption>';
    $output .='<tr>';

    // Display dayes of the week header
    foreach ($this->days_of_week as $day) {
      $output .= '<th class="header">' .$day . '</th>';
    }

    $output .= '</tr><tr>';

    if ($this->day_of_week >0) {
      $output .= '<td colspan="' .$this->day_of_week .'"></td>';
    }
    $current_calendar_day = 1;
    $counter =0;

    while ($current_calendar_day <= $this->num_days) {
      if ($this->day_of_week == 7) {
        $this->day_of_week = 0;
        $output .= '</tr><tr>';
      }

      if ($current_calendar_day == $event_days[$counter] && $current_month == $event_months[$counter]  && $current_year == $event_years[$counter]) {

        if ($current_calendar_day < 10) {
          if ($events[$counter][1] < $current_timestamp = time()) {
          $output .= '<td class="day event past" id="0' .$current_calendar_day .$event_months[$counter] .$event_years[$counter].'">' .$current_calendar_day .'</td>';
          }
          else {
            $output .= '<td class="day event" id="0' .$current_calendar_day .$event_months[$counter] .$event_years[$counter].'">' .$current_calendar_day .'</td>';
          }
        }
        else {
          if ($events[$counter][1] < $current_timestamp = time()) {
            $output .= '<td class="day event past" id="' .$current_calendar_day .$event_months[$counter] .$event_years[$counter].'">' .$current_calendar_day .'</td>';
          }
          else {
            $output .= '<td class="day event" id="' .$current_calendar_day .$event_months[$counter] .$event_years[$counter].'">' .$current_calendar_day .'</td>';
          }
        }

        $counter++;
      }
      else {
        $output .= '<td class="day">' .$current_calendar_day .'</td>';
      }

      $current_calendar_day++;
      $this->day_of_week++;
    }

    if ($this->day_of_week != 7) {
      $remaining_days = 7 - $this->day_of_week;
      $output .= '<td colspan="' .$remaining_days .'"></td>';
    }

    $output .= '</tr>';
    $output .= '</table>';
    $output .= '</div>';

    ?>

    <?php

    //print calendar table
    return $output;
}
}
