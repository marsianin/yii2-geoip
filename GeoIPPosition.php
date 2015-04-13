<?php
namespace yii2\GeoIP;

/**
 * Class GeoIPPosition
 * @package GeoIP
 */
class GeoIPPosition
{
	public $latitude;
	public $longitude;

	public function __construct($latitude, $longitude)
	{
		$this->latitude  = $latitude;
		$this->longitude = $longitude;
	}
}