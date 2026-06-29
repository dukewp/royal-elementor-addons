# Font Icon / Inline SVG Migration Tracker

Track progress for moving REA off global Font Awesome and aligning with Elementor `e_font_icon_svg` + `Icons_Manager`.

## Goal

- Remove global `font-awesome-5-all` enqueue from `plugin.php` (commented out).
- Per-widget: declare `elementor-icons-fa-*` in `get_style_depends()` when `e_font_icon_svg` is **off**.
- Render icons via `Icons_Manager::render_icon()` (not raw `<i class="fas …">`).
- Use correct library slugs: `fa-solid`, `fa-regular`, `fa-brands` (not `solid`, `regular`, `brands`).
- Update CSS/JS that targets `i.fa-*` when markup moves to inline `<svg>` or wrapper spans.

## Test matrix (each completed widget)

| `e_font_icon_svg` | REA FA enqueue removed | Check |
|---|---|---|
| On | Yes | Icons render as inline `<svg>` |
| Off | Yes | Icons render as `<i>` with Elementor FA CSS loaded via widget `get_style_depends()` |

---

## Completed

### Global

- **`plugin.php`** — global `font-awesome-5-all` enqueue commented out (rely on Elementor per-widget loading).

### `classes/utilities.php` — `get_post_sharing_icon()`

- FA icons now use `Icons_Manager::render_icon()` (`fab` → `fa-brands`, `fas` → `fa-solid`).
- Affects: grid/woo grid sharing, sharing buttons widget, instagram/ajax helpers.

### `assets/css/frontend.min.css` — sharing icons (SVG sizing)

- Grid/instagram `.wpr-sharing-icon`: `inline-flex` centering, `svg` at `1em × 1em`, `fill: currentColor`.
- Sharing Buttons widget: `svg` added alongside existing `i` selectors.

### `modules/grid/widgets/wpr-grid.php`

- **`get_style_depends()`** — when inline SVG off: `elementor-icons-fa-solid`, `elementor-icons-fa-brands`.
- Inherited by `Wpr_Grid_Pro`.

### `modules/advanced-accordion/widgets/wpr-advanced-accordion.php`

- **`get_style_depends()`** — when inline SVG off: `fa-solid`, `fa-regular`, `fa-brands`.
- **Title/toggle icon size controls** — `svg` gets `height` as well as `width`.
- **`normalize_icon_library()`** — maps legacy `solid` / `regular` / `brands` → `fa-*` before `render_icon()` (fixes title icons staying as `<i>` when inline SVG on).
- **Control defaults** — repeater `accordion_icon` defaults updated to `fa-solid` / `fa-regular` / `fa-brands`.
- Toggle icons already used `Icons_Manager` + correct `fa-solid` library.

### `modules/advanced-accordion-pro/.../wpr-advanced-accordion-pro.php` (Pro)

- **Search input** — `Icons_Manager::render_icon()` for search + clear icons (wrapped in `.wpr-acc-search-icon` / `.wpr-acc-search-clear`).
- **Style selectors** — updated from `i.fa-times` / `i:first-child` to wrapper + `svg` selectors.

### `assets/js/frontend.min.js` — accordion search

- Clear button: `i.fa-times` → `.wpr-acc-search-clear` for click + show/hide.

### `assets/css/frontend.min.css` — accordion search

- Search/clear wrappers: positioning + `svg` sizing.
- Title/toggle `svg`: `fill: currentColor`.
- **Clear icon fix** — removed `i.fa-times { display: none }` (was hiding inner `<i>` while JS only showed wrapper). Only `.wpr-acc-search-clear` is hidden by default now.
- Reset `position` on icons inside search wrappers (avoids conflict with `.wpr-acc-search-input-wrap i { position: absolute }`).

---

## Reverted / not kept

### `get_wpr_icon()` (`classes/utilities.php`)

- Tried routing FA through `Icons_Manager` (full + conditional `e_font_icon_svg` branches).
- **Reverted to original** — still outputs raw `<i>` for `fa-*` strings. To be migrated later widget-by-widget or via shared helper.

---

## Planned approach (remaining work)

1. **Widgets first** — `get_style_depends()` + `Icons_Manager` per widget; test both inline modes.
2. **Shared helpers** — `get_wpr_icon()`, `get_post_sharing_icon()` (sharing done), hardcoded `<i>` in PHP.
3. **JS** — replace `fa-*` class swapping (mega menu, taxonomy, cart, slider, sorting, etc.).
4. **CSS** — replace `.fa-*` selectors with stable `.wpr-*` classes where needed.
5. **Custom controls** — `wpr-arrow-icons`, mega menu iconpicker, legacy string icon values.

---

## Widgets not yet migrated

- All widgets except **Grid** and **Advanced Accordion** (partial — see above).
- **Woo Grid**, **Media Grid**, **Magazine Grid**, **Sharing Buttons** (widget-level `get_style_depends` if not covered by grid helpers only).
- **Pro search** — done for accordion; pattern reusable elsewhere.

---

## Known patterns / pitfalls

| Issue | Cause | Fix |
|---|---|---|
| Icons missing when inline **off** | No FA CSS on page | Add `elementor-icons-fa-*` to widget `get_style_depends()` |
| Title icons stay `<i>` when inline **on** | Library `solid` instead of `fa-solid` | Fix defaults + `normalize_icon_library()` at render |
| SVG distorted | `width` on `svg` without `height` | Set both in style selectors + base CSS |
| Clear search icon invisible | CSS hides `i.fa-times` but JS shows wrapper only | Hide `.wpr-acc-search-clear` only; reset inner icon `display` |
| `get_wpr_icon()` / pagination arrows | Raw `<i>` tags | Future: `Icons_Manager` or conditional helper |

---

## Reference — Elementor pattern

```php
public function get_style_depends() {
    $depends = [ /* existing handles */ ];

    if ( ! \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_font_icon_svg' ) ) {
        $depends[] = 'elementor-icons-fa-solid';
        $depends[] = 'elementor-icons-fa-regular'; // if widget uses far icons
        $depends[] = 'elementor-icons-fa-brands';  // if widget uses fab icons
    }

    return $depends;
}
```

See Elementor `widget-floating-bars-base.php` for upstream reference.
