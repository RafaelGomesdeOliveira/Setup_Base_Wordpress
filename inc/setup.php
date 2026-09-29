<?php
/**
 * Suportes do tema, menus e tamanhos de imagem.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;

function base_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 240, 'flex-width' => true, 'flex-height' => true ) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'automatic-feed-links' );

	register_nav_menus(
		array(
			'primary' => 'Menu principal',
			'footer'  => 'Menu do rodape',
		)
	);

	// Tamanhos de imagem do projeto. Para retina, registre o par 2x com o dobro exato
	// das dimensoes: o WP monta o srcset sozinho com tamanhos de mesma proporcao.
	add_image_size( 'card', 600, 400, true );
	add_image_size( 'card-2x', 1200, 800, true );
}
add_action( 'after_setup_theme', 'base_theme_setup' );

/**
 * Largura padrao de conteudo (oEmbed etc.).
 */
function base_content_width() {
	$GLOBALS['content_width'] = 1280;
}
add_action( 'after_setup_theme', 'base_content_width', 0 );

/**
 * Limpeza de head: emoji e generator.
 */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
