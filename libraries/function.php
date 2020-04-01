<?php
function debug($arr){
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
    //exit();
}

if (!function_exists('mb_str_replace')){
	function mb_str_replace($needle, $tex_replace, $haystack){
		return implode($tex_replace, explode($needle, $haystack));
	}
}