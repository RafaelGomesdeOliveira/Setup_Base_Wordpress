<?php
/**
 * Secao hero da home custom. Usa o titulo/conteudo da pagina definida como
 * "Pagina inicial" em Leitura, para o cliente editar sem tocar em codigo.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;
?>
<section class="w-full max-w-7xl mx-auto px-5 lg:px-10 py-20 lg:py-32">
	<div class="max-w-2xl">
		<h1 class="text-h1 lg:text-h1-desktop font-semibold"><?php the_title(); ?></h1>
		<?php if ( get_the_content() ) : ?>
			<div class="prose mt-6"><?php the_content(); ?></div>
		<?php endif; ?>
		<a href="<?php echo esc_url( base_blog_url() ); ?>" class="btn mt-8"><?php echo esc_html( base_blog_title() ); ?></a>
	</div>
</section>
