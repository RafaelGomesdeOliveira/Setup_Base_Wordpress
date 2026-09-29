<?php
/**
 * CSS/JS do front.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;

/**
 * Versao por filemtime: cache-busting automatico a cada build.
 */
function base_asset_version( $relative_path ) {
	$file = BASE_THEME_DIR . '/' . ltrim( $relative_path, '/' );

	return file_exists( $file ) ? (string) filemtime( $file ) : BASE_THEME_VERSION;
}

function base_enqueue_assets() {
	// output.css nao fica no git: gerado por `npm run build` / `npm run dev`.
	if ( file_exists( BASE_THEME_DIR . '/assets/css/output.css' ) ) {
		wp_enqueue_style( 'base-tailwind', BASE_THEME_URI . '/assets/css/output.css', array(), base_asset_version( 'assets/css/output.css' ) );
	}

	wp_enqueue_script( 'base-main', BASE_THEME_URI . '/assets/js/main.js', array(), base_asset_version( 'assets/js/main.js' ), true );

	// Assets por template: adicione condicionais aqui (is_front_page(), is_single()...).
}
add_action( 'wp_enqueue_scripts', 'base_enqueue_assets' );
