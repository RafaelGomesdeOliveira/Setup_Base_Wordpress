<?php
/**
 * Post individual.
 *
 * @package wp-base
 */

get_header();

while ( have_posts() ) :
	the_post();
	$card = base_get_card_data();
	?>
	<article <?php post_class( 'w-full max-w-3xl mx-auto px-5 lg:px-10 py-12' ); ?>>
		<header class="mb-8">
			<?php if ( $card['category'] ) : ?>
				<a href="<?php echo esc_url( get_category_link( $card['category'] ) ); ?>" class="text-h6 uppercase tracking-wide text-muted"><?php echo esc_html( $card['category']->name ); ?></a>
			<?php endif; ?>
			<h1 class="text-h1 lg:text-h1-desktop font-semibold mt-2"><?php the_title(); ?></h1>
			<p class="text-h6 text-muted mt-3">
				<time datetime="<?php echo esc_attr( $card['date_iso'] ); ?>"><?php echo esc_html( $card['date'] ); ?></time>
				&middot; <?php the_author(); ?>
			</p>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="mb-8"><?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto' ) ); ?></figure>
		<?php endif; ?>

		<div class="prose">
			<?php the_content(); ?>
		</div>

		<?php
		wp_link_pages();

		if ( comments_open() || get_comments_number() ) {
			comments_template();
		}
		?>
	</article>
	<?php
endwhile;

get_footer();
