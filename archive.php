<?php
/**
 * Categoria, tag, autor, data e CPTs com arquivo.
 *
 * @package wp-base
 */

get_header();
get_template_part(
	'templates-parts/blog/listing',
	null,
	array(
		'title'       => get_the_archive_title(),
		'description' => get_the_archive_description(),
	)
);
get_footer();
