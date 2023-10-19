<?php  
/**
 * Function Name
 *
 * Function Description
 *
 * @access	public
 * @param	type	name
 * @return	type	
 */
 if(! function_exists('curPageURL')){
    function curPageURL() {
      if(isset($_SERVER["HTTPS"]) && !empty($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] != 'off' )) {
            $url = 'https://'.$_SERVER["SERVER_NAME"];//https url
      }  else {
        $url =  'http://'.$_SERVER["SERVER_NAME"];//http url
      }
      // if(( $_SERVER["SERVER_PORT"] != 80 )) {
      //    $url .= ":".$_SERVER["SERVER_PORT"];
      // }
      $url .= $_SERVER["REQUEST_URI"];
      return $url;
    }
}

if (! function_exists('getBaseUrl'))
{
	function getBaseUrl() 
	{
	    // output: /myproject/index.php
	    $currentPath = $_SERVER['PHP_SELF']; 

	    // output: Array ( [dirname] => /myproject [basename] => index.php [extension] => php [filename] => index ) 
	    $pathInfo = pathinfo($currentPath); 

	    // output: localhost
	    $hostName = $_SERVER['HTTP_HOST']; 

	    // output: http://
	    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';

	    // return: http://localhost/myproject/
	    return $protocol.'://'.$hostName.$pathInfo['dirname']."/";
	}
}
?>