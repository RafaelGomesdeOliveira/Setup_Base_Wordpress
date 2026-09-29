<?php
/**
 * Fallback obrigatorio do WordPress. Cai aqui so se nenhum template mais
 * especifico existir; entrega a listagem padrao.
 *
 * @package wp-base
 */

get_header();
get_template_part( 'templates-parts/blog/listing' );
get_footer();
?>
