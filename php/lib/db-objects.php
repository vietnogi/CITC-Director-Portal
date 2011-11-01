<?
/*
Be careful when changing these functions return value since other code rely on returned "objects" definition
*/

function getSessionSpecialtyLinks ($seasonid = NULL) {
	if ($seasonid !== NULL && !is_numeric($seasonid)) {
		warning('$seasonid is not numeric');	
	}

	$query = 'SELECT session_specialty_link.session_specialty_link_id
			  , session.session_id
			  , specialty.specialty_id
			  , specialty.name
			  , specialty.clean_name
			  , specialty_type.name AS type
			  , session_specialty_link.price
			  , session_specialty_link.capacity
			  FROM session
				INNER JOIN session_specialty_link ON session_specialty_link.session_id = session.session_id
				INNER JOIN specialty ON session_specialty_link.specialty_id = specialty.specialty_id
				INNER JOIN specialty_type ON specialty.specialty_type_id = specialty_type.specialty_type_id
			  ' . (!empty($seasonid) ? 'WHERE session.season_id = :season_id' : '' ) . '
			  ORDER BY session_specialty_link.position ASC, session_specialty_link.session_specialty_link_id ASC';
	$values = empty($seasonid) ? array() : array(':season_id' => $seasonid);
	$specialties = $GLOBALS['mysql']->get($query, $values);
	
	// determine if specialty is full
	// get current enrollment counts per session/specialty
	$query = 'SELECT IFNULL(COUNT(enrollments.camper_id), 0) AS count, session_specialty_link.session_specialty_link_id
			  FROM season
			  INNER JOIN session ON season.season_id = session.season_id
			  INNER JOIN session_specialty_link ON session.session_id = session_specialty_link.session_id
			  LEFT JOIN (
						 SELECT camper.camper_id, enrollment_session_link.session_id, enrollment_session_link.specialty_id 
						 FROM enrollment_session_link 
						 INNER JOIN enrollment ON enrollment_session_link.enrollment_id = enrollment.enrollment_id
						 INNER JOIN camper ON enrollment.camper_id = camper.camper_id
						 ) AS enrollments ON (session_specialty_link.session_id = enrollments.session_id AND session_specialty_link.specialty_id = enrollments.specialty_id)
			  ' . (!empty($seasonid) ? 'WHERE session.season_id = :season_id' : '' ) . '
			  GROUP BY session_specialty_link.session_specialty_link_id
			  ';
	$values = empty($seasonid) ? array() : array(':season_id' => $seasonid);
	$counts = $GLOBALS['mysql']->get($query, $values);
	$counts = rowsToArray($counts, 'session_specialty_link_id', 'count');
	
	foreach ($specialties as $i => $specialty) {
		$capacity = $specialty['capacity'];
		$reachedCapacity = (is_numeric($capacity) && $counts[$specialty['session_specialty_link_id']] >= $capacity) ? true : false;
		$specialties[$i]['enrolled'] = $counts[$specialty['session_specialty_link_id']];
		$specialties[$i]['at_capacity'] = $reachedCapacity ? true : false;
	}
	
	return $specialties;
}

function getSessions ($seasonid = NULL) {		
	if ($seasonid !== NULL && !is_numeric($seasonid)) {
		warning('$seasonid is not numeric');	
	}
	
	// get sessions for the season
	$query = 'SELECT session.*, season.name AS season_name, 0 AS male_enrollment, 0 AS female_enrollment
			  FROM session
			  INNER JOIN season ON session.season_id = season.season_id
			  ' . (!empty($seasonid) ? 'WHERE session.season_id = :season_id' : '' );
	$values = empty($seasonid) ? array() : array(':season_id' => $seasonid);
	$sessions = $GLOBALS['mysql']->get($query, $values);
	
	//determine the gender enrollment count for each session
	$query = 'SELECT IFNULL(COUNT(enrollments.sex), 0) AS count, enrollments.sex, session.session_id
			  FROM season
			  INNER JOIN session ON season.season_id = session.season_id
			  LEFT JOIN (
						 SELECT camper.sex, enrollment_session_link.session_id 
						 FROM enrollment_session_link 
						 INNER JOIN enrollment ON enrollment_session_link.enrollment_id = enrollment.enrollment_id
						 INNER JOIN camper ON enrollment.camper_id = camper.camper_id
						 ) AS enrollments ON session.session_id = enrollments.session_id
			  ' . (!empty($seasonid) ? 'WHERE session.season_id = :season_id' : '' ) . '
			  GROUP BY enrollments.sex, session.session_id
			  ';
	$values = empty($seasonid) ? array() : array(':season_id' => $seasonid);
	$counts = $GLOBALS['mysql']->get($query, $values);
	
	foreach ($counts as $count){
		foreach ($sessions as $i => $session) {
			if ($session['session_id'] != $count['session_id']) {
				continue;	
			}
			if ($count['sex'] == 'M') {
				$sessions[$i]['male_enrollment'] = $count['count'];
			}
			else {
				$sessions[$i]['female_enrollment'] = $count['count'];	
			}
		}
	}
	
	return $sessions;
}

function getSeasons ($enrollable = true) {
	$query = 'SELECT season.* FROM season' . ($enrollable ? ' WHERE "' . DATE . '" BETWEEN enrollment_start_date AND enrollment_end_date' : '');	
	return $GLOBALS['mysql']->get($query);
}

// Retrieve states from db
function getStates ($fullName = true) {	
	$states = $GLOBALS['mysql']->get('SELECT * FROM state ORDER BY name ASC');
	$valueField = $fullName ? 'name' : 'abbreviation';
	return rowsToArray($states, 'state_id', $valueField);
}
// Retrieve countries from db
function getCountries ($fullName = true) {	
	$countries = $GLOBALS['mysql']->get('SELECT * FROM country ORDER BY name ASC');
	$valueField = $fullName ? 'name' : 'abbreviation';
	return rowsToArray($countries, 'country_id', $valueField);
}

function getParent ($userid) {
	$query = 'SELECT parent.* FROM parent WHERE parent.user_id = :user_id';
	$values = array(':user_id' => $userid);
	return $GLOBALS['mysql']->getSingle($query, $values);	
}

function getParentCreditCards ($parentid){	
	// get parent credit cards
	$query = 'SELECT parent_credit_card.*
			  FROM parent_credit_card 
			  WHERE parent_credit_card.parent_id = :parent_id
			  ORDER BY parent_credit_card.primary DESC';
	$values = array(':parent_id' => $parentid);
	$creditCards = $GLOBALS['mysql']->get($query, $values);
	
	foreach ($creditCards as $i => $creditCard) {
		$creditCards[$i] = decryptCreditCard($creditCard);
	}
	
	return $creditCards;
}
?>