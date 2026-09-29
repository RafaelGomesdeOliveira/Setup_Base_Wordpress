# Site-wide settings without an Options Page (ACF Free)

Header/footer data (phone, WhatsApp, address, social links, newsletter copy, copyright) needs one place in the admin. ACF Free has no Options Page, so the theme registers a private CPT and guarantees a single post of it. Field groups for global data target that CPT; helpers read from `base_settings_id()`.

Never read global fields from a hardcoded post ID (`get_field( 'x', 3 )`) or from the front page — the ID changes between environments.

## `inc/site-settings.php`

```php
<?php
/**
 * "Configurações do site": singleton CPT that holds site-wide ACF fields
 * (ACF Free has no Options Page). Read with base_settings_field().
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;

function base_register_settings_cpt() {
	register_post_type(
		'base_settings',
		array(
			'labels'          => array(
				'name'          => 'Configurações do site',
				'singular_name' => 'Configurações do site',
				'edit_item'     => 'Configurações do site',
				'menu_name'     => 'Configurações do site',
			),
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => false,
			'menu_position'   => 61,
			'menu_icon'       => 'dashicons-admin-generic',
			'supports'        => array( 'title' ),
			'rewrite'         => false,
			'query_var'       => false,
			'capability_type' => 'page',
			'map_meta_cap'    => true,
			'capabilities'    => array( 'create_posts' => 'do_not_allow' ), // Hides "Add new".
		)
	);
}
add_action( 'init', 'base_register_settings_cpt' );

/**
 * ID of the single settings post. Created on first admin visit.
 *
 * @return int 0 when it does not exist yet (front end falls back to defaults).
 */
function base_settings_id() {
	static $id = null;

	if ( null !== $id ) {
		return $id;
	}

	$id = (int) get_option( 'base_settings_id' );

	if ( $id && 'base_settings' === get_post_type( $id ) ) {
		return $id;
	}

	$found = get_posts(
		array(
			'post_type'      => 'base_settings',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);

	$id = $found ? (int) $found[0] : 0;

	if ( ! $id && is_admin() && did_action( 'init' ) ) {
		$id = (int) wp_insert_post(
			array(
				'post_type'   => 'base_settings',
				'post_title'  => 'Configurações do site',
				'post_status' => 'publish',
			)
		);
	}

	if ( $id ) {
		update_option( 'base_settings_id', $id, false );
	}

	return $id;
}

/**
 * The CPT list screen goes straight to the edit form of the single post.
 */
function base_settings_redirect_list() {
	if ( 'base_settings' !== ( $_GET['post_type'] ?? '' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
		return;
	}

	$id = base_settings_id();

	if ( $id ) {
		wp_safe_redirect( admin_url( 'post.php?post=' . $id . '&action=edit' ) );
		exit;
	}
}
add_action( 'load-edit.php', 'base_settings_redirect_list' );

/**
 * Global field value.
 *
 * @param string $name Field name.
 * @return mixed
 */
function base_settings_field( $name ) {
	$id = base_settings_id();

	return $id ? base_field( $name, $id ) : null;
}
```

## Field groups for it

Each global area is still its own module (`inc/acf-settings-footer.php`, `inc/acf-settings-contact.php`) with a section group (`settings_footer`, `settings_contact`) and location:

```php
array( 'param' => 'post_type', 'operator' => '==', 'value' => 'base_settings' )
```

Add `'hide_on_screen' => array( 'permalink', 'slug' )` and organize areas with `tab` fields when the screen grows. Helpers read with `base_settings_field( 'settings_footer' )`.

Require `inc/site-settings.php` after `inc/acf.php` in `functions.php`.
