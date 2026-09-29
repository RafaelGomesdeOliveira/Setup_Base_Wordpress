---
name: acf-component
description: Build an editable theme component/section whose content comes from ACF (Advanced Custom Fields) fields registered in PHP code. Use when creating or changing a section, block, banner, hero, card list or page template that the client must edit in the WP admin; when adding, renaming or removing ACF fields; when moving hardcoded texts/images into the admin; or when migrating field groups created in the ACF admin UI into code. Triggers (PT) — "componente com ACF", "campos personalizados", "deixar editável no admin", "seção editável", "criar campos", "cadastrar campos do ACF", "get_field".
---

# ACF component (fields registered in code)

Company standard: **every ACF field group is registered in PHP** with `acf_add_local_field_group`, versioned with the theme. Never create field groups only through the ACF admin UI and never read fields from hardcoded post IDs — those break on every fresh install and every database migration.

Projects use **ACF Free** (plugin folder `wp-content/plugins/advanced-custom-fields`). Free has **no Repeater, Flexible Content, Gallery, Clone, Options Page or ACF Blocks**. If the folder is `advanced-custom-fields-pro`, Pro features are allowed — ask before relying on them anyway, since the same code may ship to a Free install.

## Architecture: one module per section

```
inc/acf.php                              shared helpers (base_acf_add_group, base_field, base_picture...)
inc/acf-<context>-<section>.php          1) registers the fields  2) exposes base_get_<context>_<section>()
templates-parts/<context>/<section>.php  calls the helper, bails on empty data, escapes and renders
pages/template-<context>.php             (or front-page.php...) just calls get_template_part()
```

- The module has **no HTML**. The helper returns a plain array (strings, IDs, URLs) with fallbacks already applied, or `null` when there is nothing to show.
- The template part has **no `get_field()`**. It only reads the helper's array.
- `functions.php` requires `inc/acf.php` first, then each module.
- Reusable components (cards, buttons) receive their array through `get_template_part( $slug, null, $args )`; section parts call their own helper.

## Workflow

1. **Bootstrap once per project.** If `inc/acf.php` does not exist, copy [templates/acf.php](templates/acf.php) there, switch the `base_` prefix and `@package` if the project uses another one, and add `require_once BASE_THEME_DIR . '/inc/acf.php';` to `functions.php` before any `inc/acf-*.php`. Reuse its helpers instead of re-implementing them.
2. **Decide where the data lives** — this is the `location` rule:

   | Content belongs to... | Location | Read with |
   |---|---|---|
   | A page using a custom template | `page_template == pages/template-x.php` | `base_field( 'name' )` inside the loop, or pass the page ID |
   | The static front page | `page_type == front_page` | `base_field( 'name', (int) get_option( 'page_on_front' ) )` |
   | Every post / every item of a CPT | `post_type == post` / `== <cpt>` | `base_field( 'name', $post_id )` |
   | A category / taxonomy term | `taxonomy == category` | `base_field( 'name', 'term_' . $term_id )` |
   | Site-wide (header, footer, contacts, socials) | singleton settings CPT — Free has no Options Page | see [references/global-settings.md](references/global-settings.md) |
   | An unbounded list (team, testimonials, FAQ) | a private CPT, one post per item, ordered by `menu_order` | `WP_Query` in the helper |

3. **Create the module** `inc/acf-<context>-<section>.php` following [references/example-module.md](references/example-module.md) (read it before writing the first module in a session). Field arrays per type, return formats and how to escape each one: [references/field-types.md](references/field-types.md).
4. **Create/update the template part** and call it from the page template.
5. **Verify** (checklist at the end).

## Field conventions

- **One top-level `group` field per section**, `name` = `<context>_<section>` (e.g. `about_values`), `style` `seamless` on the field group. All fields of every group on the same post share one meta namespace; nesting under a section group prevents collisions and `get_field( 'about_values' )` returns the whole section as one array.
- **Keys** are globally unique and permanent: `group_<context>_<section>` for the group, `field_<context>_<section>_<name>` for fields (sub-field keys include the parent path). Build repeated sub-fields with a function that takes a key prefix instead of copy-pasting arrays.
- **Names** in English snake_case. **Labels, instructions, placeholders and choices** in Portuguese — the client reads them.
- Write `instructions` whenever a field is not obvious: recommended image size, character limit, what happens when it is left empty.
- Prefer these return formats: image/file → `id`, link → `array`, post_object/relationship → `id`, taxonomy → `id`, true_false → default, select/radio → `value`.
- Always pair a desktop image with an optional mobile image (`image` + `image_mobile`) when the layout changes between breakpoints; render both with `base_picture()`.
- Use `hide_on_screen` to remove editor boxes the template ignores (e.g. `the_content`, `excerpt`, `featured_image`).

## Replacing the Repeater (ACF Free)

- **Fixed, small count (≤ 6: three pillars, four numbers):** generate `item_1…item_N` group fields in a `for` loop in the registration, and in the helper loop the same count, skipping items whose main field is empty. Keep the count in one constant.
- **Unbounded or client-managed list:** private CPT (`public => false, show_ui => true`, `supports => title, page-attributes`) with its own field group; the helper queries it ordered by `menu_order`.
- Never create numbered fields by hand (`titulo_1`, `titulo_2`, `texto_1`...) or duplicate markup per item.

## Rules

- Register fields in a function hooked to `acf/include_fields`; guard with `function_exists` (the helper `base_acf_add_group()` already does).
- The theme must render without ACF active: read fields only through `base_field()` (or guard `get_field`), and let the section disappear when data is empty — never print empty wrappers or PHP notices.
- Every value leaves PHP escaped at output: `esc_html()` text, `nl2br( esc_html() )` textarea (`new_lines` `''`), `wp_kses_post()` wysiwyg, `esc_url()` URLs, `esc_attr()` attributes. `base_picture()`/`wp_get_attachment_image()` output is already safe.
- Fallbacks live in the helper, not in the template: empty override → post title/excerpt/featured image, empty mobile image → desktop image, empty alt → title.
- Do not use `the_field()`, `have_rows()`/`the_sub_field()` loops in templates, or `get_field()` with a literal post ID.
- Keep one module per section; do not grow a catch-all `acf-fields.php`.

## Changing fields that already have content

- A value is stored as post meta under the field **name** (plus `_name` → key). Renaming a `name` or moving a field into another group **orphans existing content**; changing a `key` breaks the link between stored values and the field definition (values come back raw or not at all). Treat keys and names as permanent once the site has content.
- To rename safely: keep the old name, or add the new field and migrate once (read old meta → `update_field()` new → delete old) with a one-off admin action, then remove the migration. Tell the user before doing it.
- Removing a field leaves its meta in the database; that is fine.

## Migrating a group created in the admin

1. In ACF → Tools → Export, select the group and use **Generate PHP**.
2. Paste into a new module, rewrite keys/names to the conventions **only if the site has no content yet** (otherwise keep the original `name`s and keys), switch to `base_acf_add_group()` and the section-group structure where possible.
3. Move the reading code into the helper and the markup into a template part.
4. Delete the database version of the group in ACF → Field Groups so there are not two definitions with the same key.

## Verification checklist

- [ ] `php -l` passes on every created/edited file (`/opt/lampp/bin/php -l <file>` on XAMPP).
- [ ] The field group shows up on the right edit screen, with PT labels and instructions, and the unused editor boxes are hidden.
- [ ] Front end renders with fields filled, with fields empty (section hidden or fallbacks used, no notices), and with ACF deactivated (no fatal error).
- [ ] Mobile and desktop images swap at the `lg` breakpoint.
- [ ] No `get_field()` in templates, no hardcoded post IDs, no numbered duplicate fields.
