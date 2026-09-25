<?php
/**
 * Thème Asaka — chargement des styles, blocs et styles de blocs.
 *
 * @package asaka
 */

defined( 'ABSPATH' ) || exit;

define( 'ASAKA_THEME_VERSION', '0.1.0' );

/**
 * URL du campus Moodle. La constante ASAKA_CAMPUS_URL est définie dans wp-config.php
 * (WORDPRESS_CONFIG_EXTRA en Docker) ; le plugin asaka-core fournit la même fonction.
 */
if ( ! function_exists( 'asaka_campus_url' ) ) {
	function asaka_campus_url( string $path = '/' ): string {
		$base = defined( 'ASAKA_CAMPUS_URL' ) ? ASAKA_CAMPUS_URL : home_url( '/campus' );
		return untrailingslashit( $base ) . '/' . ltrim( $path, '/' );
	}
}

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'asaka', get_template_directory() . '/languages' );
		add_theme_support( 'editor-styles' );
		add_editor_style( array( 'assets/css/tokens.css', 'assets/css/components.css', 'assets/css/theme.css' ) );
		remove_theme_support( 'core-block-patterns' );
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		$uri = get_template_directory_uri() . '/assets';
		wp_enqueue_style( 'asaka-tokens', "$uri/css/tokens.css", array(), ASAKA_THEME_VERSION );
		wp_enqueue_style( 'asaka-components', "$uri/css/components.css", array( 'asaka-tokens' ), ASAKA_THEME_VERSION );
		wp_enqueue_style( 'asaka-theme', "$uri/css/theme.css", array( 'asaka-components' ), ASAKA_THEME_VERSION );
		wp_enqueue_script( 'asaka-ui', "$uri/js/ui.js", array(), ASAKA_THEME_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	}
);

add_action(
	'init',
	function () {
		// Aperçu serveur des blocs dynamiques dans l'éditeur, sans étape de build.
		wp_register_script(
			'asaka-blocks-editor',
			get_template_directory_uri() . '/assets/js/blocks-editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-server-side-render' ),
			ASAKA_THEME_VERSION,
			true
		);

		register_block_type( __DIR__ . '/blocks/site-header' );
		register_block_type( __DIR__ . '/blocks/site-footer' );

		register_block_style( 'core/button', array( 'name' => 'ink', 'label' => __( 'Sombre', 'asaka' ) ) );

		register_block_pattern_category( 'asaka', array( 'label' => __( 'Asaka', 'asaka' ) ) );
	}
);

/**
 * Icônes SVG inline (trait 1.8, couleur héritée). Remplaçables par les SVG Phosphor originaux.
 */
function asaka_icon( string $name, string $class = '' ): string {
	$paths = array(
		'check' => '<circle cx="12" cy="12" r="9"/><path d="M8 12.5l2.7 2.7L16 9.8"/>',
		'menu'  => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close' => '<path d="M6 6l12 12M18 6L6 18"/>',
		'caret' => '<path d="M9 6l6 6-6 6"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return sprintf(
		'<svg class="asa-icon %s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%s</svg>',
		esc_attr( $class ),
		$paths[ $name ]
	);
}
