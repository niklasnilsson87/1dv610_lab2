<?php

namespace Application\View;

class DateTimeView
{
	private static $TIME_ZONE = "Europe/Stockholm";

	public function show(): string
	{
		date_default_timezone_set(self::$TIME_ZONE);

		$day = date('l');
		$date = date('jS');
		$month = date('F');
		$year = date('Y');
		$time = date('H:i:s', time());
		$timeString = "$day, the $date of $month $year, The time is $time";

		return '<p>' . $timeString . '</p>';
	}
}
