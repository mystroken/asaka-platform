<?php
/**
 * Rendu du bloc asaka/site-footer (composant Footer du design system).
 *
 * @package asaka
 */

defined( 'ABSPATH' ) || exit;

$asaka_catalogue = get_post_type_archive_link( 'formation' ) ?: home_url( '/formations/' );
$asaka_columns   = array(
	__( 'Formations', 'asaka' )    => array(
		__( 'Catalogue', 'asaka' )              => $asaka_catalogue,
		__( 'Parcours certifiants', 'asaka' )   => home_url( '/parcours/' ),
		__( 'Vérifier un certificat', 'asaka' ) => asaka_campus_url( '/admin/tool/certificate/index.php' ),
	),
	__( 'Organisations', 'asaka' ) => array(
		__( 'Former une équipe', 'asaka' )    => home_url( '/organisations/' ),
		__( 'Demander une facture', 'asaka' ) => home_url( '/organisations/#facture' ),
		__( 'Partenaires', 'asaka' )          => home_url( '/partenaires/' ),
	),
	'Asaka Academy'                => array(
		__( 'À propos', 'asaka' )    => home_url( '/a-propos/' ),
		__( 'Aide et FAQ', 'asaka' ) => home_url( '/aide/' ),
		__( 'Contact', 'asaka' )     => home_url( '/contact/' ),
	),
);
$asaka_legal     = array(
	__( 'Mentions légales', 'asaka' )     => home_url( '/mentions-legales/' ),
	__( 'Conditions générales', 'asaka' ) => home_url( '/conditions-generales/' ),
	__( 'Confidentialité', 'asaka' )      => home_url( '/confidentialite/' ),
);
$asaka_payments  = array( 'Orange Money', 'Wave', 'MTN MoMo', __( 'Carte bancaire', 'asaka' ) );
$asaka_logo      = get_template_directory_uri() . '/assets/images/asaka-logo-inverse.svg';
?>
<footer <?php echo get_block_wrapper_attributes( array( 'class' => 'asa-footer' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>>
	<div class="asa-footer__inner">
		<div class="asa-footer__grid">
			<div class="asa-footer__brand">
				<img src="<?php echo esc_url( $asaka_logo ); ?>" alt="ASAKA Academy" width="112" height="56">
				<p><?php esc_html_e( "Des formations certifiantes pour les professionnels de l'humanitaire et du développement, pensées pour le terrain en Afrique francophone.", 'asaka' ); ?></p>
				<div class="asa-footer__pay">
					<?php foreach ( $asaka_payments as $asaka_payment ) : ?>
						<span class="asa-footer__chip"><?php echo esc_html( $asaka_payment ); ?></span>
					<?php endforeach; ?>
				</div>
			</div>
			<?php foreach ( $asaka_columns as $asaka_title => $asaka_links ) : ?>
				<div>
					<p class="asa-footer__title"><?php echo esc_html( $asaka_title ); ?></p>
					<ul class="asa-footer__list">
						<?php foreach ( $asaka_links as $asaka_label => $asaka_url ) : ?>
							<li><a href="<?php echo esc_url( $asaka_url ); ?>"><?php echo esc_html( $asaka_label ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="asa-footer__bottom">
			<span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> ASAKA Academy</span>
			<nav aria-label="<?php esc_attr_e( 'Légal', 'asaka' ); ?>">
				<?php foreach ( $asaka_legal as $asaka_label => $asaka_url ) : ?>
					<a href="<?php echo esc_url( $asaka_url ); ?>"><?php echo esc_html( $asaka_label ); ?></a>
				<?php endforeach; ?>
			</nav>
			<?php // Sélecteur de langue : branché sur l'extension multilingue une fois D-06 tranchée. ?>
			<span class="asa-lang"><a class="is-active" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-current="true">FR</a><a href="<?php echo esc_url( home_url( '/en/' ) ); ?>" lang="en">EN</a></span>
		</div>
	</div>
</footer>
