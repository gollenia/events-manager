<?php

namespace Contexis\Events\Addons;

use DateTime;
use DateInterval;
use EM_Events;
use Timber\Timber;
use Mpdf\{
	Mpdf,
	HTMLParserMode,
	Config\ConfigVariables,
	Config\FontVariables,
};

class ExportApi {

	var $month = [];

	public int $offset;

	var $start_date;
	var $end_date;

	private Mpdf $pdf;

	public static function init() {
		$instance = new self;
		add_action('wp_ajax_nopriv_em_pdf_export',[$instance, 'register_pdf_generator']);
		add_action('wp_ajax_em_pdf_export',[$instance, 'register_pdf_generator']);
	}

	public function register_pdf_generator() {

		if(array_key_exists('offset', $_REQUEST)) {
			$this->offset = intval($_REQUEST['offset']);
		}
		
		list($this->start_date, $this->end_date) = $this->get_date_range($this->offset);
		$this->month = $this->generate_month();
		$this->get_event_data();
		
		$this->pdf = new Mpdf($this->get_pdf_fonts());
		//$this->pdf->WriteHTML($this->get_style(),HTMLParserMode::HEADER_CSS);
		
		$this->pdf->WriteHTML($this->generate_table());
		
		
		//echo '<h1>Monatsprogramm ' . wp_date('F Y', strtotime($this->start_date)) . '</h1>';
		//echo $this->generate_table();
		
		$this->pdf->Output();
		//var_dump($this->month);
		wp_die();
	}

	public function get_event_data() {
		if(empty($this->month)) return;
		$events = EM_Events::get_rest(['scope' => join(",", [$this->start_date, $this->end_date])]);
		foreach($events as $event) {
			array_push($this->month[wp_date('d', $event['start'])]['events'], $event);
		}
	}

	public function get_featured_events() {
		if(!array_key_exists('featured', $_REQUEST)) return [];
		
		return EM_Events::get_rest(['scope' => 'future', 'category' => $_REQUEST['featured']]);
	}

	public function get_pdf_fonts() {
		
		$fontPath = get_stylesheet_directory() . '/plugins/events/pdf';

		if(!file_exists($fontPath)) return;
		
		$defaultConfig = (new ConfigVariables())->getDefaults();
		$fontDirs = $defaultConfig['fontDir'];

		$defaultFontConfig = (new FontVariables())->getDefaults();
		$fontData = $defaultFontConfig['fontdata'];
		return [
			'fontDir' => array_merge($fontDirs, [ $fontPath ]),
			'fontdata' => $fontData + [
				'default' => [
					'R' => 'regular.ttf',
					'I' => 'italic.ttf',
					'B' => 'bold.ttf',
				]
			],
			'default_font' => 'default'
		];
	}

	public function generate_table() {
		
		$templates = [
			get_stylesheet_directory() . '/plugins/events/pdf/template.twig',
			\Events::DIR.'/templates/tables/pdf.twig'
		];

		$args = [
			"month" => $this->month, 
			"featured" => $this->get_featured_events(),
			"start" => $this->start_date
		];

		$result = Timber::compile($templates,$args);
		return $result;
	}

	public function get_date_range($offset = 0) {
		$start = new DateTime();

		if($offset) {			
			$start->add(new DateInterval("P" . $offset . "M"));
		}
		
		$month = date("m",$start->getTimestamp());
		$year = date("Y",$start->getTimestamp());

		return [
			date('Y-m-d', mktime(0, 0, 0, $month, 1, $year)),
			date('Y-m-t', mktime(0, 0, 0, $month, 1, $year))
		];

	}

	public function generate_month() {
		$thisTime = strtotime($this->start_date);
		$endTime = strtotime($this->end_date);
		$month = [];
		while($thisTime <= $endTime)
		{
			$month[date('d', $thisTime)] = [
				'timestamp' => $this->start_date,
				'count' => wp_date('j', $thisTime),
				'name' => wp_date('D', $thisTime),
				'weekday' => wp_date("N", $thisTime),
				'events' => [],
				'is_sunday' => date('N', $thisTime) == 7
			];

			$thisTime = strtotime('+1 day', $thisTime); // increment for loop
		}

		return $month;
	}

}

ExportApi::init();