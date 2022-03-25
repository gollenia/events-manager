<?php

namespace Contexis\Events\Intl;

use NumberFormatter;

class PriceFormatter {
	public float $price;
	public bool $free;
	public string $format;
	public string $currency;
	private NumberFormatter $fmt;
	
	public function __construct($price) {
		$this->price = floatval($price);

		$this->fmt = new NumberFormatter(
			get_locale(), 
			NumberFormatter::CURRENCY
		);

		$this->free = $this->is_free();
		$this->format = $this->get_format();
		$this->currency = $this->get_currency();
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

	public function is_free() {
		return $this->price === 0.0;
	}
}