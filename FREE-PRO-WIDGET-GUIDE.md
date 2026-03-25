# Royal Elementor Addons - Free/Pro Widget Architecture Guide

Reference for creating new free/pro widget pairs. Based on the Business Hours widget pattern.

---

## Workflow & Source Reference

We use **ElementsKit (ekit)** widgets as the source/reference when adding new widgets to Royal Addons.

### Source Plugins
- **ElementsKit Free (ekit-lite):** `C:\Users\Zuka\Local Sites\royal-addons\app\public\wp-content\plugins\elementskit-lite`
- **ElementsKit Pro (ekit):** `C:\Users\Zuka\Local Sites\royal-addons\app\public\wp-content\plugins\elementskit`

> **Note:** Sometimes the ekit pro plugin contains all the code needed (both free and pro logic), so the ekit-lite (free) plugin may not always be necessary to reference. Check pro first if unsure.

### Build Order
1. **First** — build the **complete widget with ALL features** in the Royal Addons **free** plugin. Include every control, style option, and render logic — no stubs, no pro locks. This gives us a fully working widget to test.
2. **Then** — decide which controls/features to move behind the pro wall. Extract those into stub methods and create the corresponding **pro** override widget in `wpr-addons-pro`.

> **Important:** Always create the full-featured widget first. The free/pro split is a separate step done after the widget is fully built and tested.

### Asset Files (CSS/JS)

The plugin loads **`.min` files** in production (`frontend.min.css` and `frontend.min.js`). The non-min files (`frontend.css`, `frontend.js`) are **not loaded** unless `SCRIPT_DEBUG` is enabled.

**Only edit the `.min` files** (`frontend.min.css` and `frontend.min.js`). Do NOT touch the non-min files — they are synced manually later. The `.min` files are currently unminified copies and are the working files the plugin actually loads.

---

## Overview

The free widget contains ALL logic (controls, rendering, styles). Pro features are **stubbed out** in the free widget using overridable methods. The pro widget **extends** the free widget class and overrides only those stub methods to unlock functionality.

There is only ONE widget visible to the user (same `get_name()` slug). The pro class registers with the same Elementor widget name, so when pro is active it **replaces** the free widget entirely.

---

## File Structure

### Free Plugin (`royal-elementor-addons`)
```
modules/{widget-slug}/
  module.php                          -- Module class, namespace WprAddons\Modules\{WidgetName}
  widgets/wpr-{widget-slug}.php       -- Widget class, namespace WprAddons\Modules\{WidgetName}\Widgets
```

### Pro Plugin (`wpr-addons-pro`)
```
modules/{widget-slug}-pro/
  module.php                          -- Module class, namespace WprAddonsPro\Modules\{WidgetName}Pro
  widgets/wpr-{widget-slug}-pro.php   -- Widget class, namespace WprAddonsPro\Modules\{WidgetName}Pro\Widgets
```

---

## Module Registration

### Free: `modules/{widget-slug}/module.php`
```php
namespace WprAddons\Modules\{WidgetName};
use WprAddons\Base\Module_Base;

class Module extends Module_Base {
    public function get_widgets() {
        return ['Wpr_{Widget_Name}'];
    }
    public function get_name() {
        return 'wpr-{widget-slug}';
    }
}
```

### Pro: `modules/{widget-slug}-pro/module.php`
```php
namespace WprAddonsPro\Modules\{WidgetName}Pro;
use WprAddonsPro\Base\Module_Base;

class Module extends Module_Base {
    public function get_widgets() {
        return ['Wpr_{Widget_Name}_Pro'];
    }
    public function get_name() {
        return 'wpr-{widget-slug}-pro';
    }
}
```

### Pro must also be registered in:
**`wpr-addons-pro/classes/pro-modules.php`** -> `get_registered_modules()` array:
```php
'{Widget Name} Pro' => '{widget-slug}-pro',
```

### Free widget is registered in:
**`royal-elementor-addons/classes/utilities.php`** -> `get_registered_modules()` array:
```php
'{Widget Name}' => ['{widget-slug}', 'https://...', '', ''],
```

---

## The Override Pattern (Core Concept)

The free widget defines **stub methods** for every pro-only feature. These stubs return either:
- `Controls_Manager::HIDDEN` (hides the control entirely)
- Empty method body `{}` (no control added)
- A disabled switcher with `'classes' => 'wpr-pro-control'` (shows grayed-out toggle with pro icon)

The pro widget class **extends** the free widget and **overrides** these same methods with real implementations.

### Three types of stubs:

#### 1. HIDDEN control (for repeater fields that need a default value)
Used when the control must exist in the repeater data but should be invisible in free.

**Free:**
```php
public function add_repeater_args_icon() {
    return [
        'type' => Controls_Manager::HIDDEN,
        'default' => ['value' => '', 'library' => ''],
    ];
}
```
**Pro override:**
```php
public function add_repeater_args_icon() {
    return [
        'label' => esc_html__( 'Select Icon', 'wpr-addons' ),
        'type' => Controls_Manager::ICONS,
        'skin' => 'inline',
        'label_block' => false,
    ];
}
```

#### 2. Disabled switcher with pro badge (for toggles visible but locked in free)
Used when you want the user to SEE the option exists but can't use it without pro.

**Free:**
```php
public function add_repeater_args_highlight() {
    return [
        'label' => sprintf( __( 'Highlight this Item %s', 'wpr-addons' ), '<i class="eicon-pro-icon"></i>' ),
        'type' => Controls_Manager::SWITCHER,
        'separator' => 'before',
        'classes' => 'wpr-pro-control no-distance'  // KEY: this CSS class disables the control
    ];
}
```
**Pro override:**
```php
public function add_repeater_args_highlight() {
    return [
        'label' => esc_html__( 'Highlight', 'wpr-addons' ),
        'type' => Controls_Manager::SWITCHER,
        // No 'classes' => 'wpr-pro-control' -- fully functional
    ];
}
```

#### 3. Empty method body (for style controls that simply don't exist in free)
Used for style tab controls (colors, sizes) that only make sense with the pro feature.

**Free:**
```php
public function add_control_general_even_bg_color() {}
public function add_control_general_icon_color() {}
public function add_control_general_hover_icon_color() {}
public function add_control_general_icon_size() {}
```
**Pro override:**
```php
public function add_control_general_icon_color() {
    $this->add_control('general_icon_color', [
        'label' => esc_html__( 'Icon Color', 'wpr-addons' ),
        'type' => Controls_Manager::COLOR,
        'default' => '#333333',
        'selectors' => [
            '{{WRAPPER}} .wpr-business-day i' => 'color: {{VALUE}}',
        ],
    ]);
}
```

---

## Inline Pro Upgrade Notice

Inside `register_controls()`, add a raw HTML notice inside repeaters or sections when pro is not active:

```php
if ( !defined('WPR_ADDONS_PRO_VERSION') || !wpr_fs()->can_use_premium_code() ) {
    $repeater->add_control('widget_pro_notice', [
        'type' => Controls_Manager::RAW_HTML,
        'raw' => '<span style="color:#2a2a2a;">Feature X and Feature Y</span> options are available in the <strong><a href="https://royal-elementor-addons.com/..." target="_blank">Pro version</a></strong>',
        'content_classes' => 'wpr-pro-notice',
    ]);
}
```

---

## Pro Features List Section

At the end of the content tab sections, add a pro features summary:

```php
Utilities::pro_features_list_section( $this, '', Controls_Manager::RAW_HTML, '{widget-slug}', [
    'Feature description 1',
    'Feature description 2',
    'Feature description 3',
] );
```

---

## How Widget Replacement Works at Runtime

1. **Free plugin loads first**: `modules-manager.php` iterates `Utilities::get_registered_modules()`, instantiates `WprAddons\Modules\{WidgetName}\Module`, which registers `Wpr_{Widget_Name}` widget with Elementor under the name `wpr-{widget-slug}`.

2. **Pro plugin loads after**: `modules-manager.php` iterates `Pro_Modules::get_registered_modules()`, instantiates `WprAddonsPro\Modules\{WidgetName}Pro\Module`, which registers `Wpr_{Widget_Name}_Pro` widget.

3. **Key**: The pro widget class extends the free widget class but does NOT override `get_name()`. Both return `'wpr-{widget-slug}'`. When the pro widget registers with Elementor using the same name, it **replaces** the free widget registration. Elementor uses the last-registered widget for a given name.

4. **Result**: Only one widget appears in the panel. If pro is active, it has all features unlocked. If not, the stub methods keep pro features hidden/disabled.

---

## Render Method

The `render()` method lives entirely in the **free** widget. It should handle both free and pro data gracefully. For example, the icon field will have an empty value in free (because of `HIDDEN` type with empty default), so the render checks:

```php
<?php echo '' !== $item['icon']['value'] ? '<i class="'. esc_attr($item['icon']['value']) .'"></i>' : ''; ?>
```

The pro widget does NOT need to override `render()` in most cases -- the free render already handles the data, it just can't be configured without pro controls.

---

## Step-by-Step: Creating a New Free/Pro Widget

### In the Free Plugin:

1. Create `modules/{widget-slug}/module.php` with the module class
2. Create `modules/{widget-slug}/widgets/wpr-{widget-slug}.php` with the widget class
3. Register in `classes/utilities.php` -> `get_registered_modules()` array
4. In the widget class:
   - Write ALL controls (content + style tabs)
   - Write the complete `render()` method
   - For each pro-only feature, extract the control into a separate method (`add_control_*` or `add_repeater_args_*`)
   - Stub those methods with HIDDEN/empty/disabled-switcher pattern
   - Add `Utilities::pro_features_list_section()` call
   - Add inline pro notice in repeaters if applicable

### In the Pro Plugin:

1. Create `modules/{widget-slug}-pro/module.php` with the pro module class
2. Create `modules/{widget-slug}-pro/widgets/wpr-{widget-slug}-pro.php` with the pro widget class
3. Register in `classes/pro-modules.php` -> `get_registered_modules()` array
4. In the pro widget class:
   - Extend the free widget class: `class Wpr_{Widget_Name}_Pro extends \WprAddons\Modules\{WidgetName}\Widgets\Wpr_{Widget_Name}`
   - Override ONLY the stub methods with real control implementations
   - Do NOT override `get_name()`, `get_title()`, `get_icon()`, `register_controls()`, or `render()` unless absolutely necessary

---

## Naming Conventions

| Item | Free | Pro |
|------|------|-----|
| Module folder | `modules/business-hours/` | `modules/business-hours-pro/` |
| Module namespace | `WprAddons\Modules\BusinessHours` | `WprAddonsPro\Modules\BusinessHoursPro` |
| Widget class | `Wpr_Business_Hours` | `Wpr_Business_Hours_Pro` |
| Widget file | `wpr-business-hours.php` | `wpr-business-hours-pro.php` |
| Elementor name | `wpr-business-hours` | `wpr-business-hours` (SAME!) |
| Pro modules key | -- | `'Business Hours Pro' => 'business-hours-pro'` |
