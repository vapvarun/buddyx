# Custom Google Fonts Compatibility + Picker — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Restore Kirki-era custom Google Font support on BuddyX's in-house Customizer framework — a bundled catalog, a restored picker, and a value-driven dynamic loader so existing sites' saved font selections render again.

**Architecture:** Three pieces mirroring Kirki — a bundled `google-fonts.json` catalog (admin-only), a grouped/searchable picker in the in-house `Typography` control, and a rewritten `get_google_fonts()` that scans the saved typography theme_mods and feeds BuddyX's *existing* Webfont loader. The loader is value-driven (loads what is in the DB), so fonts absent from the catalog still work.

**Tech Stack:** PHP (WordPress theme, `BuddyX\Buddyx` namespace), vanilla JS (Customizer controls), JSON data file. No build step — `customizer-controls.js`/`.css` and the JSON are loaded directly; PHP needs no compile. Verification via `wp eval` (PHP logic) and the Playwright MCP browser tools (UI/front-end), plus the repo's pre-commit hook (php-lint + stylelint + WPCS).

**Working dir:** `wp-content/themes/buddyx` — all paths below are relative to it. Branch: `5.1.0`.

---

### Task 1: Google Fonts catalog — data file + reader class

**Files:**
- Create: `inc/Fonts/data/google-fonts.json`
- Create: `inc/Fonts/Google_Fonts_Catalog.php`
- Modify: `inc/Fonts/Component.php` (require the new class in the bootstrap)

- [ ] **Step 1: Copy the catalog data from the installed Kirki plugin**

The Kirki plugin is installed (inactive) at `wp-content/plugins/kirki`. Its catalog is the exact dataset we want.

Run:
```bash
mkdir -p inc/Fonts/data
cp ../../plugins/kirki/customizer/packages/utils/googlefonts/src/webfonts.json inc/Fonts/data/google-fonts.json
```
Expected: `inc/Fonts/data/google-fonts.json` exists (~195 KB). Confirm shape:
```bash
php -r '$d=json_decode(file_get_contents("inc/Fonts/data/google-fonts.json"),true); echo count($d)." families\n"; $k=array_key_first($d); echo $k." => ".json_encode($d[$k])."\n";'
```
Expected: `~1358 families` and a sample like `ABeeZee => {"family":"ABeeZee","category":"sans-serif","variants":["italic","regular"]}`.

- [ ] **Step 2: Create the reader class**

Create `inc/Fonts/Google_Fonts_Catalog.php`:
```php
<?php
/**
 * BuddyX\Buddyx\Fonts\Google_Fonts_Catalog
 *
 * Reads the bundled Google Fonts catalog (inc/Fonts/data/google-fonts.json).
 * Used by the Customizer Typography control to populate the font picker.
 * Loaded only in the customizer admin context — never on the front end.
 *
 * @package buddyx
 */

namespace BuddyX\Buddyx\Fonts;

defined( 'ABSPATH' ) || exit;

/**
 * Google_Fonts_Catalog
 */
class Google_Fonts_Catalog {

	/**
	 * Parsed catalog cache.
	 *
	 * @var array|null
	 */
	protected static $catalog = null;

	/**
	 * Read and cache the catalog JSON.
	 *
	 * @return array<string, array> Family name => { family, category, variants }.
	 */
	public static function get_catalog(): array {
		if ( null !== self::$catalog ) {
			return self::$catalog;
		}
		$file = __DIR__ . '/data/google-fonts.json';
		if ( ! is_readable( $file ) ) {
			self::$catalog = array();
			return self::$catalog;
		}
		$data          = json_decode( (string) file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		self::$catalog = is_array( $data ) ? $data : array();
		return self::$catalog;
	}

	/**
	 * Family name => label map for the picker (family name is both key and label).
	 *
	 * @return array<string, string>
	 */
	public static function get_family_choices(): array {
		$out = array();
		foreach ( self::get_catalog() as $family => $info ) {
			$name         = isset( $info['family'] ) ? (string) $info['family'] : (string) $family;
			$out[ $name ] = $name;
		}
		return $out;
	}

	/**
	 * Whether a given family name exists in the bundled catalog.
	 *
	 * @param string $family Family name.
	 * @return bool
	 */
	public static function is_google_font( string $family ): bool {
		if ( '' === $family ) {
			return false;
		}
		$catalog = self::get_catalog();
		return isset( $catalog[ $family ] );
	}
}
```

- [ ] **Step 3: Require the class from the Fonts component bootstrap**

In `inc/Fonts/Component.php`, immediately after the `defined( 'ABSPATH' ) || exit;` line (top of file, before the `class Component` declaration), add:
```php
require_once __DIR__ . '/Google_Fonts_Catalog.php';
```
This is deterministic (the Fonts `Component` is always loaded) and matches the existing `require_once` pattern used in `inc/Customizer_Framework/Component.php`.

- [ ] **Step 4: Verify the class loads and reads the catalog**

Run:
```bash
wp eval 'echo count( \BuddyX\Buddyx\Fonts\Google_Fonts_Catalog::get_family_choices() ) . " families; is_google_font(Poppins)=" . var_export( \BuddyX\Buddyx\Fonts\Google_Fonts_Catalog::is_google_font( "Poppins" ), true ) . "\n";'
```
Expected: `~1358 families; is_google_font(Poppins)=true`

- [ ] **Step 5: Lint and commit**

Run:
```bash
php -l inc/Fonts/Google_Fonts_Catalog.php
php -l inc/Fonts/Component.php
git add inc/Fonts/data/google-fonts.json inc/Fonts/Google_Fonts_Catalog.php inc/Fonts/Component.php
git commit -m "feat(fonts): bundle Google Fonts catalog + reader class"
```
Expected: both `php -l` print "No syntax errors detected"; commit succeeds (pre-commit php-lint passes).

---

### Task 2: Dynamic font loader — `get_google_fonts()` scans saved typography theme_mods

**Files:**
- Modify: `inc/Fonts/Component.php` — replace the body of `get_google_fonts()`

This is the compat-critical task: it makes every existing site's saved font selection load again.

- [ ] **Step 1: Replace `get_google_fonts()` with the dynamic version**

In `inc/Fonts/Component.php`, replace the entire `get_google_fonts()` method (currently returns an empty static `$google_fonts = array();`) with:
```php
	/**
	 * Returns the Google Fonts in use, collected from saved typography theme_mods.
	 *
	 * Value-driven: whatever font-family a site has saved in its typography
	 * settings is loaded — whether or not it is in the bundled catalog — so
	 * selections made under the old Kirki picker keep rendering. Self-hosted
	 * theme fonts (Inter, Newsreader, System UI) and bare CSS generics are
	 * skipped; they need no Google request.
	 *
	 * @return array Associative array of $font_name => $font_variants pairs.
	 */
	protected function get_google_fonts(): array {
		if ( is_array( $this->google_fonts ) ) {
			return $this->google_fonts;
		}

		// Typography setting IDs whose saved value may carry a font-family.
		// Hardcoded because Customizer_Framework fields are only registered in
		// the customizer context, not on front-end requests.
		$typography_settings = array(
			'site_title_typography_option',
			'site_tagline_typography_option',
			'site_sub_header_typography',
			'h1_typography_option',
			'h2_typography_option',
			'h3_typography_option',
			'h4_typography_option',
			'h5_typography_option',
			'h6_typography_option',
			'menu_typography_option',
			'sub_menu_typography_option',
			'typography_option',
		);

		// Self-hosted theme.json families + bare CSS generics — never fetched.
		$skip = array(
			'', 'inter', 'newsreader', 'system', 'system-ui',
			'sans-serif', 'serif', 'monospace', 'cursive', 'fantasy',
		);

		$collected = array();
		foreach ( $typography_settings as $setting_id ) {
			$value = get_theme_mod( $setting_id );
			if ( ! is_array( $value ) || empty( $value['font-family'] ) ) {
				continue;
			}
			$family = trim( (string) $value['font-family'] );
			if ( in_array( strtolower( $family ), $skip, true ) ) {
				continue;
			}
			$variant = '';
			if ( ! empty( $value['variant'] ) ) {
				$variant = (string) $value['variant'];
			} elseif ( ! empty( $value['font-weight'] ) ) {
				$variant = (string) $value['font-weight'];
			}
			if ( ! isset( $collected[ $family ] ) ) {
				$collected[ $family ] = array();
			}
			if ( '' !== $variant && ! in_array( $variant, $collected[ $family ], true ) ) {
				$collected[ $family ][] = $variant;
			}
		}

		/**
		 * Filters the Google Fonts BuddyX loads.
		 *
		 * @param array $collected Associative array of $font_name => $font_variants pairs.
		 */
		$this->google_fonts = (array) apply_filters( 'buddyx_google_fonts', $collected );

		return $this->google_fonts;
	}
```
Note: `get_google_fonts_url()` already handles an empty `$font_variants` array (falls back to family-only) and returns `''` when the whole array is empty — no change needed there.

- [ ] **Step 2: Verify with a seeded theme_mod (PHP level)**

Run:
```bash
wp eval '
set_theme_mod( "h1_typography_option", array( "font-family" => "Poppins", "variant" => "600" ) );
set_theme_mod( "typography_option", array( "font-family" => "inter", "variant" => "400" ) );
$c = new \BuddyX\Buddyx\Fonts\Component();
$m = new ReflectionMethod( $c, "get_google_fonts" );
$m->setAccessible( true );
echo json_encode( $m->invoke( $c ) ) . "\n";
'
```
Expected: `{"Poppins":["600"]}` — Poppins collected with its weight; `inter` skipped as self-hosted.

- [ ] **Step 3: Verify the front end actually loads the font**

Use the Playwright MCP browser tools:
1. `browser_navigate` to `http://buddyx.local/?nocache=gf1`
2. `browser_evaluate`: check that a Google Fonts request exists OR an `@font-face`/`<link>` for Poppins is present, and that `getComputedStyle(document.querySelector('h1,.entry-title')).fontFamily` contains `Poppins`.

Expected: Poppins is requested/loaded and the H1 computes to Poppins.

- [ ] **Step 4: Reset the seeded theme_mods**

Run:
```bash
wp eval 'remove_theme_mod( "h1_typography_option" ); remove_theme_mod( "typography_option" );'
```
Expected: no output (clean).

- [ ] **Step 5: Lint and commit**

Run:
```bash
php -l inc/Fonts/Component.php
git add inc/Fonts/Component.php
git commit -m "feat(fonts): load Google fonts dynamically from saved typography settings"
```
Expected: "No syntax errors detected"; commit succeeds.

---

### Task 3: Restore the picker — grouped `available_font_families()` + catalog enqueue + optgroup JS

**Files:**
- Modify: `inc/Customizer_Framework/Controls/Typography.php` — `available_font_families()` returns a grouped structure
- Modify: `inc/Customizer_Framework/Component.php` — confirm catalog availability in the customizer-controls context
- Modify: `inc/Customizer_Framework/assets/customizer-controls.js` — render the family `<select>` as optgroups

This task changes the `fontFamilies` data shape and the JS that consumes it together, so the customizer is never left half-broken.

- [ ] **Step 1: Inspect the current family-select population in the JS**

Open `inc/Customizer_Framework/assets/customizer-controls.js` and locate the `buddyx-typography` control's `ready:` handler — specifically where it reads `this.params.fontFamilies` (or `params.default` / `params`) and fills `.buddyx-typo-family`. Note the exact variable names and how options are currently appended (the variable holding the saved value, e.g. `initial`; the select element variable, e.g. `familyEl`). The Step 4 code adapts to that block.

- [ ] **Step 2: Make `available_font_families()` return a grouped structure**

In `inc/Customizer_Framework/Controls/Typography.php`, replace the `available_font_families()` method with:
```php
	/**
	 * Font families for the picker, grouped for <optgroup> rendering.
	 *
	 * Returns:
	 *   array(
	 *     'default' => '',                       // value of the "Default (theme)" entry
	 *     'groups'  => array(
	 *       array( 'label' => 'Theme fonts',  'fonts' => array( slug => label, ... ) ),
	 *       array( 'label' => 'Google Fonts', 'fonts' => array( name => name, ... ) ),
	 *     ),
	 *   )
	 *
	 * Theme fonts come from theme.json fontFamilies (self-hosted); Google fonts
	 * come from the bundled catalog. The JS prepends a "Default (theme)" option.
	 *
	 * @return array
	 */
	protected static function available_font_families(): array {
		$theme_json = function_exists( 'wp_get_global_settings' ) ? wp_get_global_settings() : array();
		$families   = $theme_json['typography']['fontFamilies']['theme'] ?? array();
		$theme      = array();
		foreach ( $families as $f ) {
			if ( isset( $f['slug'], $f['name'] ) ) {
				$theme[ $f['slug'] ] = $f['name'];
			}
		}
		if ( empty( $theme ) ) {
			$theme = array(
				'system' => 'System UI',
				'inter'  => 'Inter',
			);
		}

		$google = array();
		if ( class_exists( '\\BuddyX\\Buddyx\\Fonts\\Google_Fonts_Catalog' ) ) {
			$google = \BuddyX\Buddyx\Fonts\Google_Fonts_Catalog::get_family_choices();
		}

		$groups = array(
			array(
				'label' => esc_html__( 'Theme fonts', 'buddyx' ),
				'fonts' => $theme,
			),
		);
		if ( ! empty( $google ) ) {
			$groups[] = array(
				'label' => esc_html__( 'Google Fonts', 'buddyx' ),
				'fonts' => $google,
			);
		}

		return array(
			'default' => '',
			'groups'  => $groups,
		);
	}
```

- [ ] **Step 3: Confirm the catalog class is available in the customizer-controls context**

The `Typography` control's `to_json()` runs when controls render, and `available_font_families()` references `\BuddyX\Buddyx\Fonts\Google_Fonts_Catalog`. That class is required by `inc/Fonts/Component.php` (Task 1, Step 3), which loads on every request, so it is already available — the `class_exists()` guard in Step 2 makes this safe regardless. No code change needed in `inc/Customizer_Framework/Component.php`; the Step 5 browser check confirms the Google group appears. If it does not, add this line as the first statement of `available_font_families()`:
```php
		require_once get_template_directory() . '/inc/Fonts/Google_Fonts_Catalog.php';
```

- [ ] **Step 4: Update the JS to render optgroups**

In `inc/Customizer_Framework/assets/customizer-controls.js`, in the `buddyx-typography` control's family-select population (found in Step 1), replace the option-building logic with this (adapt `familyEl` / `params` / `initial` to the variable names found in Step 1):
```js
			// Build the family <select>: "Default (theme)" + grouped optgroups.
			var fontData = params.fontFamilies || {};
			while (familyEl.firstChild) {
				familyEl.removeChild(familyEl.firstChild);
			}
			var defOpt = document.createElement('option');
			defOpt.value = fontData.default || '';
			defOpt.textContent = 'Default (theme)';
			familyEl.appendChild(defOpt);
			(fontData.groups || []).forEach(function (group) {
				var og = document.createElement('optgroup');
				og.label = group.label;
				Object.keys(group.fonts || {}).forEach(function (val) {
					var opt = document.createElement('option');
					opt.value = val;
					opt.textContent = group.fonts[val];
					og.appendChild(opt);
				});
				familyEl.appendChild(og);
			});
			// Reflect the saved value.
			familyEl.value = (initial && initial['font-family']) ? initial['font-family'] : '';
```
Keep the rest of the typography `ready:` handler (weight/size/etc.) unchanged.

- [ ] **Step 5: Verify the picker in the browser**

Use the Playwright MCP browser tools:
1. `browser_navigate` to `http://buddyx.local/wp-admin/customize.php`, wait for load.
2. `browser_evaluate`:
```js
() => new Promise(res => {
  wp.customize.control('typography_option', c => {
    var sel = c.container[0].querySelector('.buddyx-typo-family');
    var groups = [...sel.querySelectorAll('optgroup')].map(g => g.label);
    res({ optionCount: sel.options.length, groups: groups, hasPoppins: !!sel.querySelector('option[value="Poppins"]') });
  });
})
```
Expected: `optionCount` > 1300, `groups` includes `"Theme fonts"` and `"Google Fonts"`, `hasPoppins: true`.

- [ ] **Step 6: Commit**

Run:
```bash
php -l inc/Customizer_Framework/Controls/Typography.php
php -l inc/Customizer_Framework/Component.php
git add inc/Customizer_Framework/Controls/Typography.php inc/Customizer_Framework/Component.php inc/Customizer_Framework/assets/customizer-controls.js
git commit -m "feat(customizer): restore full Google Fonts picker in typography controls"
```
Expected: "No syntax errors detected"; commit succeeds (pre-commit php-lint passes).

---

### Task 4: Searchable family select + inject saved-but-missing value

**Files:**
- Modify: `inc/Customizer_Framework/assets/customizer-controls.js` — add a search filter above the family select; inject the saved value if absent
- Modify: `inc/Customizer_Framework/assets/customizer-controls.css` — style the search input

A 1,300-option `<select>` is unusable without search. This task is additive — it does not change the data shape.

- [ ] **Step 1: Add the search input + filter + missing-value injection to the JS**

In `inc/Customizer_Framework/assets/customizer-controls.js`, in the `buddyx-typography` `ready:` handler, immediately AFTER the optgroup-building block from Task 3 Step 4, add (adapt `familyEl` / `initial` to the Step 1 names):
```js
			// If the saved value isn't in the catalog (e.g. an older Kirki
			// pick), inject it so the control reflects reality.
			var savedFam = (initial && initial['font-family']) ? initial['font-family'] : '';
			if (savedFam && !familyEl.querySelector('option[value="' + window.CSS.escape(savedFam) + '"]')) {
				var custom = document.createElement('optgroup');
				custom.label = 'Saved';
				var cOpt = document.createElement('option');
				cOpt.value = savedFam;
				cOpt.textContent = savedFam;
				custom.appendChild(cOpt);
				familyEl.insertBefore(custom, familyEl.children[1] || null);
				familyEl.value = savedFam;
			}
			// Searchable filter above the family select.
			var search = document.createElement('input');
			search.type = 'search';
			search.className = 'buddyx-typo-family-search';
			search.placeholder = 'Search fonts…';
			familyEl.parentNode.insertBefore(search, familyEl);
			search.addEventListener('input', function () {
				var q = search.value.toLowerCase();
				[...familyEl.querySelectorAll('optgroup')].forEach(function (og) {
					var anyVisible = false;
					[...og.querySelectorAll('option')].forEach(function (opt) {
						var match = opt.textContent.toLowerCase().indexOf(q) !== -1;
						opt.hidden = !match;
						if (match) { anyVisible = true; }
					});
					og.hidden = !anyVisible;
				});
			});
```

- [ ] **Step 2: Style the search input**

In `inc/Customizer_Framework/assets/customizer-controls.css`, add (near the other `.buddyx-typo-*` rules):
```css
.buddyx-typo-family-search {
	width: 100%;
	margin: 0 0 4px;
	padding: 4px 6px;
	border: 1px solid #c3c4c7;
	border-radius: 4px;
	font-size: 13px;
}
```

- [ ] **Step 3: Verify search + saved-value injection in the browser**

Use the Playwright MCP browser tools:
1. Seed an off-catalog value: `wp eval 'set_theme_mod( "h2_typography_option", array( "font-family" => "SomeOldFont", "variant" => "400" ) );'`
2. `browser_navigate` to `http://buddyx.local/wp-admin/customize.php`, wait for load.
3. `browser_evaluate`: focus the `h2_typography_option` control, confirm `.buddyx-typo-family` has an option with value `SomeOldFont` and it is selected, and that a `.buddyx-typo-family-search` input exists.
4. `browser_evaluate`: set the search input value to `pop`, dispatch an `input` event, confirm the `Poppins` option is visible and unrelated options are `hidden`.
5. Reset: `wp eval 'remove_theme_mod( "h2_typography_option" );'`

Expected: saved off-catalog value present + selected; search filters the list.

- [ ] **Step 4: Commit**

Run:
```bash
git add inc/Customizer_Framework/assets/customizer-controls.js inc/Customizer_Framework/assets/customizer-controls.css
git commit -m "feat(customizer): searchable font family select + preserve off-catalog saved values"
```
Expected: commit succeeds (pre-commit stylelint --fix + php-lint pass).

---

### Task 5: Changelog + full regression smoke

**Files:**
- Modify: `readme.txt` — changelog entry

- [ ] **Step 1: Add the changelog entry**

In `readme.txt`, under the current in-development version heading, add these bullets in the project's WooCommerce-style action-prefix format (New first, then Improve, Fix):
```
* New      - Customizer typography controls now offer the full Google Fonts library again, with a searchable picker.
* Improve  - New installs default to the self-hosted Inter base font; Newsreader stays for editorial accents.
* Fix      - Custom Google Font selections from older versions now load again - the theme reads each saved typography setting and loads the chosen font, self-hosting it when "Load Google Fonts Locally" is enabled.
```
Match the existing changelog's whitespace/padding exactly (8-char-padded action labels, regular hyphens, no em-dashes, no emoji).

- [ ] **Step 2: Full regression smoke (browser)**

Use the Playwright MCP browser tools and `wp eval`:

1. **New-install path** — `wp eval 'foreach(["site_title_typography_option","site_tagline_typography_option","site_sub_header_typography","h1_typography_option","h2_typography_option","h3_typography_option","h4_typography_option","h5_typography_option","h6_typography_option","menu_typography_option","sub_menu_typography_option","typography_option"] as $s){ remove_theme_mod($s); }'` → `browser_navigate` to `http://buddyx.local/?nocache=smoke1` → `browser_evaluate` confirms NO `fonts.googleapis`/`fonts.gstatic` request and body computes to Inter.

2. **Compat path** — `wp eval 'set_theme_mod("h1_typography_option", array("font-family"=>"Poppins","variant"=>"600"));'` → `browser_navigate` to `http://buddyx.local/?nocache=smoke2` → `browser_evaluate` confirms Poppins is loaded and an H1/`.entry-title` computes to Poppins.

3. **Self-host path** — `wp eval 'set_theme_mod("site_load_google_font_locally", 1);'` → `browser_navigate` to `http://buddyx.local/?nocache=smoke3` → confirm the request is served from the site domain (`uploads/buddyx-local-fonts/`), not `fonts.gstatic.com`.

4. **Reset** — `wp eval 'remove_theme_mod("h1_typography_option"); remove_theme_mod("site_load_google_font_locally");'`

Expected: new install = Inter, no Google request; compat = Poppins loads; self-host = served locally.

- [ ] **Step 3: Run the repo quality gate on all changed PHP**

Run:
```bash
vendor/bin/phpcs --standard=phpcs.xml.dist inc/Fonts/Google_Fonts_Catalog.php inc/Fonts/Component.php inc/Customizer_Framework/Controls/Typography.php inc/Customizer_Framework/Component.php
```
Expected: no NEW violations introduced by these files' changed lines (pre-existing file-wide violations, if any, are out of scope — same policy as the rest of the 5.1.0 work).

- [ ] **Step 4: Commit**

Run:
```bash
git add readme.txt
git commit -m "docs: changelog for custom Google Fonts compatibility + picker"
```
Expected: commit succeeds.

- [ ] **Step 5: Push**

Run:
```bash
git push origin 5.1.0
```
Expected: pushes to `origin` (wbcomdesigns) only — never `public`/vapvarun.

---

## Self-Review Notes

- **Spec coverage:** Catalog (Task 1), dynamic loader (Task 2), picker (Task 3), search + off-catalog preservation (Task 4), changelog + new-install/self-host verification (Task 5) — every spec component maps to a task.
- **Value-driven loader:** Task 2's `$skip` list excludes only self-hosted/generic families; everything else loads — so off-catalog Kirki picks still render (spec goal 1).
- **Type consistency:** `Google_Fonts_Catalog::get_family_choices()` (Task 1) is the method called in Task 3; `get_catalog()` / `is_google_font()` defined in Task 1 and used consistently. `available_font_families()` returns `{default, groups[]}` in Task 3 and the JS in Task 3 Step 4 + Task 4 consume exactly that shape.
- **No build step:** confirmed — JSON, JS, CSS, PHP all load directly; no `npm run build` needed.
