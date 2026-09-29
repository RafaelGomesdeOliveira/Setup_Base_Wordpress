<?php
/**
 * Home do site.
 *
 * Dois modos, definidos em Configuracoes > Leitura:
 *  - "Seus posts mais recentes"  -> site e um blog: mostra a listagem.
 *  - "Uma pagina estatica"       -> home custom: secoes de templates-parts/home/.
 *    A pagina escolhida como "Pagina de posts" usa home.php.
 *
 * @package wp-base
 */

get_header();

if ( is_home() ) {
	
	get_template_part( 'templates-parts/blog/listing' );
} else {
	get_template_part( 'templates-parts/home/hero' );
	get_template_part( 'templates-parts/home/latest-posts' );
	// Adicione mais secoes aqui: get_template_part( 'templates-parts/home/<secao>' );
}

get_footer();
?>
