<?php

namespace Login\View;

class DateTimeView
{

	/**
	 * TODO: Move "date" variables to fields?
	 */
	public function show()
	{
		$day = date('l');
		$date = date('dS');
		$month = date('F');
		$year = date('Y');
		$time = date('H:i:s', time());
		$timeString = "$day, the $date of $month $year, The time is $time";

		return '<p>' . $timeString . '</p>';
	}
}
