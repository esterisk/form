<?php
namespace Esterisk\Form\Field;

class FieldUrl extends FieldText
{
	var $fieldtype = 'url';
	var $placeholder = 'http://';
	var $rules = [ 'URL' ];

	public function validator()
	{	
		$value = $this->requestValue();
		$dummy = @file_get_contents($value);
		if ($dummy == '') return 'indirizzo non raggiungibile';
		else return true;
    	}

	public function sanitize($value, $input)
	{	
		static $badParameters = [ 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'gclsrc', 'dclid', 'fbclid', 'zanpid' ];
		$parsed_url = parse_url($value);
		if (!isset($parsed_url['query'])) return $value;
		
		$query = [];
		parse_str($parsed_url['query'], $query);
		asort($query);
		foreach ($badParameters as $bp) unset($query[$bp]);
		$parsed_url['query'] = http_build_query($query);
		$value = $this->unparse_url($parsed_url);
		return $value;
	}

	function unparse_url($parsed_url) { 
		$scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : ''; 
		$host     = isset($parsed_url['host']) ? $parsed_url['host'] : ''; 
		$port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : ''; 
		$user     = isset($parsed_url['user']) ? $parsed_url['user'] : ''; 
		$pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : ''; 
		$pass     = ($user || $pass) ? "$pass@" : ''; 
		$path     = isset($parsed_url['path']) ? $parsed_url['path'] : ''; 
		$query    = !empty($parsed_url['query']) ? '?' . $parsed_url['query'] : ''; 
		$fragment = !empty($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : ''; 
		return "$scheme$user$pass$host$port$path$query$fragment"; 
	}
	
	function show($value)
	{
		return '<a href="'.$value.'" target="_blank">'.$value.'</a>';
	}
	
}
