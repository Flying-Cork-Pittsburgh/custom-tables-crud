let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for your application, as well as bundling up your JS files.
 |
 */

//	mix.setPublicPath('./');

mix.sourceMaps();

mix.options({
	processCssUrls: false,
	publicPath: ('./')
});

mix
.js(
	[
		'src/js/vendors/helpers/domExtensions.js',
		'src/js/vendors/polyfills/element/closest.js',
		'src/js/vendors/polyfills/object/keys.js',
		'src/js/classes_admin/_Core.js',
		// 'src/vue/app.js'
	],
	'assets/js/scripts_admin.js'
)
.js(
	[
		'src/js/classes_frontend/_Core.js',
	],
	'assets/js/scripts_frontend.js'
);
//.extract(['vue', 'gsap'])				// if we want ventors in a separate file for better caching
//.copyDirectory('assets/js/'/* , 'cms/dist/' */);
// version();


mix
.sass(
	'src/scss/styles_admin.scss',
	'assets/css/styles_admin.css'
)
.sass(
	'src/scss/styles_frontend.scss',
	'assets/css/styles_frontend.css'
);

// Full API
// mix.js(src, output);
// mix.react(src, output); <-- Identical to mix.js(), but registers React Babel compilation.
// mix.preact(src, output); <-- Identical to mix.js(), but registers Preact compilation.
// mix.coffee(src, output); <-- Identical to mix.js(), but registers CoffeeScript compilation.
// mix.ts(src, output); <-- TypeScript support. Requires tsconfig.json to exist in the same folder as webpack.mix.js
// mix.extract(vendorLibs);
// mix.sass(src, output);
// mix.standaloneSass('src', output); <-- Faster, but isolated from Webpack.
// mix.fastSass('src', output); <-- Alias for mix.standaloneSass().
// mix.less(src, output);
// mix.stylus(src, output);
// mix.postCss(src, output, [require('postcss-some-plugin')()]);
// mix.browserSync('my-site.test');
// mix.combine(files, destination);
// mix.babel(files, destination); <-- Identical to mix.combine(), but also includes Babel compilation.
// mix.copy(from, to);
// mix.copyDirectory(fromDir, toDir);
// mix.minify(file);
// mix.sourceMaps(); // Enable sourcemaps
// mix.version(); // Enable versioning.
// mix.disableNotifications();
// mix.setPublicPath('path/to/public');
// mix.setResourceRoot('prefix/for/resource/locators');
// mix.autoload({}); <-- Will be passed to Webpack's ProvidePlugin.
// mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly.
// mix.babelConfig({}); <-- Merge extra Babel configuration (plugins, etc.) with Mix's default.
// mix.then(function () {}) <-- Will be triggered each time Webpack finishes building.
// mix.extend(name, handler) <-- Extend Mix's API with your own components.
// mix.options({
//   extractVueStyles: false, // Extract .vue component styling to file, rather than inline.
//   globalVueStyles: file, // Variables file to be imported in every component.
//   processCssUrls: true, // Process/optimize relative stylesheet url()'s. Set to false, if you don't want them touched.
//   purifyCss: false, // Remove unused CSS selectors.
//   uglify: {}, // Uglify-specific options. https://webpack.github.io/docs/list-of-plugins.html#uglifyjsplugin
//   postCss: [] // Post-CSS options: https://github.com/postcss/postcss/blob/master/docs/plugins.md
// });

// mix.options({ processCssUrls: false });
// mix.webpackConfig({
// 	node: {
// 		fs: "empty",
// 		request: "empty"
// 	},
// 	resolve: {
// 		alias: {
// 				"handlebars" : "handlebars/dist/handlebars.js"
// 		}
// 	},
// });

// https://webpack.js.org/guides/shimming/
