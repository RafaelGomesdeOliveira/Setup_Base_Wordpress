<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'min-h-screen flex flex-col bg-bg text-fg font-sans antialiased' ); ?>>
<?php wp_body_open(); ?>

<a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:bg-fg focus:text-bg focus:px-4 focus:py-2"><?php esc_html_e( 'Ir para o conteudo', 'wp-base' ); ?></a>

<?php get_template_part( 'templates-parts/header/site-header' ); ?>

<main id="main" class="flex-1">
