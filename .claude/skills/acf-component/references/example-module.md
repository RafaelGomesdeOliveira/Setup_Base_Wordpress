# Worked example: "O que nos move" (About Us values) driven by ACF

Starting point: `templates-parts/about-us/values.php` has three hardcoded items. Goal: the client edits the section title and the three items on the "Quem Somos" page.

Decisions:
- Location: the page using `pages/template-about-us.php` → `page_template` rule.
- Fixed count of 3 items → Repeater replacement with a loop (ACF Free).
- Section group `about_values` wraps everything, so `get_field( 'about_values' )` returns one array.

## 1. `inc/acf-about-values.php`

```php
<?php
/**
 * About Us "values" section: ACF fields on the Quem Somos page + data helper.
 * Rendered by templates-parts/about-us/values.php.
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;

// ACF Free has no Repeater: fixed number of items, generated in a loop.
define( 'BASE_ABOUT_VALUES_ITEMS', 3 );

/**
 * Field group of the section.
 */
function base_register_about_values_fields() {
	$items = array();

	for ( $i = 1; $i <= BASE_ABOUT_VALUES_ITEMS; $i++ ) {
		$items[] = array(
			'key'        => "field_about_values_item_{$i}",
			'label'      => "Item {$i}",
			'name'       => "item_{$i}",
			'type'       => 'group',
			'layout'     => 'block',
			'sub_fields' => array(
				array(
					'key'          => "field_about_values_item_{$i}_title",
					'label'        => 'Título',
					'name'         => 'title',
					'type'         => 'text',
					'instructions' => 'Deixe em branco para esconder o item.',
				),
				array(
					'key'       => "field_about_values_item_{$i}_text",
					'label'     => 'Texto',
					'name'      => 'text',
					'type'      => 'textarea',
					'rows'      => 3,
					'new_lines' => '',
				),
			),
		);
	}

	base_acf_add_group(
		array(
			'key'        => 'group_about_values',
			'title'      => 'Quem Somos — O que nos move',
			'style'      => 'seamless',
			'menu_order' => 10,
			'fields'     => array(
				array(
					'key'        => 'field_about_values',
					'label'      => 'O que nos move',
					'name'       => 'about_values',
					'type'       => 'group',
					'layout'     => 'block',
					'sub_fields' => array_merge(
						array(
							array(
								'key'         => 'field_about_values_title',
								'label'       => 'Título da seção',
								'name'        => 'title',
								'type'        => 'text',
								'placeholder' => 'O que nos move',
							),
						),
						$items
					),
				),
			),
			'location'   => array(
				array(
					array(
						'param'    => 'page_template',
						'operator' => '==',
						'value'    => 'pages/template-about-us.php',
					),
				),
			),
		)
	);
}
add_action( 'acf/include_fields', 'base_register_about_values_fields' );

/**
 * Section data with fallbacks. Null when no item is filled.
 *
 * @param int $post_id Page ID (0 = current post).
 * @return array{title: string, items: array<int, array{title: string, text: string}>}|null
 */
function base_get_about_values( $post_id = 0 ) {
	$data = base_field( 'about_values', $post_id ? $post_id : false );

	if ( ! is_array( $data ) ) {
		return null;
	}

	$items = array();

	for ( $i = 1; $i <= BASE_ABOUT_VALUES_ITEMS; $i++ ) {
		$item  = isset( $data[ "item_{$i}" ] ) && is_array( $data[ "item_{$i}" ] ) ? $data[ "item_{$i}" ] : array();
		$title = base_acf_text( $item['title'] ?? '' );

		if ( '' === $title ) {
			continue;
		}

		$items[] = array(
			'title' => $title,
			'text'  => base_acf_text( $item['text'] ?? '' ),
		);
	}

	if ( ! $items ) {
		return null;
	}

	$title = base_acf_text( $data['title'] ?? '' );

	return array(
		'title' => '' !== $title ? $title : 'O que nos move',
		'items' => $items,
	);
}
```

## 2. `templates-parts/about-us/values.php`

```php
<?php
/**
 * Mission, vision and values. Data from base_get_about_values() (inc/acf-about-values.php).
 *
 * @package wp-base
 */

defined( 'ABSPATH' ) || exit;

$values = base_get_about_values();

if ( ! $values ) {
	return;
}
?>
<section class="border-t border-line" aria-labelledby="values-title">
	<div class="w-full max-w-7xl mx-auto px-5 lg:px-10 py-16 lg:py-24">
		<h2 id="values-title" class="text-h2 lg:text-h2-desktop font-semibold mb-10"><?php echo esc_html( $values['title'] ); ?></h2>

		<div class="grid gap-8 md:grid-cols-3">
			<?php foreach ( $values['items'] as $item ) : ?>
				<div class="border border-line rounded-md p-6">
					<h3 class="text-h3 lg:text-h3-desktop font-semibold"><?php echo esc_html( $item['title'] ); ?></h3>
					<?php if ( $item['text'] ) : ?>
						<p class="text-h4 text-muted mt-3"><?php echo nl2br( esc_html( $item['text'] ) ); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
```

## 3. `functions.php`

```php
require_once BASE_THEME_DIR . '/inc/acf.php'; // ACF shared helpers
require_once BASE_THEME_DIR . '/inc/acf-about-values.php'; // About Us: values section
```

The page template (`pages/template-about-us.php`) does not change: it already calls `get_template_part( 'templates-parts/about-us/values' )`.

## Variation: section with image + link (hero)

Sub-fields for a hero usually are `kicker` (text), `title` (text), `text` (textarea), `link` (link, `array`), `image` + `image_mobile` (image, `id`). Helper excerpt:

```php
$link = base_acf_link( $data['link'] ?? null );

return array(
	'title'     => base_acf_text( $data['title'] ?? '' ) ?: get_the_title( $post_id ),
	'text'      => base_acf_text( $data['text'] ?? '' ),
	'link'      => $link,
	'image_id'  => base_acf_image_id( $data['image'] ?? 0 ) ?: (int) get_post_thumbnail_id( $post_id ),
	'mobile_id' => base_acf_image_id( $data['image_mobile'] ?? 0 ),
);
```

Template excerpt:

```php
<?php echo base_picture( $hero['image_id'], $hero['mobile_id'], 'full', array( 'class' => 'w-full h-auto object-cover', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); // Safe HTML from WP. ?>

<?php if ( $hero['link'] ) : ?>
	<a href="<?php echo esc_url( $hero['link']['url'] ); ?>" class="btn"<?php echo $hero['link']['target'] ? ' target="_blank" rel="noopener"' : ''; ?>>
		<?php echo esc_html( $hero['link']['title'] ?: 'Saiba mais' ); ?>
	</a>
<?php endif; ?>
```

Use `loading => eager` + `fetchpriority => high` only for the first image above the fold; everything else keeps the default lazy loading.
