<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define ('CURRENCY_QS', 'wmc-currency');

define ('CURRENCY_QS_VALUE' , (isset($_GET[CURRENCY_QS]) ? $_GET[CURRENCY_QS] : '')  );

if ( ! function_exists( 'get_products_links' )){
	function get_products_links( $buffer ) {
		
		$product_link_array = array();

		preg_match_all('/<a[\S\s]*?>/', $buffer, $found_links);
		
		foreach ($found_links[0] as $key => $link ) {

			if ( strpos( $link, '#' ) !== false || strpos( $link, '?' ) !== false  ) continue;

			if ( strpos( $link, '/product' ) !== false )  $product_link_array[] = $link;
			
			if ( strpos( $link, '/store' ) !== false ) $product_link_array[] = $link;

		}

		return array_unique($product_link_array);

	}
}

if ( ! function_exists( 'add_qs_to_href' )){
	function add_qs_to_href($link) {

		$href_break = explode('href=', $link);

		$quote_character = $href_break[1][0];

		$link_break = explode( $quote_character, $href_break[1] );

		// $link_break[1] = rtrim( $link_break[1], '/' ); // remove slash on the end

		$link_break[1] .= '?'.CURRENCY_QS.'='.CURRENCY_QS_VALUE;

		$href_break[1] =  implode( $quote_character, $link_break);

		$href = implode('href=', $href_break);

		return $href;

	
	}
}


if ( ! function_exists( 'add_qs_to_internal_links' )){
	function add_qs_to_internal_links( $buffer ) { // $buffer contains entire page

		$found_products_links = get_products_links($buffer);
		
		foreach ($found_products_links as $key => $link) {

			$link_with_qs = add_qs_to_href($link);
			
			$buffer = str_replace( $link, $link_with_qs , $buffer );

		}

		return $buffer;
	}
}

if ( ! function_exists( 'adjust_page_content' )){
	function adjust_page_content() {
		
		ob_start();

		ob_start( 'add_qs_to_internal_links' );
	
	}
}



if ( ! function_exists( 'add_currency_qs_to_internal_links' )){
	function add_currency_qs_to_internal_links() {
	
	if ( empty(CURRENCY_QS_VALUE) ) return;

	add_action( 'template_redirect', 'adjust_page_content' );
	
	}
}

add_currency_qs_to_internal_links();