# Royal Elementor Addons — AI & MCP

This plugin registers **WordPress Abilities** (WP 7.0+) and an **MCP** server so external clients (Claude Desktop, MCP Inspector, ChatGPT connectors, etc.) can list kits, import content, and related operations over the official MCP Adapter.

## Connect Claude Desktop in about one minute

1. In WordPress, create an **Application Password** for an **Administrator** account (**Users → Profile → Application Passwords**).
2. In wp-admin open **Royal Addons → AI & MCP**.
3. Copy the **MCP endpoint URL** (Streamable HTTP) shown on that page.
4. In **Claude Desktop** (`claude_desktop_config.json`), add an `mcpServers` entry using **`npx.cmd`** (Windows) or `npx` (macOS/Linux), **`mcp-remote`**, your site URL, and **`--allow-http`** if the site is not HTTPS on localhost.
5. Set the `Authorization` header to **`Basic `** + Base64 of `username:application_password` (no spaces inside the secret before encoding).
6. Under **Royal Addons → AI & MCP**, turn on only the tools you need. **Heavy write tools** (import, full install, finalize, toggle extension, **patch Royal widget text**) are **off by default** until you enable them.

Optional deep testing: define `WPR_AI_DEBUG` as `true` in `wp-config.php` **before** the plugin loads to enable verbose MCP tracing and the **AI Abilities** debug submenu.

## What the AI can do (tools)

| Tool | Default MCP | Purpose |
|------|---------------|---------|
| `list-template-kits` | On | Browse available template kits. |
| `search-template-kits` | On | Free-text search kits. |
| `list-widgets` / `list-extensions` | On | Inspect enabled modules. |
| `list-rea-widgets-on-page` | On | Scan an Elementor page for **Royal** widgets (`wpr-*`); get `element_id` + `patchable_keys` (flat + repeater paths). |
| `get-install-job-status` | On | Poll a background `install-kit` job. |
| `import-template-kit` | **Off** | Import kit XML (large writes). |
| `finalize-kit-setup` | **Off** | Run post-import finalization. |
| `install-kit` | **Off** | Full kit pipeline (theme/plugins/import). **Defaults to async** — returns a `job_id`; poll `get-install-job-status`. |
| `patch-rea-widget-text-settings` | **Off** | Change **allowlisted** text: **flat** keys (e.g. `content_title`) or **repeater** paths (`advanced_accordion.0.accordion_title`, `testimonial_items.1.testimonial_content`, `grid_elements.2.element_extra_text`). **Not** colors/spacing/CSS. |
| `toggle-extension` | **Off** | Enable/disable an extension. |

### Example prompts

- “List free Royal Elementor template kits and pick one suitable for a logistics company site.”
- “Search REA kits for ‘coffee shop’ and summarize the top three.”
- “After I enabled install-kit in AI & MCP, queue kit `logistic` with async, then poll job status until complete.”
- “On page ID 42, list Royal widgets, then change the `title` of element `a1b2c3d` to `Contact us` (enable patch tool first).”

## Security notes

- **Kit / extension writes** still expect **`manage_options`** where noted in the Abilities API. **Elementor page tools** use normal **post edit** caps (`edit_post` on the given ID) plus the **AI & MCP** toggle for the patch tool.
- Destructive / write-heavy calls are **rate-limited** per user (filter: `wpr_ai_destructive_rate_limit_per_minute`, default 8/min).
- Optional **audit** lines go to the PHP error log when enabled on the settings page.
- Kit XML is only downloaded from the fixed **`royal-elementor-addons.com`** host.
- **Patch** only accepts **pre-approved setting keys** (filter `wpr_ai_rea_widget_text_setting_keys`) and **repeater field maps** (filter `wpr_ai_repeater_text_fields`); values go through **`sanitize_text_field`** / **`wp_kses_post`** and Elementor’s own save path (including `kses` when the user lacks `unfiltered_html`).
