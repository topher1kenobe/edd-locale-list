<?php
/**
 * Plugin Name: Easy Digital Downloads - Locale List
 * Description: Renders HTML table of locale translation status for EDD
 * Version: 1.0
 * Author: Topher
 */

function edd_get_locale_data() {

	$url = 'https://translate.wordpress.org/api/projects/wp-plugins/easy-digital-downloads/stable';

	$response = wp_remote_get( $url );

	return $response['body'];

}

function edd_render_locale_data() {

	$output = '';

	$json = edd_get_locale_data();

	$data = json_decode( $json );

	$locales = $data->translation_sets;

print '<pre>';
print_r( $locales );
print '</pre>';

	$output .= '<table class="standard-table">' . "\n";
	$output .= '<thead>' . "\n";
	$output .= '<tr>' . "\n";

	$output .= '<th>Locale</th>' . "\n";
	$output .= '<th>Status</th>' . "\n";
	$output .= '<th></th>' . "\n";

	$output .= '</tr>' . "\n";
	$output .= '</thead>' . "\n";

	$output .= '<tbody>' . "\n";


	foreach ( $locales as $locale ) {

		if ( 100 == $locale->percent_translated ) {
			$class = "complete";
			} elseif ( 90 <= $locale->percent_translated && 100 > $locale->percent_translated ) {
			$class = "complete90";
			} elseif ( 80 <= $locale->percent_translated && 90 > $locale->percent_translated ) {
			$class = "complete80";
			} elseif ( 70 <= $locale->percent_translated && 80 > $locale->percent_translated ) {
			$class = "complete70";
			} elseif ( 60 <= $locale->percent_translated && 70 > $locale->percent_translated ) {
			$class = "complete60";
			} elseif ( 50 <= $locale->percent_translated && 60 > $locale->percent_translated ) {
			$class = "complete50";
			} elseif ( 40 <= $locale->percent_translated && 50 > $locale->percent_translated ) {
			$class = "complete40";
			} elseif ( 30 <= $locale->percent_translated && 40 > $locale->percent_translated ) {
			$class = "complete30";
			} else {
			$class = 'incomplete';
		}

		$output .= '<tr class="' . esc_attr( $class ) . '">' . "\n";

		$output .= '<td>' . esc_html( $locale->name ) . '</td>' . "\n";
		$output .= '<td>' . absint( $locale->percent_translated ) . '%</td>' . "\n";
		$output .= '<td><a href="https://translate.wordpress.org/locale/' . esc_attr( $locale->locale ) . '/' . esc_attr( $locale->slug ) . '/wp-plugins/easy-digital-downloads">Translate</a></td>' . "\n";

		$output .= '</tr>' . "\n";

	}

	$output .= '</tbody>' . "\n";
	$output .= '</table>' . "\n";

	return $output;

}
add_shortcode( 'edd-locale-list', 'edd_render_locale_data' );
