<?php

namespace Contexis\Events\Intl;

use IntlDateFormatter;

class Date {

	var $start;
	var $end;
	var IntlDateFormatter $fmt;
	
	public function __construct(int $start, int $end = 0, $is_time = false) {
		$this->start = $start;
		$this->end = $end;

		if($is_time) {
			$this->fmt = new IntlDateFormatter(
				get_locale(), 
				IntlDateFormatter::NONE, 
				IntlDateFormatter::SHORT,
				wp_timezone()
			);
			return;
		}

		$this->fmt = new IntlDateFormatter(
			get_locale(), 
			IntlDateFormatter::LONG, 
			IntlDateFormatter::NONE,
			wp_timezone()
		);
	}
	
	/**
	 * Returns a Intl formatted date range or single date
	 *
	 * @param integer $start TimeStamp
	 * @param integer $end TimeStamp
	 * @return string
	 */
	public static function get_date(int $start, int $end = 0) {
		$instance = new self($start, $end);
		
		if($instance->is_same_day() || $end == 0) {
			return $instance->fmt->format($start);
		}
		return $instance->date_range();
	}

	public static function get_time(int $start, int $end = 0) {
		$instance = new self($start, $end, true);

		if($end == 0) {
			return $instance->fmt->format($start);
		}
		
		if($instance->is_same_day()) {
			return $instance->fmt->format($start) . " " . __('to', 'events-manager') . " " . $instance->fmt->format($end) . " " . __("o'clock", 'events-manager');
		}

		return __('Begins at:', 'events-manager') . ' ' . $instance->fmt->format($start) . '<br>' . __('Ends at:', 'events-manager') . ' ' . $instance->fmt->format($end);
	}

	/**
	 * Cuts of Year and month of the start date if they are the same as the end date
	 *
	 * @return string
	 */
	public function date_range() {

		$result = [__('to', 'events-manager'), $this->fmt->format($this->end)];

		$remove_letters = array_merge(
			$this->is_same_year() ? ['y', 'Y'] : [],
			$this->is_same_month() ? ['m', 'M'] : []
		);

		$this->fmt->setPattern(
			trim(str_replace($remove_letters, '', $this->fmt->getPattern()))
		);

		array_unshift($result, $this->fmt->format($this->start));
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

	public static function weekdays() {
		return [
			__('Mon', 'events-manager'),
			__('Tue', 'events-manager'),
			__('Wed', 'events-manager'),
			__('Thu', 'events-manager'),
			__('Fri', 'events-manager'),
			__('Sat', 'events-manager'),
			__('Sun', 'events-manager')
		];
	}
}