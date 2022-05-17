<?php

namespace Contexis\Events\Intl;

use NumberFormatter;

class PriceFormatter {
	public float $price;
	public bool $free;
	public string $format;
	public string $currency;
	private NumberFormatter $fmt;
	
	/**
	 * Construct a price object
	 *
	 * @param [type] $price
	 */
	public function __construct($price) {
		$this->price = floatval($price);

		$this->fmt = new NumberFormatter(
			get_locale(), 
			NumberFormatter::CURRENCY
		);

		$this->fmt->setTextAttribute(NumberFormatter::CURRENCY_CODE, get_option('dbem_bookings_currency'));

		//$this->fmt->setAttribute(NumberFormatter::CURRENCY_CODE, get_option('dbem_bookings_currency'));

		$this->free = $this->is_free();
		$this->format = $this->get_format();
		$this->currency = get_option('dbem_bookings_currency');
	}
	
	/**
	 * Returns a Intl formatted date range or single date
	 *
	 * @param integer $start TimeStamp
	 * @param integer $end TimeStamp
	 * @return string
	 */
	public function get_format() {
		return $this->fmt->format($this->price);
	}

	public function get_currency() {
		return $this->fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
	}

	public function get_currency_code() {
		return $this->fmt->getTextAttribute(NumberFormatter::CURRENCY_CODE);
	}

	public function is_free() {
		return $this->price === 0.0;
	}

	public static function format($price) {
		$price = new PriceFormatter($price);
		return $price->format;
	}
}