<?php
if(isset($_POST["url"]))
{
	$get_url = $_POST["url"]; 
		
		//Include PHP HTML DOM parser (requires PHP 5 +)
		include_once("include/simplehtmldom_1_9_1/simple_html_dom.php");
		
		//get URL content
		$get_content = file_get_html($get_url); 
		
		//Get Page Title 
		foreach($get_content->find('title') as $element) 
		{
			$page_title = $element->plaintext;
		}
		
		//Get Body Text
		foreach($get_content->find('body') as $element) 
		{
			$page_body = trim($element->plaintext);
			$pos=strpos($page_body, ' ', 200); //Find the numeric position to substract
			$page_body = substr($page_body,0,$pos ); //shorten text to 200 chars
		}
	
		$image_urls = array();
		
		//get all images URLs in the content
		foreach($get_content->find('img') as $element) 
		{
				/* check image URL is valid and name isn't blank.gif/blank.png etc..
				you can also use other methods to check if image really exist */
				if(!preg_match('/blank.(.*)/i', $element->src) && filter_var($element->src, FILTER_VALIDATE_URL))
				{
					$image_urls[] =  $element->src;
				}
		}
		
		//prepare for JSON 
		$output = array('title'=>$page_title, 'images'=>$image_urls, 'content'=> $page_body);
		echo json_encode($output); //output JSON data
}
?>