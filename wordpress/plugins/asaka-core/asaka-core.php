<?php
/**
 * Plugin Name: Asaka Core
 * Description: Données du site public Asaka : type de contenu « formation » et liens vers le campus Moodle. La synchronisation du catalogue Moodle viendra ici.
 * Version: 0.1.0
 * Requires at least: 6.6
 * Requires PHP: 8.2
 * Author: Asaka Academy
 * License: GPL-2.0-or-later
 * Text Domain: asaka-core
 *
 * @package asaka-core
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL absolue d'une page du campus Moodle.
 * ASAKA_CAMPUS_URL est défini dans wp-config.php (WORDPRESS_CONFIG_EXTRA en Docker).
 */
if ( ! function_exists( 'asaka_campus_url' ) ) {
	function asaka_campus_url( string $path = '/' ): string {
		$base = defined( 'ASAKA_CAMPUS_URL' ) ? ASAKA_CAMPUS_URL : home_url( '/campus' );
		return untrailingslashit( $base ) . '/' . ltrim( $path, '/' );
	}
}

add_action(
	'init',
	function () {
		register_post_type(
			'formation',
			array(
				'labels'       => array(
					'name'          => __( 'Formations', 'asaka-core' ),
					'singular_name' => __( 'Formation', 'asaka-core' ),
					'add_new_item'  => __( 'Ajouter une formation', 'asaka-core' ),
					'edit_item'     => __( 'Modifier la formation', 'asaka-core' ),
					'all_items'     => __( 'Toutes les formations', 'asaka-core' ),
				),
				'public'       => true,
				'has_archive'  => 'formations',
				'rewrite'      => array( 'slug' => 'formations', 'with_front' => false ),
				'menu_icon'    => 'dashicons-welcome-learn-more',
				'show_in_rest' => true,
				'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions' ),
				// Nouvelle formation = gabarit de la fiche, prêt à remplir.
				'template'     => array( array( 'core/pattern', array( 'slug' => 'asaka/formation-detail' ) ) ),
			)
		);

		// Identifiant du cours Moodle correspondant (renseigné par la future synchro catalogue).
		register_post_meta(
			'formation',
			'asaka_moodle_course_id',
			array(
				'type'          => 'integer',
				'single'        => true,
				'show_in_rest'  => true,
				'auth_callback' => fn() => current_user_can( 'edit_posts' ),
			)
		);
	}
);

register_activation_hook(
	__FILE__,
	function () {
		do_action( 'init' );
		flush_rewrite_rules();
	}
);
