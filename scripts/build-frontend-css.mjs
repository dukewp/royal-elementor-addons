import fs from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";

const cwd = process.cwd();
const inputPath = path.join(cwd, "assets/css/frontend.min.css");
const backupPath = path.join(cwd, "assets/css/frontend.min.prebuild.backup.css");

// Same rules as rea-prod-frontend-minify/scripts/build-frontend-css.mjs.
// Matched byte for byte against rea-prod assets/css/frontend.min.css
// (the file previously minified at https://www.cleancss.com/).
export function minifyCss(css) {
  const tokenRe = /\/\*[\s\S]*?\*\/|"(?:\\.|[^"\\])*"|'(?:\\.|[^'\\])*'/g;
  let last = 0;
  let out = "";
  let match;

  while ((match = tokenRe.exec(css))) {
    if (match.index > last) {
      out += css.slice(last, match.index).replace(/\s+/g, " ");
    }
    if (!match[0].startsWith("/*")) {
      out += match[0];
    }
    last = match.index + match[0].length;
  }

  if (last < css.length) {
    out += css.slice(last).replace(/\s+/g, " ");
  }

  return out
    .replace(/\{\s+/g, "{")
    .replace(/\}\s+/g, "}")
    .replace(/;\s+/g, ";");
}

async function run() {
  const source = await fs.readFile(inputPath, "utf8");
  const lineCount = source.split("\n").length;
  const force = process.argv.includes("--force");

  if (!force && lineCount < 30) {
    throw new Error(
      "frontend.min.css already looks minified (" +
        lineCount +
        " lines). Refusing to minify it again. Restore the readable stylesheet first, or pass --force."
    );
  }

  await fs.writeFile(backupPath, source, "utf8");

  const minified = minifyCss(source);
  await fs.writeFile(inputPath, minified, "utf8");

  process.stdout.write(
    "Built assets/css/frontend.min.css (" +
      source.length +
      " -> " +
      minified.length +
      " bytes) with the cleancss.com whitespace rules.\n"
  );
  process.stdout.write("Backup created: assets/css/frontend.min.prebuild.backup.css\n");
}

const thisFile = path.resolve(fileURLToPath(import.meta.url));
const entry = process.argv[1] ? path.resolve(process.argv[1]) : "";
if (entry === thisFile) {
  run().catch((error) => {
    process.stderr.write(`${error.stack || error.message}\n`);
    process.exit(1);
  });
}
