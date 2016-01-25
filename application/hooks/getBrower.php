<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function _getBrowser(){
    // $CI = & get_instance();
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= '';
    $ipaddress = '';

    //HTTP_CLIENT_IP
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';

    if (preg_match('/linux/i', $u_agent)) { $platform = 'linux'; }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) { $platform = 'mac'; }
    elseif (preg_match('/windows|win32/i', $u_agent)) { $platform = 'windows'; }

    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) { $bname = 'IE'; $ub = "MSIE"; }
    elseif(preg_match('/Firefox/i',$u_agent)) { $bname = 'Firefox'; $ub = "Firefox"; }
    elseif(preg_match('/Chrome/i',$u_agent)) { $bname = 'Chrome'; $ub = "Chrome"; }
    elseif(preg_match('/Safari/i',$u_agent)) { $bname = 'Safari'; $ub = "Safari"; }
    elseif(preg_match('/Opera/i',$u_agent)) { $bname = 'Opera'; $ub = "Opera"; }
    elseif(preg_match('/Netscape/i',$u_agent)) { $bname = 'Netscape'; $ub = "Netscape"; }

    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
        ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){ $version= $matches['version'][0]; }
        else { $version= $matches['version'][1]; }
    }
    else { $version= $matches['version'][0]; }

    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    return array(
        'userAgent'=>$u_agent,
        'name'=>$bname,
        'ip'=>$ipaddress,
        'version'=>$version,
        'platform'=>$platform,
        'pattern'=>$pattern
    );
}
