# Theme conventions (themeBase and themes forked from it)

Classic WordPress theme (PHP templates, no block theme/FSE), styled with Tailwind CSS v4, editable content through ACF Free. Follow the existing code before inventing a new pattern; when a rule below conflicts with the code, point it out instead of silently picking one.

## Structure

- `functions.php` only defines constants (`BASE_THEME_DIR`, `BASE_THEME_URI`, `BASE_THEME_VERSION`) and `require_once`s files from `inc/`, **one file per responsibility**: `setup.php` (supports, menus, image sizes), `enqueue.php`, `helpers.php`, `nav-menu.php`, and one module per feature (`acf-<context>-<section>.php`, `cpt-<name>.php`, `<integration>.php`). No hooks or logic directly in `functions.php`.
- Root templates (`front-page.php`, `home.php`, `page.php`, `single.php`, `archive.php`, `search.php`, `404.php`, `index.php`) stay thin: `get_header()`, a sequence of `get_template_part()`, `get_footer()`.
- Custom page templates go in `pages/template-<name>.php`; each section in `templates-parts/<context>/<section>.php`. `header.php`/`footer.php` only wrap `templates-parts/header/site-header.php` and `templates-parts/footer/site-footer.php`.
- Data goes into template parts through the third argument of `get_template_part( $slug, null, $args )`. Queries, field reads and data shaping live in helpers in `inc/` that return arrays; templates only render. Reuse existing builders (`base_get_card_data()`, `base_blog_title()`, `base_blog_url()`) before writing new ones.
- Content the client must edit comes from ACF fields registered in code — use the `acf-component` skill. Never create ACF fields only in the admin and never read data from a hardcoded post/page ID.

## Naming and language

- Functions, constants, CPTs, option names and script/style handles use the theme prefix: `base_` / `BASE_` / `base-` in themeBase. A client project forked from it uses its own prefix — check `functions.php` for the current one and keep it consistent.
- Text domain: `wp-base` (or the client theme's own).
- **English** for file and folder names (kebab-case), functions and variables (snake_case), CSS classes, HTML ids and code comments.
- **Portuguese** only for what the client or visitor reads: `Template Name:` headers, admin labels (menus, CPT labels, ACF labels/instructions), and site copy.

## PHP

- Every file in `inc/` and `templates-parts/` starts with a docblock (`@package wp-base`) and `defined( 'ABSPATH' ) || exit;`.
- WordPress Coding Standards: tabs, spaces inside parentheses, `array()` syntax, docblocks on functions with `@param`/`@return`.
- Escape late, at output: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses_post()` for rich HTML. HTML returned by WP (`wp_get_attachment_image()`, `get_the_post_thumbnail()`, `wp_nav_menu()`) is already safe — say so in a short comment when echoing it.
- Sanitize input (`sanitize_text_field()`, `absint()`...) and verify nonces/capabilities on anything that saves data. Use `$wpdb->prepare()` for any custom SQL.
- Secondary `WP_Query`: always `wp_reset_postdata()`; add `'no_found_rows' => true` when there is no pagination. Never use `query_posts()`.
- The theme must not fatal when a plugin is off: guard plugin functions with `function_exists()` and hide the section instead.
- Template parts bail early (`return;`) on empty data instead of printing empty wrappers.

## CSS (Tailwind v4)

- Configuration lives in `src/css/input.css` — there is no `tailwind.config.js`. `@source "../../**/*.php"` scans every PHP file, so write full class names (no string concatenation like `'text-' . $color`).
- `assets/css/output.css` is generated: never edit it by hand. Run `npm run dev` while working.
- Use the design tokens (`bg-bg`, `text-fg`, `text-muted`, `border-line`, `bg-primary`) and the typography utilities in pairs (`text-h2 lg:text-h2-desktop`). New colors, fonts or type sizes go into `@theme` / `@utility` in `input.css`, not as arbitrary values (`text-[#123456]`) scattered in templates.
- Shared component styles that do not fit utilities go into `@layer components` in `input.css`.
- Section container: `w-full max-w-7xl mx-auto px-5 lg:px-10`. Mobile first; `lg` (64rem) is the mobile/desktop switch, mirrored in `assets/js/main.js`.

## JavaScript and assets

- Vanilla JS in `assets/js/main.js`, no jQuery. Hook behavior to markup through `data-*` attributes and keep ARIA state (`aria-expanded`, `aria-controls`) in sync.
- Enqueue only in `inc/enqueue.php`, versioned with `base_asset_version()` (filemtime). Assets used by a single template are enqueued conditionally there (`is_front_page()`, `is_page_template()`...).
- Image sizes are registered in `inc/setup.php`, always with a `-2x` pair of exactly double dimensions so WP builds the srcset.

## Menus

- Locations are registered in `inc/setup.php` (`primary`, `footer`) and rendered with `wp_nav_menu()` using `'container' => false` and `'fallback_cb' => false`.
- Dropdown menus use the `nav-menu` class: it enables the submenu toggle buttons (`inc/nav-menu.php`), the CSS in `input.css` and the JS in `main.js`. Do not write a custom walker for this.

## Accessibility

- One `<h1>` per page. Sections with a heading use `aria-labelledby`; interactive toggles are `<button type="button">` with an `aria-label` when they have no text.
- Images need a meaningful `alt` (fallback to the post/section title) or `alt=""` when decorative.
