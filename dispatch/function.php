<?php

  function isValidZIPCode($zipcode)
    {
        return (preg_match("/^[0-9]{5}(-[0-9]{4})?$/i", trim($zipcode)) > 0) ? true : false;
    }
	
  function isValidPhone($phone)
  {
	$result = preg_replace("/\D/","",$phone);
	if (substr($result, 0, 1) == 1) {
		$result = substr($result, 1);		
	}
	
	if (strlen($result) < 10) {
		return false;		
	}
	
	else {
		return true;
	}
	  
  }
  
  function dateDefault($date1)
  {
	$datereturn = new DateTime($date1);
	return $datereturn->format('D, M jS, Y');
  }
  
  function isValidOrgName($org)
  {
	  return isValidString($org);
  }
  
  function isValidString($string)
  {
	  return (strlen(trim($string)) > 0) ? true : false;
  }
  
  function isValidAddress($address)
  {
	if (isValidString($address)) {
		$addresscheck = explode(" ", trim($address));
		if (!isset($addresscheck[2])) {
			return false;
		}
		else {
			return true;
		}
	}
	else {
		return false;
	}
	
  }
  
  function formatValid($value)
  {
	  if ($value == true) {
		  return "Valid";
	  }
	  else {
		  return '<strong><font color="#ff0000">INVALID</font></strong>';
	  }
	  
  }

?>
