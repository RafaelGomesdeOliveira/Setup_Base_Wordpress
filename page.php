<?php
/**
 * Pagina estatica generica.
 *
 * @package wp-base
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<article <?php post_class( 'w-full max-w-3xl mx-auto px-5 lg:px-10 py-12' ); ?>>
		<h1 class="text-h1 lg:text-h1-desktop font-semibold mb-8"><?php the_title(); ?></h1>
		<div class="prose"> <?php the_content(); ?></div>
	</article>
	<?php
endwhile;

get_footer();
