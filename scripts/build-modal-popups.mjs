import fs from "node:fs/promises";
import path from "node:path";
import babel from "@babel/core";
import { minify } from "terser";

const cwd = process.cwd();
const inputPath = path.join(cwd, "assets/js/modal-popups.min.js");

async function run() {
  const source = await fs.readFile(inputPath, "utf8");
  const lineCount = source.split("\n").length;

  if (lineCount < 30) {
    throw new Error(
      "modal-popups.min.js already looks minified (" +
        lineCount +
        " lines). Refusing to minify it again. Put the readable script in place first."
    );
  }

  const babelResult = await babel.transformAsync(source, {
    filename: "modal-popups.min.js",
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
    "Built assets/js/modal-popups.min.js with pinned Babel + Terser (ES5-safe).\n"
  );
  process.stdout.write("No backup file was written.\n");
}

run().catch((error) => {
  process.stderr.write(`${error.stack || error.message}\n`);
  process.exit(1);
});
