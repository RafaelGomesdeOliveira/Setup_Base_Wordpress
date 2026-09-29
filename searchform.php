<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="flex gap-2 max-w-md mx-auto">
	<label for="s" class="sr-only"><?php esc_html_e( 'Buscar', 'wp-base' ); ?></label>
	<input id="s" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Buscar...', 'wp-base' ); ?>" class="input flex-1">
	<button type="submit" class="btn"><?php esc_html_e( 'Buscar', 'wp-base' ); ?></button>
</form>
