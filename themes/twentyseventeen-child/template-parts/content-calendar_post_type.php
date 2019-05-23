<?php
/**
 * The template part for displaying calendar post type
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->



	<div class="entry-content">
		<?php

			$date = get_post_meta(get_the_ID(), '_calendar_event_value_key', true);
			//print calendar

			class Calendar{
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

				public function show(){
					$output = '<table class="calendar">';
					$output .='<caption>'.$this->date_info['month'].' '.$this->year.'</caption>';
					$output .='<tr>';

					// Display dayes of the week header
					foreach ($this->days_of_week as $day) {
						$output .= '<th class="header">' .$day . '</th>';
					}

					$output .= '</tr><tr>';

					if ($this->day_of_week >0) {
						$output .= '<td colspan="' .$this->day_of_week .'"></td>';
					}
					$current_day = 1;

					while ($current_day <= $this->num_days) {
						if ($this->day_of_week == 7) {
							$this->day_of_week = 0;
							$output .= '</tr><tr>';
						}

						$output .= '<td class="day">' .$current_day .'</td>';

						$current_day++;
						$this->day_of_week++;
					}

					if ($this->day_of_week != 7) {
						$remaining_days = 7 - $this->day_of_week;
						$output .= '<td colspan="' .$remaining_days .'"></td>';
					}

					$output .= '</tr>';
					$output .= '</table>';

					//print calendar table
					echo $output;

				}

				public function markeDay($event_day, $event_month, $event_year){

					$output = '<table class="calendar">';
					$output .='<caption>'.$this->date_info['month'].' '.$this->year.'</caption>';
					$output .='<tr>';

					// Display dayes of the week header
					foreach ($this->days_of_week as $day) {
						$output .= '<th class="header">' .$day . '</th>';
					}

					$output .= '</tr><tr>';

					if ($this->day_of_week >0) {
						$output .= '<td colspan="' .$this->day_of_week .'"></td>';
					}
					$current_day = 1;

					while ($current_day <= $this->num_days) {
						if ($this->day_of_week == 7) {
							$this->day_of_week = 0;
							$output .= '</tr><tr>';
						}
						if ($current_day == $event_day) {
							$output .= '<td class="day event" style="background: #f970f7;">' .$current_day .'</td>';
						}
						else {
							$output .= '<td class="day">' .$current_day .'</td>';
						}

						$current_day++;
						$this->day_of_week++;
					}

					if ($this->day_of_week != 7) {
						$remaining_days = 7 - $this->day_of_week;
						$output .= '<td colspan="' .$remaining_days .'"></td>';
					}

					$output .= '</tr>';
					$output .= '</table>';

					//print calendar table
					echo $output;
			}
		}

			$calendar = new Calendar(5,2019);
			// $calendar->markeDay(12,5,2019);
			//split date
			$parts = explode('.', $date);
			$day = $parts[0];
			$month = $parts[1];
			$year = $parts[2];

			$calendar->markeDay($day,$month,$year);

			// get_calendar(true);


			the_content();


			//printing date from datepicker
			echo $date;

			wp_link_pages(
				array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				)
			);

			if ( '' !== get_the_author_meta( 'description' ) ) {
				get_template_part( 'template-parts/biography' );
			}
			?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php
			edit_post_link(
				sprintf(
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
