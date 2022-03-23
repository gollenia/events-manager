<?php

class EM_DateFormat {

	var $start;
	var $end;
	var \IntlDateFormatter $formatter;
	
	public function __construct(int $start, int $end = 0) {
		$this->start = $start;
		$this->end = $end;
		$this->formatter = new \IntlDateFormatter(
			get_locale(), 
			\IntlDateFormatter::LONG, 
			\IntlDateFormatter::NONE
		);
	}
	
	/**
	 * Returns a Intl formatted date range or single date
	 *
	 * @param integer $start TimeStamp
	 * @param integer $end TimeStamp
	 * @return string
	 */
	public static function get_format(int $start, int $end = 0) {
		$instance = new self($start, $end);
		
		if($instance->is_same_day()) {
			return $instance->formatter->format($start);
		}
		return $instance->date_range();
	}

	/**
	 * Cuts of Year and month of the start date if they are the same as the end date
	 *
	 * @return string
	 */
	public function date_range() {

		$result = [__('to', 'events-manager'), $this->formatter->format($this->end)];

		$remove_letters = array_merge(
			$this->is_same_year() ? ['y', 'Y'] : [],
			$this->is_same_month() ? ['m', 'M'] : []
		);

		$this->formatter->setPattern(
			trim(str_replace($remove_letters, '', $this->formatter->getPattern()))
		);

		array_unshift($result, $this->formatter->format($this->start));
		return join(" ", $result);
	}

	/**
	 * Determains if the two dates are on the same day
	 *
	 * @return boolean
	 */
	public function is_same_day() {
		return wp_date('jY', $this->start) === wp_date('jY', $this->end);
	}

	/**
	 * Determains if the two dates are in the same month
	 *
	 * @return boolean
	 */
	public function is_same_month() {
		return wp_date('n', $this->start) === wp_date('n', $this->end);
	}

	/**
	 * Determains if the two dates are in the same year
	 *
	 * @return boolean
	 */
	public function is_same_year() {
		return wp_date('Y', $this->start) === wp_date('Y', $this->end);
	}
}