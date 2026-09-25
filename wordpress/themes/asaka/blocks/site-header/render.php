<?php
/**
 * Rendu du bloc asaka/site-header (composant Header du design system).
 *
 * @package asaka
 */

defined( 'ABSPATH' ) || exit;

$asaka_catalogue = get_post_type_archive_link( 'formation' ) ?: home_url( '/formations/' );
$asaka_links     = array(
	array( __( 'Accueil', 'asaka' ), home_url( '/' ), is_front_page() ),
	array( __( 'Catalogue', 'asaka' ), $asaka_catalogue, is_post_type_archive( 'formation' ) || is_singular( 'formation' ) ),
	array( __( 'Organisations', 'asaka' ), home_url( '/organisations/' ), is_page( 'organisations' ) ),
);
$asaka_drawer_id = 'asa-drawer-' . wp_unique_id();
$asaka_logo      = get_template_directory_uri() . '/assets/images/asaka-logo.svg';
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'asa-site-header' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<header class="asa-header">
		<a class="asa-header__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<img src="<?php echo esc_url( $asaka_logo ); ?>" alt="<?php esc_attr_e( 'ASAKA Academy, accueil', 'asaka' ); ?>" width="88" height="44">
		</a>
		<nav class="asa-header__nav" aria-label="<?php esc_attr_e( 'Principale', 'asaka' ); ?>">
			<?php foreach ( $asaka_links as list( $label, $url, $active ) ) : ?>
				<a class="asa-header__link<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo esc_url( $url ); ?>"<?php echo $active ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $label ); ?></a>
			<?php endforeach; ?>
		</nav>
		<div class="asa-header__actions">
			<a class="asa-btn asa-btn--ghost asa-btn--sm" href="<?php echo esc_url( asaka_campus_url( '/login/index.php' ) ); ?>"><?php esc_html_e( 'Connexion', 'asaka' ); ?></a>
			<a class="asa-btn asa-btn--ink asa-btn--sm" href="<?php echo esc_url( asaka_campus_url( '/login/signup.php' ) ); ?>"><?php esc_html_e( 'Créer un compte', 'asaka' ); ?></a>
			<button class="asa-btn asa-btn--ghost asa-header__menu" type="button" data-asa-menu aria-controls="<?php echo esc_attr( $asaka_drawer_id ); ?>" aria-expanded="false">
				<span data-asa-menu-open><?php echo asaka_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span data-asa-menu-close hidden><?php echo asaka_icon( 'close' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'asaka' ); ?></span>
			</button>
		</div>
	</header>
	<nav class="asa-drawer" id="<?php echo esc_attr( $asaka_drawer_id ); ?>" aria-label="<?php esc_attr_e( 'Principale', 'asaka' ); ?>" hidden>
		<?php foreach ( $asaka_links as list( $label, $url, $active ) ) : ?>
			<a class="asa-drawer__link<?php echo $active ? ' is-active' : ''; ?>" href="<?php echo esc_url( $url ); ?>"<?php echo $active ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $label ); ?><?php echo asaka_icon( 'caret' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
		<?php endforeach; ?>
		<div class="asa-drawer__actions">
			<a class="asa-btn asa-btn--outline asa-btn--block" href="<?php echo esc_url( asaka_campus_url( '/login/index.php' ) ); ?>"><?php esc_html_e( 'Connexion', 'asaka' ); ?></a>
		</div>
	</nav>
</div>
