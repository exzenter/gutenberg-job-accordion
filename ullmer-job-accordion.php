<?php
/**
 * Plugin Name:       Ullmer Stellenanzeigen-Stil
 * Description:       Blockstil "Stellenanzeige" für den Core-Accordion-Block: Ullmer-Kartenoptik, Pfeil dreht sich beim Aufklappen, Kreis und Pfeilfarbe faden weich ineinander. Reines CSS, kein eigener Block, kein Custom Post Type, kein JavaScript.
 * Version:           1.0.0
 * Requires at least: 6.9
 * Requires PHP:      7.4
 * Author:            exzent
 * Author URI:        https://exzent.de/
 * Plugin URI:        https://github.com/exzenter/gutenberg-job-accordion
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ullmer-job-accordion
 *
 * Das Plugin bringt keinen eigenen Block mit. Es registriert einen Blockstil
 * für core/accordion und ein Stylesheet, das nur geladen wird, wenn der Block
 * auf der Seite tatsächlich vorkommt.
 *
 * @package UllmerJobAccordion
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ULLMER_JOB_ACCORDION_VERSION', '1.0.0' );

/**
 * Registriert den Blockstil und hängt das Stylesheet an core/accordion.
 *
 * wp_enqueue_block_style() lädt die Datei nur auf Seiten, auf denen der Block
 * vorkommt – kein globales CSS für eine Seite, die gar keine Stellen zeigt.
 *
 * @return void
 */
function ullmer_job_accordion_register() {

	register_block_style(
		'core/accordion',
		array(
			'name'  => 'ullmer-job',
			'label' => __( 'Stellenanzeige', 'ullmer-job-accordion' ),
		)
	);

	wp_enqueue_block_style(
		'core/accordion',
		array(
			'handle' => 'ullmer-job-accordion',
			'src'    => plugins_url( 'assets/accordion.css', __FILE__ ),
			'path'   => plugin_dir_path( __FILE__ ) . 'assets/accordion.css',
			'ver'    => ULLMER_JOB_ACCORDION_VERSION,
		)
	);
}
add_action( 'init', 'ullmer_job_accordion_register' );

/**
 * Lädt dasselbe Stylesheet im Editor, damit die Vorschau dem Frontend entspricht.
 *
 * @return void
 */
function ullmer_job_accordion_editor_assets() {
	wp_enqueue_style(
		'ullmer-job-accordion-editor',
		plugins_url( 'assets/accordion.css', __FILE__ ),
		array(),
		ULLMER_JOB_ACCORDION_VERSION
	);
}
add_action( 'enqueue_block_editor_assets', 'ullmer_job_accordion_editor_assets' );
