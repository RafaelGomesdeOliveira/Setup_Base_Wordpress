<?php
/**
 * About Us opening: page title, content and featured image,
 * so the client can edit it without touching code.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;

$has_image = has_post_thumbnail();
?>
<section class="w-full max-w-7xl mx-auto px-5 lg:px-10 py-16 lg:py-24">
	<div class="grid gap-10 <?php echo $has_image ? 'lg:grid-cols-2 lg:items-center' : ''; ?>">
		<div class="max-w-2xl">
			<h1 class="text-h1 lg:text-h1-desktop font-semibold"><?php the_title(); ?></h1>
			<?php if ( get_the_content() ) : ?>
				<div class="prose mt-6"><?php the_content(); ?></div>
			<?php endif; ?>
		</div>

		<?php if ( $has_image ) : ?>
			<div class="overflow-hidden rounded-md bg-line">
				<?php the_post_thumbnail( 'card-2x', array( 'class' => 'w-full h-auto object-cover' ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
