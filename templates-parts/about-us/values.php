<?php
/**
 * Mission, vision and values. Hardcoded texts: replace them with the client's.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;

$items = array(
	array(
		'title' => 'Missão',
		'text'  => 'Descreva aqui o propósito da empresa e o que ela entrega para os clientes.',
	),
	array(
		'title' => 'Visão',
		'text'  => 'Descreva aqui onde a empresa quer chegar nos próximos anos.',
	),
	array(
		'title' => 'Valores',
		'text'  => 'Liste aqui os princípios que guiam as decisões do time no dia a dia.',
	),
);
?>
<section class="border-t border-line" aria-labelledby="values-title">
	<div class="w-full max-w-7xl mx-auto px-5 lg:px-10 py-16 lg:py-24">
		<h2 id="values-title" class="text-h2 lg:text-h2-desktop font-semibold mb-10">O que nos move</h2>

		<div class="grid gap-8 md:grid-cols-3">
			<?php foreach ( $items as $item ) : ?>
				<div class="border border-line rounded-md p-6">
					<h3 class="text-h3 lg:text-h3-desktop font-semibold"><?php echo esc_html( $item['title'] ); ?></h3>
					<p class="text-h4 text-muted mt-3"><?php echo esc_html( $item['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
