<?php
/**
 * Pagina nao encontrada.
 *
 * @package wp-base
 */

get_header();
?>
<section class="container-narrow py-24 text-center">
	<h1 class="text-h1 lg:text-h1-desktop font-semibold"><?php esc_html_e( 'Pagina nao encontrada', 'wp-base' ); ?></h1>
	<p class="text-muted mt-4"><?php esc_html_e( 'O endereco pode estar errado ou a pagina foi removida.', 'wp-base' ); ?></p>
	<div class="mt-8"><?php get_search_form(); ?></div>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn mt-8"><?php esc_html_e( 'Voltar ao inicio', 'wp-base' ); ?></a>
</section>
<?php
get_footer();
