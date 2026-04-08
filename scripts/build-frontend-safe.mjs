import fs from "node:fs/promises";
import path from "node:path";
import babel from "@babel/core";
import { minify } from "terser";

const cwd = process.cwd();
const inputPath = path.join(cwd, "assets/js/frontend.min.js");
const backupPath = path.join(cwd, "assets/js/frontend.min.prebuild.backup.js");

async function run() {
  const source = await fs.readFile(inputPath, "utf8");
  await fs.writeFile(backupPath, source, "utf8");

  const babelResult = await babel.transformAsync(source, {
    filename: "frontend.min.js",
    comments: false,
    compact: true,
    presets: [
      [
        "@babel/preset-env",
        {
          targets: {
            browsers: ["> 0.5%", "last 2 versions", "not dead", "ie 11"]
          },
          bugfixes: true,
          modules: false,
          loose: false
        }
      ]
    ]
  });

  if (!babelResult?.code) {
    throw new Error("Babel did not return transformed code.");
  }

  const terserResult = await minify(babelResult.code, {
    ecma: 5,
    compress: {
      ecma: 5,
      passes: 2
    },
    mangle: {
      keep_fnames: true,
      keep_classnames: true
    },
    format: {
      comments: false,
      ascii_only: true
    }
  });

  if (!terserResult?.code) {
    throw new Error("Terser did not return minified code.");
  }

  await fs.writeFile(inputPath, terserResult.code, "utf8");
  process.stdout.write(
    "Built assets/js/frontend.min.js with pinned Babel + Terser (ES5-safe).\n"
  );
  process.stdout.write(
    "Backup created: assets/js/frontend.min.prebuild.backup.js\n"
  );
}

run().catch((error) => {
  process.stderr.write(`${error.stack || error.message}\n`);
  process.exit(1);
});
