<?php

class poko_utils_ListData {
	public function __construct(){}
	static function getDays($reverse) {
		if($reverse === null) {
			$reverse = true;
		}
		$data = new HList();
		{
			$_g1 = 1; $_g = 32;
			while($_g1 < $_g) {
				$i = $_g1++;
				$n = $i;
				$data->add(_hx_anonymous(array("key" => Std::string($n), "value" => Std::string($n))));
				unset($n,$i);
			}
		}
		return ($data);
	}
	static $months_short;
	static $months;
	static function getMonths($short) {
		if($short === null) {
			$short = false;
		}
		return ($short ? poko_utils_ListData::arrayToList(poko_utils_ListData::$months_short, 1) : poko_utils_ListData::arrayToList(poko_utils_ListData::$months, 1));
	}
	static function getYears($from, $to, $reverse) {
		if($reverse === null) {
			$reverse = true;
		}
		$data = new HList();
		if($reverse) {
			{
				$_g1 = 0; $_g = ($to - $from + 1);
				while($_g1 < $_g) {
					$i = $_g1++;
					$n = $to - $i;
					$data->add(_hx_anonymous(array("key" => Std::string($n), "value" => Std::string($n))));
					unset($n,$i);
				}
			}
		}
		else {
			{
				$_g12 = 0; $_g2 = ($to - $from + 1);
				while($_g12 < $_g2) {
					$i2 = $_g12++;
					$n2 = $from + $i2;
					$data->add(_hx_anonymous(array("key" => Std::string($n2), "value" => Std::string($n2))));
					unset($n2,$i2);
				}
			}
		}
		return ($data);
	}
	static function hashToList($hash, $startCounter) {
		if($startCounter === null) {
			$startCounter = 0;
		}
		$data = new HList();
		$»it = $hash->keys();
		while($»it->hasNext()) {
		$key = $»it->next();
		{
			$data->add(_hx_anonymous(array("key" => $key, "value" => $hash->get($key))));
			;
		}
		}
		return $data;
	}
	static function arrayToList($array, $startCounter) {
		if($startCounter === null) {
			$startCounter = 0;
		}
		$data = new HList();
		$c = $startCounter;
		{
			$_g = 0;
			while($_g < $array->length) {
				$i = $array[$_g];
				++$_g;
				$data->add(_hx_anonymous(array("key" => $i, "value" => $c)));
				$c++;
				unset($i);
			}
		}
		return $data;
	}
	static function flatArraytoList($array) {
		$data = new HList();
		{
			$_g = 0;
			while($_g < $array->length) {
				$i = $array[$_g];
				++$_g;
				$data->add(_hx_anonymous(array("key" => $i, "value" => $i)));
				unset($i);
			}
		}
		return $data;
	}
	function __toString() { return 'poko.utils.ListData'; }
}
poko_utils_ListData::$months_short = new _hx_array(array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"));
poko_utils_ListData::$months = new _hx_array(array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"));
