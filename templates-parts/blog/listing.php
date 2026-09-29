<?php
/**
 * Listagem de posts da query principal (home, blog, arquivos, busca).
 *
 * @package wp-base
 *
 * @var array $args { title?: string, description?: string }
 */

defined( 'ABSPATH' ) || exit;

$title       = isset( $args['title'] ) ? (string) $args['title'] : '';
$description = isset( $args['description'] ) ? (string) $args['description'] : '';
?>
<section class="w-full max-w-7xl mx-auto px-5 lg:px-10 py-12">
	<?php if ( $title ) : ?>
		<header class="mb-10">
			<h1 class="text-h1 lg:text-h1-desktop font-semibold"><?php echo esc_html( $title ); ?></h1>
			<?php if ( $description ) : ?>
				<div class="text-muted mt-2"><?php echo wp_kses_post( $description ); ?></div>
			<?php endif; ?>
		</header>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'templates-parts/blog/card', null, base_get_card_data() );
			endwhile;
			?>
		</div>

		<?php
		the_posts_pagination(
			array(
				'prev_text' => 'Anterior',
				'next_text' => 'Proximo',
				'class'     => 'mt-12',
			)
		);
		?>
	<?php else : ?>
		<p class="text-muted">Nenhum post encontrado.</p>
	<?php endif; ?>
</section>
