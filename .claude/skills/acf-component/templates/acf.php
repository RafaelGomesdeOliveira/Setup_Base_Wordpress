<?php
/**
 * ACF shared helpers: field group defaults and value normalizers.
 * Every inc/acf-*.php module builds on these. The theme must keep rendering
 * with ACF deactivated, so every helper degrades to an empty value.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registers a local field group with the theme defaults.
 * Call it inside a function hooked to 'acf/include_fields'.
 *
 * @param array<string, mixed> $group acf_add_local_field_group() args.
 */
function base_acf_add_group( $group ) {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		wp_parse_args(
			$group,
			array(
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'active'                => true,
			)
		)
	);
}

/**
 * get_field() that does not fatal when ACF is inactive.
 *
 * @param string          $name    Field name.
 * @param int|string|bool $post_id Post ID, 'term_{id}', 'user_{id}' or false for the current post.
 * @return mixed
 */
function base_field( $name, $post_id = false ) {
	return function_exists( 'get_field' ) ? get_field( $name, $post_id ) : null;
}

/**
 * Trimmed string from a text/textarea value ('' for anything else).
 *
 * @param mixed $value
 * @return string
 */
function base_acf_text( $value ) {
	return is_scalar( $value ) ? trim( (string) $value ) : '';
}

/**
 * Attachment ID from an image/file value, whatever its return_format ('id', 'array', 'url').
 *
 * @param mixed $value
 * @return int
 */
function base_acf_image_id( $value ) {
	if ( is_array( $value ) && ! empty( $value['ID'] ) ) {
		return (int) $value['ID'];
	}

	if ( is_numeric( $value ) ) {
		return (int) $value;
	}

	if ( is_string( $value ) && '' !== $value ) {
		return (int) attachment_url_to_postid( $value );
	}

	return 0;
}

/**
 * Normalized link field (return_format 'array'). Empty array when there is no URL.
 *
 * @param mixed $value
 * @return array{url: string, title: string, target: string}|array{}
 */
function base_acf_link( $value ) {
	if ( ! is_array( $value ) || empty( $value['url'] ) ) {
		return array();
	}

	return array(
		'url'    => (string) $value['url'],
		'title'  => base_acf_text( $value['title'] ?? '' ),
		'target' => '_blank' === ( $value['target'] ?? '' ) ? '_blank' : '',
	);
}

/**
 * Post IDs from a post_object/relationship value (IDs or WP_Post objects).
 *
 * @param mixed $value
 * @param int   $limit 0 = no limit.
 * @return array<int, int>
 */
function base_acf_post_ids( $value, $limit = 0 ) {
	$ids = array();

	foreach ( (array) $value as $post ) {
		$post_id = $post instanceof WP_Post ? (int) $post->ID : (int) $post;

		if ( $post_id ) {
			$ids[] = $post_id;
		}

		if ( $limit && count( $ids ) >= $limit ) {
			break;
		}
	}

	return $ids;
}

/**
 * Responsive image with optional mobile art direction. Mobile first: the <img> is the
 * mobile image and a <source> swaps in the desktop one from the lg breakpoint (64rem).
 * Without a distinct mobile image it is a plain wp_get_attachment_image().
 *
 * @param int                  $desktop_id Desktop attachment ID.
 * @param int                  $mobile_id  Mobile attachment ID (0 = same as desktop).
 * @param string               $size       Registered image size.
 * @param array<string, mixed> $attr       <img> attributes (class, loading, alt...).
 * @return string Safe HTML, or '' when there is no image.
 */
function base_picture( $desktop_id, $mobile_id = 0, $size = 'full', $attr = array() ) {
	$desktop_id = (int) $desktop_id;
	$mobile_id  = (int) $mobile_id;

	if ( ! $desktop_id ) {
		$desktop_id = $mobile_id;
	}

	if ( ! $desktop_id ) {
		return '';
	}

	if ( ! $mobile_id || $mobile_id === $desktop_id ) {
		return wp_get_attachment_image( $desktop_id, $size, false, $attr );
	}

	$srcset = wp_get_attachment_image_srcset( $desktop_id, $size );
	$sizes  = wp_get_attachment_image_sizes( $desktop_id, $size );

	if ( ! $srcset ) {
		$srcset = (string) wp_get_attachment_image_url( $desktop_id, $size );
	}

	return sprintf(
		'<picture><source media="(min-width: 64rem)" srcset="%1$s"%2$s>%3$s</picture>',
		esc_attr( $srcset ),
		$sizes ? ' sizes="' . esc_attr( $sizes ) . '"' : '',
		wp_get_attachment_image( $mobile_id, $size, false, $attr )
	);
}
