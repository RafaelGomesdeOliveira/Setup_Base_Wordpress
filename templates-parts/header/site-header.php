<?php
/**
 * Cabecalho do site.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;
?>
<header class="border-b border-line">
	<div class="container flex items-center justify-between py-4">
		<div class="text-h4 font-semibold">
			<?php if ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
			<?php endif; ?>
		</div>

		<button type="button" class="lg:hidden" data-menu-toggle aria-expanded="false" aria-controls="primary-menu" aria-label="<?php esc_attr_e( 'Abrir menu', 'wp-base' ); ?>">
			<span class="block w-6 h-0.5 bg-fg mb-1.5"></span>
			<span class="block w-6 h-0.5 bg-fg mb-1.5"></span>
			<span class="block w-6 h-0.5 bg-fg"></span>
		</button>

		<nav id="primary-menu" class="hidden lg:block absolute lg:static top-full left-0 w-full lg:w-auto bg-bg lg:bg-transparent border-b lg:border-0 border-line" aria-label="<?php esc_attr_e( 'Menu principal', 'wp-base' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'flex flex-col lg:flex-row gap-4 lg:gap-8 p-4 lg:p-0 text-h5 uppercase tracking-wide',
					'fallback_cb'    => false,
					'depth'          => 1,
				)
			);
			?>
		</nav>
	</div>
</header>
