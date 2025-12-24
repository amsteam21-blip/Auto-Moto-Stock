<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
if (!function_exists('amotos_get_price_decimal_separator')) {
	function amotos_get_price_decimal_separator() {
		return amotos_get_option('decimal_separator', '.');
	}
}

if (!function_exists('amotos_get_price_decimals')) {
	function amotos_get_price_decimals() {
		return amotos_get_option('number_of_decimals', 0);
	}
}

if (!function_exists('amotos_get_rounding_precision')) {
	function amotos_get_rounding_precision() {
		$precision = amotos_get_price_decimals() + 2;
		if ( absint( AMOTOS_ROUNDING_PRECISION ) > $precision ) {
			$precision = absint( AMOTOS_ROUNDING_PRECISION );
		}
		return $precision;
	}
}


/**
 * Format decimal numbers ready for DB storage.
 *
 * Sanitize, optionally remove decimals, and optionally round + trim off zeros.
 *
 * This function does not remove thousands - this should be done before passing a value to the function.
 *
 * @param  float|string $number     Expects either a float or a string with a decimal separator only (no thousands).
 * @param  mixed        $dp number  Number of decimal points to use, blank to use number_of_decimals, or false to avoid all rounding.
 * @param  bool         $trim_zeros From end of string.
 * @return string
 */
if (!function_exists('amotos_format_decimal')) {
	function amotos_format_decimal( $number, $dp = false, $trim_zeros = false ) {
		$locale   = localeconv();
		$decimals = array( amotos_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'] );

		// Remove locale from string.
		if ( ! is_float( $number ) ) {
			$number = str_replace( $decimals, '.', $number );
			// Convert multiple dots to just one.
			$number = preg_replace( '/\.(?![^.]+$)|[^0-9.-]/', '', amotos_clean( $number ) );
		}


		if ( false !== $dp ) {
			$dp     = intval( '' === $dp ? amotos_get_price_decimals() : $dp );
			$number = number_format( floatval( $number ), $dp, '.', '' );
		} elseif ( is_float( $number ) ) {
			// DP is false - don't use number format, just return a string using whatever is given. Remove scientific notation using sprintf.
			$number = str_replace( $decimals, '.', sprintf( '%.' . amotos_get_rounding_precision() . 'f', $number ) );
			// We already had a float, so trailing zeros are not needed.
			$trim_zeros = true;
		}


		if ( $trim_zeros && strstr( $number, '.' ) ) {
			$number = rtrim( rtrim( $number, '0' ), '.' );
		}

		return $number;
	}
}

/**
 * Format a price with AMOTOS Currency Locale settings.
 *
 * @param  string $value Price to localize.
 * @return string
 */
if (!function_exists('amotos_format_localized_price')) {
	function amotos_format_localized_price( $value ) {
		return apply_filters( 'amotos_format_localized_price', str_replace( '.', amotos_get_price_decimal_separator(), strval( $value ) ), $value );
	}
}