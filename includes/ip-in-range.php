<?php

function ip2bin6($ipaddr) {
	$mask=null;
	$cx = strpos($ipaddr, '/');
	if ($cx)
	{
	  $subnet = (int)(substr($ipaddr, $cx+1));
	  $ipaddr = substr($ipaddr, 0, $cx);
	}
	else $subnet = null;

	$addr = inet_pton($ipaddr);
	
	if (is_integer($subnet))
	{
	  $len = 8*strlen($addr);
	  if ($subnet > $len) $subnet = $len;
 
	  $mask  = str_repeat('f', $subnet>>2);
	  switch($subnet & 3)
	  {
	  case 3: $mask .= 'e'; break;
	  case 2: $mask .= 'c'; break;
	  case 1: $mask .= '8'; break;
	  }
	  $mask = str_pad($mask, $len>>2, '0');

	  $mask = pack('H*', $mask);

	}
	return array($addr,$mask);
}

function ip_in_range($ip, $network) {
	if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
		//IPV4
		if (strpos($network, '/') !== false) {
			// Range is in 1.2.3.4/255.255.0.0 or 1.2.3.4/16 format
			list($network, $netmask) = explode('/', $network, 2);
			if (strpos($netmask, '.') !== false) {
				// Subnet Mask is in 255.255.255.0 or 255.255.255.* format
				$netmask = str_replace('*', '0', $netmask);
				$netmask_dec = ip2long($netmask);
				return ( (ip2long($ip) & $netmask_dec) == (ip2long($network) & $netmask_dec) );
			} else {
				// CIDR Prefix
				$octets = explode('.', $network);
				while(count($octets)<4) $octets[] = '0';
				list($a,$b,$c,$d) = $octets;
				$network = sprintf("%u.%u.%u.%u", empty($a)?'0':$a, empty($b)?'0':$b,empty($c)?'0':$c,empty($d)?'0':$d);
				$network_dec = ip2long($network);
				$ip_dec = ip2long($ip);

				$wildcard_dec = pow(2, (32-$netmask)) - 1;
				$netmask_dec = ~ $wildcard_dec;

				return (($ip_dec & $netmask_dec) == ($network_dec & $netmask_dec));
			}
		} else {
			if (strpos($network, '*') !==false) {
				// 1.2.*.* format
				// Convert to 1.2.0.0-1.2.255.255 format
				$startrange = str_replace('*', '0', $network);
				$stoprange = str_replace('*', '255', $network);
				$network = "$startrange-$stoprange";
			}

			if (strpos($network, '-')!==false) {
				// 1.2.3.4-5.6.7.8 format
				list($startrange, $stoprange) = explode('-', $network, 2);
				$startrange_dec = (float)sprintf("%u",ip2long($startrange));
				$stoprange_dec = (float)sprintf("%u",ip2long($stoprange));
				$ip_dec = (float)sprintf("%u",ip2long($ip));
				return ( ($ip_dec>=$startrange_dec) && ($ip_dec<=$stoprange_dec) );
			}

			// Invalid Format
			return -1;
		}
	} elseif(filter_var($ip,FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
		//IPV6
		if (strpos($network, '/') === false) {
			$network .= '/128';
		}
		list($network,$netmask) = ip2bin6($network);
		
		if ( (inet_pton($ip) & $netmask) == ($network & $netmask) ){
			return true;
		} else {
			return false;
		}
	} else {
		//Invalid IPs
		return false;
	}
}

?>
