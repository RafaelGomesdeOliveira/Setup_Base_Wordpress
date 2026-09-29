<?php
/**
 * Ultimos posts na home custom (query secundaria, com reset).
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;

$latest = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

if ( ! $latest->have_posts() ) {
	return;
}
?>
<section class="container py-12" aria-labelledby="latest-posts-title">
	<div class="flex items-end justify-between mb-8">
		<h2 id="latest-posts-title" class="text-h2 lg:text-h2-desktop font-semibold"><?php echo esc_html( base_blog_title() ); ?></h2>
		<a href="<?php echo esc_url( base_blog_url() ); ?>" class="text-h5 underline"><?php esc_html_e( 'Ver todos', 'wp-base' ); ?></a>
	</div>

	<div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
		<?php
		while ( $latest->have_posts() ) :
			$latest->the_post();
			get_template_part( 'templates-parts/blog/card', null, base_get_card_data() );
		endwhile;
		wp_reset_postdata();
		?>
	</div>
</section>
