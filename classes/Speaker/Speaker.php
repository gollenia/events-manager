<?php

namespace Contexis\Events;

class Speaker {

	var int $id = 0;
	var $name = "";
	var $email = "";
	var $gender = "";
	var $phone = "";
	var $image = [];
	var $slug = "";
	var $role = "";
	var $description = "";

	public function __construct(int $id = 0)
	{
		if( !$id ) return;

		$this->id = $id;
		self::get($this);
	}

	public static function get($speaker) {

		if(!$speaker) return new Speaker();

		if(is_int($speaker)) {
			return new Speaker($speaker);
		}

		$args = array(
			'p'         => $speaker->id, // ID of a page, post, or custom type
			'post_type' => 'event-speaker'
		);

		$query = new \WP_Query($args);
		$result = $query->get_posts();

		if(empty($result)) return $speaker;

		$data = $result[0];
		$speaker->image = self::get_image($speaker->id);
		$speaker->name = $data->post_title;
		$speaker->email = get_post_meta($speaker->id,'_email', true);
		$speaker->phone = get_post_meta($speaker->id,'_phone', true);
		$speaker->role = get_post_meta($speaker->id,'_role', true);
		$speaker->gender = get_post_meta($speaker->id,'_gender', true);
		$speaker->slug = $data->post_name;
		return $speaker;
	}

	private static function get_image($id) {

		$thumbnail = get_post_thumbnail_id($id);

		if(!$thumbnail) return false;

		$attachment = [
			'attachment_id' => $thumbnail,
			'sizes' => []
		];
		
		foreach(get_intermediate_image_sizes($thumbnail) as $size) {
			$attachment['sizes'][$size] = array_combine(['url', 'width',  'height', 'resized'], wp_get_attachment_image_src( $thumbnail, $size) );
		}
		
		return $attachment;
		
	}
}