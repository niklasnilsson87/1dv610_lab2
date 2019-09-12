<?php

class DateTimeView {


	public function show() {
		$day = date('l');
		$date = date('d');
		$month = date('F');
		$year = date('Y');
		ini_set('date.timezone', 'Europe/Stockholm');
		$time = date('H:i:s', time() - date('Z'));
		$timeString = '';
		$timeString .= "$day, the $date" . "th of $month $year, The time is $time";

		return '<p>' . $timeString . '</p>';
	}
}