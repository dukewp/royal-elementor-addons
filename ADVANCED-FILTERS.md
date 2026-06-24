# REA Advanced Filters — Technical Reference

Authoritative reference for **Advanced Filters Pro** and its integration with Grid / Woo Grid. Read this before changing filter behavior in PHP, JS, or grid helpers.

**Location:** `royal-elementor-addons/ADVANCED-FILTERS.md` (dev-only; excluded from release zip via `zip-free.py`).

---

## 1. Two filter systems (do not confuse them)

| System | UI location | URL params | AJAX | Purpose |
|--------|-------------|------------|------|---------|
| **Built-in Grid Filters** | Grid → Layout → Filters | `wpr_taxonomy`, `wpr_filter` | `filtersExperiment()` in `frontend.min.js` | Taxonomy tab bar on the grid itself |
| **Advanced Filters Pro** | Separate `wpr-advanced-filters-pro` widget(s) | `wpr_af_*`, `wpr_afr_*`, `wpr_aft_*` | `widgetAdvancedFilters()` | External filter widgets controlling one grid |

They can coexist on a page but use **different code paths**.

---

## 2. File map

### Dev reference (free plugin root)

| File | Role |
|------|------|
| `ADVANCED-FILTERS.md` | This document |
| `.cursor/rules/rea-advanced-filters.mdc` | Cursor rule summary + pointer to this doc |

### Pro — widget module (`royal-elementor-addons-pro/modules/advanced-filters-pro/`)

| File | Role |
|------|------|
| `module.php` | Registers slug `wpr-advanced-filters-pro` → widget `Wpr_Advanced_Filters_Pro` |
| `widgets/wpr-advanced-filters-pro.php` | **~5,650 lines** — all controls, SSR render, dependency logic, markup, wrapper data attributes |

### Pro — related but separate

| File | Role |
|------|------|
| `modules/theme-builder/woocommerce/product-filters-pro/` | Legacy Woo shop filters (search/price/rating). **Not** part of `wpr_af_*` pipeline |

### Free — grid integration

| File | Role |
|------|------|
| `modules/grid/widgets/wpr-grid.php` | `advanced_filters` toggle, `data-advanced-filters`, `grid_settings` JSON |
| `modules/woo-grid/widgets/wpr-woo-grid.php` | Same for products |
| `classes/modules/wpr-grid-helpers.php` | Post AJAX: `wpr_grid_filters_ajax`, `wpr_get_filtered_count_posts`, `get_main_query_args()` |
| `classes/modules/wpr-woo-grid-helpers.php` | Product AJAX: `wpr_woo_grid_filters_ajax`, `wpr_get_filtered_count_products`, price/rating handling |

### Free — frontend

| File | Role |
|------|------|
| `assets/js/frontend.min.js` | `widgetAdvancedFilters`, `ajaxFilters`, `updateURL`, `updateResultsCount`, `debounceAjaxCall` (**edit only `frontend.min.js`**) |
| `assets/css/frontend.min.css` | `.wpr-advanced-filters-*`, `.wpr-af-*` (~line 17622+) |

### Free — registration / promotion

| File | Role |
|------|------|
| `classes/pro-modules.php` (pro) | Expert plan gate for `advanced-filters-pro` |
| `admin/plugin-options.php` | Widget toggle `wpr-element-advanced-filters-pro` |
| `plugin.php` | Promotion widget when Pro inactive |

---

## 3. Architecture model

- **One Elementor widget instance = one filter control block** (checkbox group, select, range, apply button, active chips, etc.).
- Users compose a filter UI from **multiple** Advanced Filters widgets on the page.
- **One grid** per page is targeted (JS uses `.first()` on `[data-advanced-filters="yes"]`).
- All `.wpr-advanced-filters-wrap` elements share one global URL state (`finalURL`).

### Widget PHP private state

| Property | Purpose |
|----------|---------|
| `$output` | Accumulated HTML string |
| `$dependent_count` | Cascading select level (main = 1, children > 1) |
| `$filter_item` | Current row from `dependent_select_repeater` |

### Main PHP methods (`wpr-advanced-filters-pro.php`)

| Method | Purpose |
|--------|---------|
| `register_controls()` | Elementor panel (General, Content, Visual repeater, Advanced, style tabs) |
| `render()` → `render_grid_filters()` | Entry; builds wrapper + dispatches by `filter_type` |
| `render_general_filters()` | Select / checkbox / radio shell |
| `render_main_filters()` | Term/meta queries + `enable_dependency` narrowing |
| `print_filters()` | Recursive term/meta option output (children, counts, empty SSR) |
| `render_range_filter()` | Price or numeric meta slider |
| `render_rating_filter()` | Woo star ratings |
| `render_active_filters()` | Chip UI + reset |
| `render_apply_all_filter()` | Apply button (batch mode) |
| `render_date_filter()` | Date UI (partially disabled in controls) |
| `render_search_filter()` | Search input (render exists; not in `filter_type` dropdown) |

---

## 4. Filter types and data sources

### `filter_type` (UI control)

| Value | Notes |
|-------|-------|
| `select` | `wpr-af-main-select` / `wpr-af-dependent-select` for cascading |
| `checkbox` | Supports `tax_query_type` AND/OR, visual filters |
| `radio` | Single value per group |
| `range` | Slider + min/max; price or meta |
| `rating` | Woo only; forces `tax_query_type = or` |
| `apply` | Defers grid AJAX until Apply click |
| `active` | Active chips; populated by JS |
| `date` / `date_range` | Commented out of dropdown; render code remains |
| `search` | Render exists; not exposed in dropdown; **no query handler** in grid helpers |

### `filter_data`

| Value | Options source | `name` attribute |
|-------|----------------|------------------|
| `taxonomy` | `get_terms()` on `query_taxonomy_{filters_query}` | `wpr_af_{taxonomy}` |
| `meta_field` | Distinct `postmeta` values for `cf_for_all_post_types` | `wpr_af_{meta_key}` |
| `price` | Woo `_price` min/max | `wpr_af__price` |

### `filters_query`

Post type context (post, product, CPT). Non-core CPTs gated behind Expert plan in UI.

---

## 5. URL parameter convention

Global JS updates `finalURL` via `updateURL()` → `WprElements.changeFinalURL()`.

### Value params — `wpr_af_{key}`

| Pattern | Example |
|---------|---------|
| Single term | `?wpr_af_product_cat=12` |
| Multi (checkbox OR) | `?wpr_af_product_cat=12,34` |
| Range | `?wpr_af__price=10,200` |
| Date range | `?wpr_af_date_range=2024-01-01,2024-12-31` |
| Cleared select | param removed when value is `0` |

### Relation meta — `wpr_afr_{key}`

Set on every filter change:

```
wpr_afr_{key} = {relation},{filter_type}
```

Example: `wpr_afr_product_cat=or,checkbox`

PHP reads this in `get_main_query_args()` to set inner `tax_query` / `meta_query` relation.

### Range type meta — `wpr_aft_{key}`

```
wpr_aft_{key} = range
```

Tells PHP to build `BETWEEN` meta_query.

### PHP consumption (`WPR_Grid_Helpers::get_main_query_args`)

1. Uses `$params` if passed, else `$_POST['wpr_url_params']`, else `$_GET`
2. Iterates keys containing `wpr_af_`
3. Strips prefix → routes via `identify_key_type()` → `taxonomy` | `meta_field` | `custom_field`
4. Merges `tax_query`, `meta_query`, `date_query` at top level with AND

Woo helper adds `rating`, `price` / `_price` special cases.

---

## 6. Wrapper data attributes

Rendered on `.wpr-advanced-filters-wrap` in `render_grid_filters()`:

| Attribute | Source | Used for |
|-----------|--------|----------|
| `data-wpr-relation` | `tax_query_type` (`and` / `or`) | `updateURL` → `wpr_afr_*`; OR skip in `updateResultsCount` |
| `data-wpr-filter-type` | `filter_type` | `updateURL` → `wpr_afr_*` |
| `data-enable-ajax` | `enable_ajax` | Full reload vs `debounceAjaxCall` |
| `data-show-count` | `show_count` | Counter display + `updateResultsCount` gate |
| `data-change-counter` | `change_counter` if `enable_dependency` is yes | Self-filter skip (`other_filters`) |
| `data-empty-action` | `empty_actions` if `handle_empty` is yes | Hide/disable in JS; empty string = off |
| `data-none-label` | select placeholder | Dependent select reset label |
| `data-term-label` | taxonomy/meta label | Active filter chips |

**No `data-handle-empty`** — empty handling is gated by whether `data-empty-action` is `hide` or `disable`.

Range container also gets `data-active`, `data-format-numbers`, `data-decimal-places`.

---

## 7. Advanced controls (interconnected)

| Control | Panel condition | PHP (SSR) | JS (AJAX) |
|---------|-----------------|-----------|-----------|
| **enable_dependency** | checkbox/radio/select | Narrows terms via other `wpr_af_*` GET params (excludes own key) | Required for `data-change-counter` output |
| **show_count** | checkbox/radio/select | Appends `(N)` in `.wpr-af-count` | `updateResultsCount` rewrites counts |
| **display_count_aside** | `show_count` yes | CSS margin on `.wpr-af-count` | — |
| **change_counter** | ajax + dependency + show_count | — | `always` = update all wraps; `other_filters` = skip triggering widget |
| **hide_empty** | **non-AJAX only** (`enable_ajax!`) | `get_terms(hide_empty)` + skip in loop | — (legacy; TODO merge with handle_empty) |
| **handle_empty** | ajax + checkbox/radio/select | hide: skip render; disable: `disabled` attr | Triggers empty logic in `updateResultsCount` |
| **empty_actions** | `handle_empty` yes | `hide` / `disable` | `.hide()` / `.prop('disabled')` when count = 0 |

### Counter vs empty — design rules

1. **Counters** and **empty actions** are separate concerns but share the count AJAX endpoint.
2. `updateResultsCount` runs when **either** `data-show-count=yes` (with `data-change-counter` set) **or** `data-empty-action` is hide/disable.
3. **Self-filter skip** (`change_counter === other_filters` + wrap is `$scope`): skip **entire wrap** — no count or empty updates on the widget that triggered the change. Prevents hidden items reappearing when clicking the same filter.
4. **OR relation skip**: if `data-wpr-relation=or` and wrap is `$scope`, skip all per-option updates in success callback.
5. **Empty-only count query**: when `handle_empty` on but `show_count` off, hypothetical params use **replace** (`paramsObjTemp[selectedName] = selectedValue`) not merge with siblings.
6. **Both count + empty on**: uses **merge** params (original counter behavior) for the shared AJAX batch.

---

## 8. Dependency systems (two different features)

### A. `enable_dependency` (facet narrowing)

When yes, `render_main_filters()`:

1. Collects all `$_GET['wpr_af_*']` **except** current filter's own key
2. Builds constrained post query from other active filters
3. Only outputs terms/meta present in matching posts
4. Respects `hide_empty` on non-AJAX path

### B. `enable_dependent_select` (cascading selects)

Separate feature. Uses AJAX `wpr_get_dependent_terms` with `tax_array` + `parent_terms`. Classes: `wpr-af-main-select`, `wpr-af-dependent-select`.

---

## 9. Frontend JS flow (`widgetAdvancedFilters`)

Registered as: `'wpr-advanced-filters-pro.default': WprElements.widgetAdvancedFilters`

### Init

1. Finds **first** `.wpr-grid[data-advanced-filters="yes"]` and parent widget scope
2. Picks Woo vs post AJAX actions
3. Wires range sliders, AirDatepicker, visual filters, dependent select, active filters, apply/reset
4. Infinite scroll branch when grid uses `pagination_type === infinite-scroll'`

### `ajaxFilters(self)` pipeline

1. Loop **all** `.wpr-advanced-filters-wrap` → `updateURL()` into shared `finalURL`
2. `renderActiveFilters('ajax')` — rebuild chips globally
3. If wrap has `data-enable-ajax !== 'yes'` → full page reload
4. Else → `debounceAjaxCall()` (300ms)

### `debounceAjaxCall()`

POST `wpr_grid_filters_ajax` / `wpr_woo_grid_filters_ajax` with `grid_settings` + `wpr_url_params`. Success → `updateGrid()` → isotope replace → `updateResultsCount()`.

### `updateResultsCount(settings)`

POST `wpr_get_filtered_count_posts` / `wpr_get_filtered_count_products` with **array** of param objects (one per option tested). Updates `.wpr-af-count` and hide/disable per `data-empty-action`.

### Apply button mode

When `.wpr-af-apply-btn` exists on page, individual changes only update `finalURL`; grid AJAX waits for Apply / Reset / range apply.

### Grid hijacking

When any advanced filters wrap exists, grid's built-in `loadMoreExperiment()` is skipped; load more routes through advanced filters AJAX.

---

## 10. AJAX endpoints

Nonce: `wpr-addons-js` (`WprConfig.nonce`).

| Action | Handler | POST | Response |
|--------|---------|------|----------|
| `wpr_grid_filters_ajax` | `WPR_Grid_Helpers` | `grid_settings`, `wpr_url_params`, optional `wpr_offset` | `{ output, duration, found_posts, post_count }` |
| `wpr_woo_grid_filters_ajax` | `WPR_Woo_Grid_Helpers` | Same | Same |
| `wpr_get_filtered_count_posts` | `WPR_Grid_Helpers` | `grid_settings`, `wpr_url_params[]` | `[{ found_posts, post_count }, ...]` |
| `wpr_get_filtered_count_products` | `WPR_Woo_Grid_Helpers` | Same | Same + `params` echo |
| `wpr_get_dependent_terms` | `WPR_Grid_Helpers` | `taxonomy`, `parent_term`, `tax_array`, `parent_terms` | `[{ id, name }, ...]` |

### `grid_settings` payload

Built in grid `add_grid_settings()` when `filters_experiment` **or** `advanced_filters` is yes. Serialized to grid `data-settings` JSON. Includes query config, elements repeater, `check_ajax_filter: 'yes'`, pagination type, etc.

---

## 11. Markup and CSS classes

```
.elementor-widget-wpr-advanced-filters-pro
  └── .wpr-advanced-filters-wrap[data-*]
        ├── .wpr-af-filters-label
        ├── .wpr-advanced-filters-inner
        ├── .wpr-af-select-group | .wpr-af-check-radio-group
        ├── .wpr-af-range-container
        ├── .wpr-af-rating-filter
        ├── .wpr-af-apply-btn-wrap > .wpr-af-apply-btn
        └── .wpr-af-active-filters > .wpr-af-reset-btn
```

**Checkbox/radio item:**

```
.wpr-af-input-wrap[.wpr-checked]
  input[name="wpr_af_{taxonomy}"]
  .wpr-af-term-name + .wpr-af-count
```

**Visual filter:** `.wpr-af-visual-wrap[.wpr-af-visual-active]`, `data-replace-value`

**Hierarchy depth:** `.wpr-af-child`, `.wpr-af-g-child`, `.wpr-af-g-grand-child`

**Grid:** `section.wpr-grid[data-advanced-filters="yes"][data-settings]`

**Loader:** `.wpr-filters-loader-wrap`, `.wpr-grid-loading`

**Utility:** `.wpr-hidden-element`, `.wpr-active-product-filter` (rating)

---

## 12. Grid-side integration

### Control (`wpr-grid.php` / `wpr-woo-grid.php`)

- `advanced_filters` switcher in Query section
- Description: use with Advanced Filters widget; works with load-more + infinite-scroll

### Render

```php
'data-advanced-filters' => ('yes' === $settings['advanced_filters']) ? 'yes' : 'no'
```

When advanced filters enabled and query empty, grid still renders empty shell so JS can attach.

### Built-in filters section (separate)

`layout_filters`, `filters_experiment`, `filters_hide_empty` — uses `wpr_taxonomy` / `wpr_filter`, not `wpr_af_*`.

---

## 13. Gotchas and known issues

### Architecture

- **One grid per page** — JS `.first()` only; multiple `data-advanced-filters=yes` grids unsupported.
- **Many filter widgets → one grid** — all wraps write to same `finalURL`.
- **Expert plan** required for Pro module and non-core CPT filters.

### Self-filter behavior (critical)

| Layer | Behavior |
|-------|----------|
| PHP SSR | Current taxonomy key excluded from dependency `wpr_af_variables` |
| JS `other_filters` | Entire triggering widget skipped in `updateResultsCount` |
| JS OR + scope | Per-option updates skipped for triggering OR widget |

### Legacy / incomplete

- `hide_empty` vs `handle_empty` — two systems (TODO comments in widget PHP)
- `search` filter type — no `get_main_query_args` handler
- `date` types commented out of dropdown
- Active chip removal may reference legacy `_gf_` params in one code path
- `identify_key_type()` unknown keys fall through to `meta_field`
- Grid cache in `debounceAjaxCall` disabled (`'goga' === 'droga'`)

### Product Filters Pro

Theme-builder Woo widget — **not** connected to Advanced Filters pipeline.

---

## 14. Task checklist for implementers

When changing Advanced Filters behavior:

1. Read this doc and inspect `wpr-advanced-filters-pro.php` + `widgetAdvancedFilters` in `frontend.min.js`.
2. Determine if change affects **SSR** (PHP render), **AJAX grid** (helpers), or **count/empty** (`updateResultsCount`).
3. Respect **self-filter skip** — do not re-show hidden items on the triggering widget when `other_filters` is set.
4. Gate `data-empty-action` on `handle_empty`; do not add redundant `data-handle-empty`.
5. Edit **only** `frontend.min.js` / `frontend.min.css` for runtime assets.
6. Test: other filter changes (should update counts + empty on non-triggering widgets), self click (should not reveal hidden), OR relation, show_count off + handle_empty on, apply-button mode.

---

## 15. Quick setup (for QA)

1. Expert Pro active; enable Advanced Filters Pro in Royal Addons settings.
2. Grid/Woo Grid: **Enable Advanced Filters**; pagination = Load More or Infinite Scroll.
3. Place Advanced Filters widget(s); match `filters_query` to grid post type.
4. Per filter widget: **Enable AJAX** = yes.
5. Only **one** grid with `data-advanced-filters="yes"`.
6. Optional: Active Filters + Apply widgets for batched UX.
7. For live counts/empty: Enable Dependency + Show Count + Handle Empty.

---

*Last updated: reflects counter/empty decoupling and self-filter skip fixes in `updateResultsCount`.*
