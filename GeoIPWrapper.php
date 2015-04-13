<?php

namespace yii2\GeoIP;

/**
 * Class GeoIPWrapper
 * @package GeoIP
 */
class GeoIPWrapper
{
    private static $_instance;
    private $_ip;
    public $defaultIp = '5.5.5.5';

    /**
     * @return GeoIPWrapper
     * @throws Exception
     */
    public static function getInstance()
    {
    	if (!extension_loaded('geoip'))
    		throw new \Exception('GeoIP extension not installed', '500');

        if (!isset(self::$_instance))
            self::$_instance = new self();

        return self::$_instance;
    }

    /**
     *
     */
    public function __construct()
	{
		$this->setIp(self::getRealIp());
	}

    /**
     * @return mixed
     */
    public static function getRealIp()
	{
		if (!empty($_SERVER['HTTP_CLIENT_IP']))
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else
			$ip = $_SERVER['REMOTE_ADDR'];
		
		return $ip;	
	}

    /**
     * @param $ip
     * @return $this
     */
    public function setIp($ip)
	{
		if (strpos($ip, '127.') !== false || strpos($ip, '10.0') !== false)
			$ip = $this->defaultIp;
			
		$this->_ip = $ip;
		return $this;
	}

    /**
     * @return mixed
     */
    public function getIp()
	{
		return $this->_ip;
	}

    /**
     * @return bool|GeoIPPosition
     */
    public function getPosition()
	{
		$data = @geoip_record_by_name($this->_ip);
		if (!$data || !is_array($data) || !array_key_exists('latitude', $data) || !array_key_exists('longitude', $data))
			return false;

		return new GeoIPPosition($data['latitude'], $data['longitude']);
	}
}