<?php

namespace Login\View;

class DateTimeView
{
	public function show()
	{
		date_default_timezone_set('Europe/Stockholm');

		$day = date('l');
		$date = date('jS');
		$month = date('F');
		$year = date('Y');
		$time = date('H:i:s', time());
		$timeString = "$day, the $date of $month $year, The time is $time";

		return '<p>' . $timeString . '</p>';
	}
}
