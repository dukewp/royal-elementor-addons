# Frontend Minification (Deterministic)

Use this local build to avoid JSCompress config drift between releases.

## First time

```bash
npm install
```

## Build frontend bundle

```bash
npm run build:frontend-safe
```

This process:

- Reads `assets/js/frontend.min.js`
- Transpiles with pinned Babel preset targets
- Minifies with pinned Terser options (ES5-safe)
- Rewrites `assets/js/frontend.min.js`
- Creates backup at `assets/js/frontend.min.prebuild.backup.js`
