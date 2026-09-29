<?php
/**
 * Nav menus: submenu toggle buttons.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;

/**
 * Appends a toggle button after the link of items that have children, in any menu
 * rendered with the .nav-menu class.
 * Hover opens submenus on desktop (CSS); the button covers click, touch and keyboard,
 * and drives the accordion on mobile (assets/js/main.js toggles .is-open on the <li>).
 *
 * @param string   $item_output Item HTML.
 * @param WP_Post  $item        Menu item.
 * @param int      $depth       Item depth (0 = top level).
 * @param stdClass $args        wp_nav_menu() arguments.
 * @return string
 */
function base_nav_menu_submenu_toggle( $item_output, $item, $depth, $args ) {
	$is_nav_menu = in_array( 'nav-menu', explode( ' ', (string) $args->menu_class ), true );

	if ( ! $is_nav_menu || ! in_array( 'menu-item-has-children', (array) $item->classes, true ) ) {
		return $item_output;
	}

	// The class is added even when 'depth' stops the walker from rendering the submenu.
	if ( ! empty( $args->depth ) && $depth + 1 >= $args->depth ) {
		return $item_output;
	}

	$label = sprintf( __( 'Submenu de %s', 'wp-base' ), wp_strip_all_tags( $item->title ) );

	return $item_output . sprintf(
		'<button type="button" class="submenu-toggle" aria-expanded="false" aria-label="%s"><svg viewBox="0 0 12 12" fill="none" aria-hidden="true"><path d="M2.5 4.5 6 8l3.5-3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></button>',
		esc_attr( $label )
	);
}
add_filter( 'walker_nav_menu_start_el', 'base_nav_menu_submenu_toggle', 10, 4 );
