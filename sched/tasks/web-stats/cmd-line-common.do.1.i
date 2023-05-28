<?php
    /* common include for a non-secure independenant command line oddyssey runtime environment */

    // ** Global Function for retrieving remote data (JSON, XML, HTML, etc.)
    function get_data($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

	function just_the_domain ($url) {
		// remove possible protocols and or slash(es) in case web address was passed
		$url = str_replace("/", "", str_replace("http://", "", str_replace("https://", "", $url)));

		// split array on periods
		$pieces = explode(".", $url);

		// return last two elements as domain
		return implode(".", array_slice($pieces, -2, 2));
	}

    function namedtmpfile($suffix) {
        $file = tempnam(sys_get_temp_dir(), 'web-stats-' . $suffix . '.');
        if ($file === FALSE) {
            throw new Exception('Could not create named tmp file');
        }
        return $file;
    }
?>
