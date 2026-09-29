# Field types cheat sheet (ACF Free 6.x)

Every field needs `key`, `label`, `name`, `type`. Add `instructions` and `required` (`1`) when useful. Keys follow `field_<context>_<section>_<name>`.

| Type | Extra settings worth setting | Read in helper | Output in template |
|---|---|---|---|
| `text` | `maxlength`, `placeholder` | `base_acf_text( $v )` | `esc_html()` |
| `textarea` | `rows`, `new_lines => ''` | `base_acf_text( $v )` | `nl2br( esc_html() )` |
| `wysiwyg` | `tabs => 'visual'`, `toolbar => 'basic'`, `media_upload => 0` | `(string) $v` | `wp_kses_post()` |
| `number` | `min`, `max`, `step` | `(int)` / `(float)` | `esc_html()` |
| `email` | — | `sanitize_email( $v )` | `esc_html()`, link with `esc_url( 'mailto:' . antispambot( $email ) )` |
| `url` | — | `(string) $v` | `esc_url()` |
| `link` | `return_format => 'array'` | `base_acf_link( $v )` | `esc_url( url )`, `esc_html( title )`, `target="_blank" rel="noopener"` |
| `image` | `return_format => 'id'`, `preview_size => 'medium'`, `mime_types => 'jpg, jpeg, png, webp'` | `base_acf_image_id( $v )` | `base_picture()` / `wp_get_attachment_image()` |
| `file` | `return_format => 'id'`, `mime_types => 'pdf'` | `base_acf_image_id( $v )` then `wp_get_attachment_url()` | `esc_url()` |
| `oembed` | — | `(string) $v` (already HTML) | `echo` as is, it comes from `wp_oembed_get()` |
| `true_false` | `ui => 1`, `default_value => 0` | `(bool) $v` | — |
| `select` / `radio` / `button_group` | `choices => array( 'value' => 'Rótulo PT' )`, `default_value` | whitelist: `in_array( $v, array_keys( $choices ), true )` | map value → CSS class in the helper |
| `checkbox` | `choices` | `array_values( (array) $v )` | — |
| `color_picker` | `enable_opacity => 0` | `sanitize_hex_color( $v )` | `esc_attr()` inside `style=""` |
| `date_picker` | `display_format => 'd/m/Y'`, `return_format => 'Y-m-d'` | `DateTimeImmutable::createFromFormat( 'Y-m-d', $v )` | `esc_html( wp_date( ... ) )` |
| `post_object` | `post_type => array( 'post' )`, `return_format => 'id'`, `allow_null => 1` | `base_acf_post_ids( $v, 1 )` | data from `base_get_card_data()` etc. |
| `relationship` | `post_type`, `filters => array( 'search' )`, `max`, `return_format => 'id'` | `base_acf_post_ids( $v, $max )` | idem |
| `taxonomy` | `taxonomy => 'category'`, `field_type => 'select'`, `return_format => 'id'`, `add_term => 0` | `(int)` / `array_map( 'intval', ... )` | `get_term_link()` + `esc_url()` |
| `group` | `layout => 'block'`, `sub_fields` | nested array | — |
| `tab` | `placement => 'top'` (no `name`) | — | — |
| `message` | `message` (admin-only help text, no `name`) | — | — |
| `accordion` | `open`, `multi_expand` (no `name`) | — | — |

Not available in Free: `repeater`, `flexible_content`, `gallery`, `clone`. See "Replacing the Repeater" in SKILL.md.

## Choices mapped to classes

Keep class names in the helper so Tailwind finds them in PHP and the client only picks a label:

```php
$themes = array(
	'light' => 'bg-bg text-fg',
	'dark'  => 'bg-fg text-bg',
);
$theme = isset( $themes[ $data['theme'] ?? '' ] ) ? $data['theme'] : 'light';

return array(
	'class' => $themes[ $theme ],
	// ...
);
```

## Location rules

```php
// Page template (path relative to the theme root)
array( 'param' => 'page_template', 'operator' => '==', 'value' => 'pages/template-about-us.php' )
// Static front page
array( 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' )
// Post type
array( 'param' => 'post_type', 'operator' => '==', 'value' => 'post' )
// Taxonomy term edit screen
array( 'param' => 'taxonomy', 'operator' => '==', 'value' => 'category' )
```

`location` is an array of OR-groups, each an array of AND-rules: `array( array( $rule_a, $rule_b ), array( $rule_c ) )` = (a AND b) OR c.

## hide_on_screen values

`permalink`, `the_content`, `excerpt`, `discussion`, `comments`, `revisions`, `slug`, `author`, `format`, `page_attributes`, `featured_image`, `categories`, `tags`, `send-trackbacks`.

`the_content` only hides the classic editor. For a page template made only of ACF sections, also turn off the block editor for it:

```php
function base_disable_block_editor_for_acf_templates( $use, $post ) {
	$templates = array( 'pages/template-about-us.php' );

	return in_array( get_page_template_slug( $post ), $templates, true ) ? false : $use;
}
add_filter( 'use_block_editor_for_post', 'base_disable_block_editor_for_acf_templates', 10, 2 );
```

## Reading values outside the current post

```php
base_field( 'name', 123 );                       // a post (avoid literal IDs: resolve them)
base_field( 'name', 'term_' . $term->term_id );  // a term
base_field( 'name', 'user_' . $user_id );        // a user
base_field( 'name', (int) get_option( 'page_on_front' ) ); // the static front page
```
