<?php
/**
 * Cabecalho do site.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;
?>
<header class="relative z-40 border-b border-line bg-bg">
	<div class="w-full max-w-7xl mx-auto px-5 lg:px-10 flex items-center justify-between py-4">
		

		<button type="button" class="group relative size-6 lg:hidden" data-menu-toggle aria-expanded="false" aria-controls="primary-menu" aria-label="Menu">
			<span class="absolute left-0 top-1 w-6 h-0.5 bg-fg transition-all duration-300 group-aria-expanded:top-1/2 group-aria-expanded:-translate-y-1/2 group-aria-expanded:rotate-45"></span>
			<span class="absolute left-0 top-1/2 -translate-y-1/2 w-6 h-0.5 bg-fg transition-all duration-300 group-aria-expanded:opacity-0"></span>
			<span class="absolute left-0 bottom-1 w-6 h-0.5 bg-fg transition-all duration-300 group-aria-expanded:bottom-1/2 group-aria-expanded:translate-y-1/2 group-aria-expanded:-rotate-45"></span>
		</button>

		<nav id="primary-menu" class="hidden lg:block absolute lg:static top-full inset-x-0 max-h-[80dvh] overflow-y-auto lg:max-h-none lg:overflow-visible bg-bg lg:bg-transparent border-b lg:border-0 border-line shadow-lg lg:shadow-none" aria-label="Menu principal">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav-menu flex flex-col lg:flex-row lg:items-center gap-4 lg:gap-8 p-5 lg:p-0 text-h5 uppercase tracking-wide',
					'fallback_cb'    => false,
					'depth'          => 3,
				)
			);
			?>
		</nav>

		<div class="text-h4 font-semibold">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</header>
