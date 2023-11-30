<?php

namespace Contexis\Events\Blocks;


/**
 * Base class for all blocks
 */
class Block {

	public static function init()
	{
		$instance = new self;
		add_action('init', [$instance, 'register_blocks']);
	}

	function register_blocks()
	{
		
		$blocks = [
			'upcoming',
			'details',
			'details-audience',
			'details-date',
			'details-location',
			'details-price',
			'details-shutdown',
			'details-spaces',
			'details-time',
			'details-speaker',
			'booking'
		];

		foreach ($blocks as $block) {
			register_block_type(__DIR__ . '/../build/blocks/' . $block);
		}
	}
	
	

}

Block::init();


