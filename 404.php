<?php
/**
 * Pagina nao encontrada.
 *
 * @package wp-base
 */

get_header();
?>
<section class="w-full max-w-3xl mx-auto px-5 lg:px-10 py-24 text-center">
	<h1 class="text-h1 lg:text-h1-desktop font-semibold">Pagina nao encontrada</h1>
	<p class="text-muted mt-4">O endereco pode estar errado ou a pagina foi removida.</p>
	<div class="mt-8"><?php get_search_form(); ?></div>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn mt-8">Voltar ao inicio</a>
</section>
<?php
get_footer();
