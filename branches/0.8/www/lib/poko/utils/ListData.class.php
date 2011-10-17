<?php

class poko_utils_ListData {
	public function __construct(){}
	static function getDateElement($low, $high, $labels) {
		$data = new HList();
		if($labels !== null) {
			{
				$_g1 = $low; $_g = $high + 1;
				while($_g1 < $_g) {
					$i = $_g1++;
					$data->add(_hx_anonymous(array("key" => Std::string($i), "value" => $labels[$i - 1])));
					unset($i);
				}
				unset($_g1,$_g);
			}
			;
		}
		else {
			{
				$_g1 = $low; $_g = $high + 1;
				while($_g1 < $_g) {
					$i = $_g1++;
					$n = Std::string($i);
					$data->add(_hx_anonymous(array("key" => $n, "value" => (poko_utils_ListData_0($_g, $_g1, $data, $high, $i, $labels, $low, $n)))));
					unset($n,$i);
				}
				unset($_g1,$_g);
			}
			;
		}
		return $data;
		unset($data);
	}
	static function getDays($reverse) {
		if($reverse === null) {
			$reverse = true;
			;
		}
		$data = new HList();
		{
			$_g1 = 1; $_g = 32;
			while($_g1 < $_g) {
				$i = $_g1++;
				$n = Std::string($i);
				$data->add(_hx_anonymous(array("key" => $n, "value" => $n)));
				unset($n,$i);
			}
			unset($_g1,$_g);
		}
		return ($data);
		unset($data);
	}
	static $months_short;
	static $months;
	static function getMonths($short) {
		if($short === null) {
			$short = false;
			;
		}
		return poko_utils_ListData_1($short);
		;
	}
	static function getYears($from, $to, $reverse) {
		if($reverse === null) {
			$reverse = true;
			;
		}
		$data = new HList();
		if($reverse) {
			{
				$_g1 = 0; $_g = (($to - $from) + 1);
				while($_g1 < $_g) {
					$i = $_g1++;
					$n = $to - $i;
					$data->add(_hx_anonymous(array("key" => Std::string($n), "value" => Std::string($n))));
					unset($n,$i);
				}
				unset($_g1,$_g);
			}
			;
		}
		else {
			{
				$_g1 = 0; $_g = (($to - $from) + 1);
				while($_g1 < $_g) {
					$i = $_g1++;
					$n = $from + $i;
					$data->add(_hx_anonymous(array("key" => Std::string($n), "value" => Std::string($n))));
					unset($n,$i);
				}
				unset($_g1,$_g);
			}
			;
		}
		return ($data);
		unset($data);
	}
	static function hashToList($hash, $startCounter) {
		if($startCounter === null) {
			$startCounter = 0;
			;
		}
		$data = new HList();
		if(null == $hash) throw new HException('null iterable');
		$»it = $hash->keys();
		while($»it->hasNext()) {
		$key = $»it->next();
		{
			$data->add(_hx_anonymous(array("key" => $key, "value" => $hash->get($key))));
			;
		}
		}
		return $data;
		unset($data);
	}
	static function arrayToList($array, $startCounter) {
		if($startCounter === null) {
			$startCounter = 0;
			;
		}
		$data = new HList();
		$c = $startCounter;
		{
			$_g = 0;
			while($_g < $array->length) {
				$v = $array[$_g];
				++$_g;
				$data->add(_hx_anonymous(array("key" => $c, "value" => $v)));
				$c++;
				unset($v);
			}
			unset($_g);
		}
		return $data;
		unset($data,$c);
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
			unset($_g);
		}
		return $data;
		unset($data);
	}
	function __toString() { return 'poko.utils.ListData'; }
}
poko_utils_ListData::$months_short = new _hx_array(array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"));
poko_utils_ListData::$months = new _hx_array(array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"));
;
function poko_utils_ListData_0(&$_g, &$_g1, &$data, &$high, &$i, &$labels, &$low, &$n) {
if($i < 10) {
	return "0" . $n;
	;
}
else {
	return $n;
	;
}
}
function poko_utils_ListData_1(&$short) {
if($short) {
	return poko_utils_ListData::arrayToList(poko_utils_ListData::$months_short, 1);
	;
}
else {
	return poko_utils_ListData::arrayToList(poko_utils_ListData::$months, 1);
	;
}
}