<?php
/**
 * Bootstrap do tema. Toda a logica fica em inc/ — um arquivo por responsabilidade.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;

define( 'BASE_THEME_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'BASE_THEME_DIR', get_template_directory() );
define( 'BASE_THEME_URI', get_template_directory_uri() );

require_once BASE_THEME_DIR . '/inc/setup.php'; // Setup do projeto, logo, title tag...
require_once BASE_THEME_DIR . '/inc/enqueue.php'; // Enqueue de arquivos de estilo, java script...
require_once BASE_THEME_DIR . '/inc/helpers.php';
require_once BASE_THEME_DIR . '/inc/nav-menu.php'; // Primary menu submenu toggles

// Modulos de funcionalidade (CPTs, campos custom, integracoes) entram aqui,
// um arquivo por modulo. Ex.: require_once BASE_THEME_DIR . '/inc/cpt-produtos.php';
