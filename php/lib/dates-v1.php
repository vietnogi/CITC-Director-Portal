<?
class Dates{
	public $current = array();
	public $months = array('01' => 'January'
						   , '02' => 'February'
						   , '03' => 'March'
						   , '04' => 'April'
						   , '05' => 'May'
						   , '06' => 'June'
						   , '07' => 'July'
						   , '08' => 'August'
						   , '09' => 'September'
						   , '10' => 'October'
						   , '11' => 'November'
						   , '12' => 'December'
						   );
	public $weekdays = array ('Sunday'
							  , 'Monday'
							  , 'Tuesday'
							  , 'Wednesday'
							  , 'Thursday'
							  , 'Friday'
							  , 'Saturday'
							  );
	public $hours12 = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
	
	public function __construct() {
		$parts = explode(' ', date('Y n j'));
		$this->current = array('year' => $parts[0]
							   , 'month' => $parts[1]
							   , 'day' =>  $parts[2]
							   );
	}
	
	public function getCreditCardMonths() {
		return array_keys($this->months);
	}
	
	public function getYears($offsetMax = 10, $offsetMin = 10, $centerYear = false){
		if(!$centerYear){
			$centerYear = $this->current['year'];
		}
		
		$years = array();
		for($i = $centerYear - $offsetMin; $i <= $centerYear + $offsetMax; $i++){
			array_push($years, $i);	
		}
		
		return $years;
	}
	
	//Big endian form, 2003-11-09
	public function standardFormat($date){
		$hasTime = strpos($date, ':') === false ? false : true;
		$format = 'Y-m-d';
		$timeFormat = 'H:i:s';
		
		$format .= ($hasTime ? ' '.$timeFormat : '');
		
		$strtotime = strtotime($date);
		if($strtotime == '' || $strtotime < 0){
			return;	
		}
		
		return date($format, $strtotime);	
	}
	
	//Middle endian form, 11/09/03
	public function usFormat($date, $noTime = false){
		$hasTime = strpos($date, ':') === false ? false : true;
		$format = 'm/d/Y';
		$timeFormat = 'h:i a';
		
		$format .= ($hasTime && !$noTime ? ' '.$timeFormat : '');
		
		$strtotime = strtotime($date);
		if($strtotime == '' || $strtotime < 0){
			return;	
		}
		
		return date($format, $strtotime);	
	}
	
	public function friendlyFormat($date){
		$hasTime = strpos($date, ':') === false ? false : true;
		$format = 'D M jS Y';
		$timeFormat = 'g:i a';
		
		$format .= ($hasTime ? ' '.$timeFormat : '');
		
		$strtotime = strtotime($date);
		if($strtotime == '' || $strtotime < 0){
			return;	
		}
		
		return date($format, $strtotime);	
	}
	
	public function shortFriendlyFormat($date) {
		$format = 'D M jS';
		
		$strtotime = strtotime($date);
		if($strtotime == '' || $strtotime < 0){
			return;	
		}
		
		return date($format, $strtotime);
	}
	
	public function dateIntervalCount($timestamp, $now = TIME){
		$times = array('year' => 31536000
					   , 'month' => 2592000
					   , 'week' => 604800
					   , 'day' => 86400
					   , 'hour' => 3600
					   , 'minute' => 60
					   , 'second' => 1
					   );
	
		if($timestamp > $now){
			$totalTime = $timestamp - $now;
		}
		else{
			$totalTime = $now - $timestamp;
		}
		
		$occurances = array();
		
		foreach($times as $interval => $secEquivalent){
			if($totalTime >= $secEquivalent){
				$occurances[$interval] = floor($totalTime / $secEquivalent);
				$totalTime -= $occurances[$interval] * $secEquivalent;
			}
			else{
				$occurances[$interval] = 0;
			}
		}
	
		return $occurances;
	}
	
	public function minutes($interval = 5){
		$minutes = array();
		
		for($i = 0; $i < 60; $i += $interval){
			if($i < 10){ //add leading zeros
				array_push($minutes, '0' . $i);	
			}
			else{
				array_push($minutes, $i);
			}
		}
		
		return $minutes;
	}
}
?>