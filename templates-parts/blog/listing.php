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
<section class="container py-12">
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
				'prev_text' => __( 'Anterior', 'wp-base' ),
				'next_text' => __( 'Proximo', 'wp-base' ),
				'class'     => 'mt-12',
			)
		);
		?>
	<?php else : ?>
		<p class="text-muted"><?php esc_html_e( 'Nenhum post encontrado.', 'wp-base' ); ?></p>
	<?php endif; ?>
</section>
