const fs = require('fs');
const path = require('path');
const postcss = require('postcss');
const cssnano = require('cssnano');
const { minify } = require('terser');

// Directories containing source files to minify
const CSS_DIRS = [
	'assets/css',
	'assets/css/lib/animations',
];

const JS_DIRS = [
	'assets/js',
];

const ROOT = __dirname;

function getFiles(dir, ext) {
	const fullDir = path.join(ROOT, dir);
	if (!fs.existsSync(fullDir)) return [];

	return fs.readdirSync(fullDir)
		.filter(f => f.endsWith(ext) && !f.endsWith('.min' + ext))
		.map(f => path.join(fullDir, f));
}

async function minifyCSS(files) {
	let count = 0;
	for (const file of files) {
		const source = fs.readFileSync(file, 'utf8');
		const outFile = file.replace(/\.css$/, '.min.css');

		try {
			const result = await postcss([cssnano({ preset: 'default' })]).process(source, {
				from: file,
				to: outFile,
			});
			fs.writeFileSync(outFile, result.css);
			count++;
			console.log(`  CSS: ${path.relative(ROOT, outFile)}`);
		} catch (err) {
			console.error(`  FAIL: ${path.relative(ROOT, file)} - ${err.message}`);
		}
	}
	return count;
}

async function minifyJS(files) {
	let count = 0;
	for (const file of files) {
		const source = fs.readFileSync(file, 'utf8');
		const outFile = file.replace(/\.js$/, '.min.js');

		try {
			const result = await minify(source, {
				compress: true,
				mangle: true,
			});
			if (result.code) {
				fs.writeFileSync(outFile, result.code);
				count++;
				console.log(`  JS:  ${path.relative(ROOT, outFile)}`);
			}
		} catch (err) {
			console.error(`  FAIL: ${path.relative(ROOT, file)} - ${err.message}`);
		}
	}
	return count;
}

async function main() {
	const mode = process.argv[2]; // 'css', 'js', or undefined (both)
	console.log('Royal Elementor Addons - Build\n');

	let cssCount = 0;
	let jsCount = 0;

	if (mode !== 'js') {
		const cssFiles = CSS_DIRS.flatMap(dir => getFiles(dir, '.css'));
		console.log(`Minifying ${cssFiles.length} CSS files...`);
		cssCount = await minifyCSS(cssFiles);
	}

	if (mode !== 'css') {
		const jsFiles = JS_DIRS.flatMap(dir => getFiles(dir, '.js'));
		console.log(`Minifying ${jsFiles.length} JS files...`);
		jsCount = await minifyJS(jsFiles);
	}

	console.log(`\nDone: ${cssCount} CSS + ${jsCount} JS files minified.`);
}

main().catch(err => {
	console.error(err);
	process.exit(1);
});
