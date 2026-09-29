<?php
/**
 * Helpers de template.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;

/**
 * Dados de um post para o card da listagem. Centraliza aqui para que
 * home, arquivo e busca renderizem o mesmo card.
 *
 * @param int|WP_Post $post
 * @return array<string, mixed>
 */
function base_get_card_data( $post = null ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return array();
	}

	$categories = get_the_category( $post->ID );

	return array(
		'id'        => $post->ID,
		'url'       => get_permalink( $post ),
		'title'     => get_the_title( $post ),
		'excerpt'   => get_the_excerpt( $post ),
		'date'      => get_the_date( '', $post ),
		'date_iso'  => get_the_date( 'c', $post ),
		'category'  => ! empty( $categories ) ? $categories[0] : null,
		'thumbnail' => get_the_post_thumbnail( $post, 'card', array( 'class' => 'w-full h-auto object-cover', 'loading' => 'lazy' ) ),
	);
}

/**
 * Titulo da pagina de listagem do blog (nome da pagina definida em Leitura, ou "Blog").
 */
function base_blog_title() {
	$page_id = (int) get_option( 'page_for_posts' );

	return $page_id ? get_the_title( $page_id ) : __( 'Blog', 'wp-base' );
}

/**
 * URL da listagem do blog (funciona nos dois modos de Leitura).
 */
function base_blog_url() {
	$page_id = (int) get_option( 'page_for_posts' );

	return $page_id ? get_permalink( $page_id ) : home_url( '/' );
}
