<?php
/**
 * Resultados de busca.
 *
 * @package wp-base
 */

get_header();
get_template_part(
	'templates-parts/blog/listing',
	null,
	array(
		/* translators: %s: termo buscado */
		'title' => sprintf( __( 'Resultados para "%s"', 'wp-base' ), get_search_query() ),
	)
);
get_footer();
