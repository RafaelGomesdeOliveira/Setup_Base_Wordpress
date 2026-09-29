<?php
/**
 * Listagem do blog (pagina definida como "Pagina de posts" em Leitura,
 * ou a home quando o site e so blog e nao existe front-page.php).
 *
 * @package wp-base
 */

get_header();
get_template_part( 'templates-parts/blog/listing', null, array( 'title' => base_blog_title() ) );
get_footer();
?>
