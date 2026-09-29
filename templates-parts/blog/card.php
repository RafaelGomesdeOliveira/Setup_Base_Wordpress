<?php
/**
 * Card de post. Recebe o array de base_get_card_data() via $args.
 *
 * @package wp-base
 *
 * @var array $args
 */

defined( 'ABSPATH' ) || exit;

$card = $args ?? array();

if ( empty( $card['url'] ) ) {
	return;
}
?>
<article class="group flex flex-col">
	<a href="<?php echo esc_url( $card['url'] ); ?>" class="block overflow-hidden rounded-md bg-line aspect-3/2">
		<?php echo $card['thumbnail']; // ja escapado pelo WP ?>
	</a>

	<div class="mt-4">
		<p class="text-h6 uppercase tracking-wide text-muted">
			<?php if ( ! empty( $card['category'] ) ) : ?>
				<span><?php echo esc_html( $card['category']->name ); ?></span> &middot;
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( $card['date_iso'] ); ?>"><?php echo esc_html( $card['date'] ); ?></time>
		</p>
		<h2 class="text-h3 lg:text-h3-desktop font-semibold mt-2">
			<a href="<?php echo esc_url( $card['url'] ); ?>" class="group-hover:underline"><?php echo esc_html( $card['title'] ); ?></a>
		</h2>
		<?php if ( ! empty( $card['excerpt'] ) ) : ?>
			<p class="text-h5 text-muted mt-2 line-clamp-3"><?php echo esc_html( $card['excerpt'] ); ?></p>
		<?php endif; ?>
	</div>
</article>
