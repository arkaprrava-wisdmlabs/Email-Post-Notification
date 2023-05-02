<?php 
function epn_meta_description($response)
{
    $word = '<meta name="description" content="';

    $index = strpos($response, $word);
    $metaDescriptionMsg = '';

    if ($index !== false) {

        //Get the end index of the meta tag
        $end = strpos($response, '>', $index);
        //Exclude the <meta name="description" Content=" part and get the only content
        $start = $index + 34;
        $length = $end - $start - 3;
        $metaDescriptionMsg = substr($response, $start, $length);

    } else {
        $metaDescriptionMsg = "No Meta Description Found";
    }

    return $metaDescriptionMsg;

}

function epn_meta_title($response)
{
    $word = '<title>';
    $index = strpos($response, $word);
    $metaTitle = '';
    if ($index !== false) {
        $end = strpos($response, '</title>', $index);
        $start = $index + 7;
        $length = $end - $start;
        $metaTitle = substr($response, $start, $length);
    } else {
        $metaTitle = "No Title Found";
    }

    return $metaTitle;
}
function epn_page_speed_score($url)
	{

		$api_key = "416ca0ef-63e4-4caa-a047-ead672ecc874"; // your api key
		$new_url = "http://www.webpagetest.org/runtest.php?url=" . $url . "&runs=1&f=xml&k=" . $api_key;
		$run_result = simplexml_load_file($new_url);
		$response_status_code = $run_result->statusCode;
		if($response_status_code != 200){
			return $run_result->statusText;
		}
		$test_id = $run_result->data->testId;

		$status_code = 100;

		while ($status_code != 200) {
			sleep(10);
			$xml_result = "http://www.webpagetest.org/xmlResult/" . $test_id . "/";
			$result = simplexml_load_file($xml_result);
			$status_code = $result->statusCode;
			$time = (float) ($result->data->median->firstView->loadTime) / 1000;
		}

		return $time;

}
