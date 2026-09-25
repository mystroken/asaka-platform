<?php
/**
 * Title: Fiche formation
 * Slug: asaka/formation-detail
 * Categories: asaka
 * Post Types: formation
 * Description: Écran 01 du prototype — hero, carte d'inscription, programme, avis, facturation organisation.
 *
 * @package asaka
 */

$asaka_catalogue = get_post_type_archive_link( 'formation' ) ?: home_url( '/formations/' );
$asaka_enrol     = function_exists( 'asaka_campus_url' ) ? asaka_campus_url( '/enrol/index.php' ) : '#';
?>
<!-- wp:group {"tagName":"section","className":"asa-hero","layout":{"type":"constrained","contentSize":"1200px"}} -->
<section class="wp-block-group asa-hero"><!-- wp:columns {"className":"asa-hero__grid"} -->
<div class="wp-block-columns asa-hero__grid"><!-- wp:column {"width":"60%","className":"asa-hero__intro"} -->
<div class="wp-block-column asa-hero__intro" style="flex-basis:60%"><!-- wp:paragraph {"className":"asa-breadcrumb"} -->
<p class="asa-breadcrumb"><a href="<?php echo esc_url( $asaka_catalogue ); ?>">Catalogue</a> › <a href="<?php echo esc_url( $asaka_catalogue ); ?>">Supply chain</a> › Supply Chain Humanitaire</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"asa-badge"} -->
<p class="asa-badge">Certifiante</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Supply Chain Humanitaire &amp; Gestion des Achats</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"asa-lead"} -->
<p class="asa-lead">Maîtriser la chaîne d'approvisionnement d'urgence : prévision des besoins, appels d'offres conformes, gestion des stocks et redevabilité envers les bailleurs.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"asa-facts"} -->
<ul class="wp-block-list asa-facts"><!-- wp:list-item -->
<li>28 h de contenu</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>6 modules · 24 leçons</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Intermédiaire</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>FR · EN</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>1 240 inscrits</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:group {"className":"asa-instructor","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group asa-instructor"><!-- wp:paragraph {"className":"asa-avatar"} -->
<p class="asa-avatar">IS</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"asa-instructor__text"} -->
<p class="asa-instructor__text"><strong>Ibrahim Sow</strong><br>Responsable logistique · 14 ans de terrain</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:heading {"level":2,"className":"asa-hero__subtitle"} -->
<h2 class="wp-block-heading asa-hero__subtitle">Ce que vous saurez faire</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"asa-checks asa-checks--grid"} -->
<ul class="wp-block-list asa-checks asa-checks--grid"><!-- wp:list-item -->
<li>Construire un plan d'approvisionnement chiffré</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Piloter un appel d'offres conforme</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Suivre stocks et péremptions en entrepôt</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Documenter la redevabilité bailleurs</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:group {"className":"asa-card asa-card--floating asa-enrol","layout":{"type":"default"}} -->
<div class="wp-block-group asa-card asa-card--floating asa-enrol"><!-- wp:group {"className":"asa-media","layout":{"type":"default"}} -->
<div class="wp-block-group asa-media"><!-- wp:paragraph -->
<p>Vidéo de présentation</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"asa-card__body","layout":{"type":"default"}} -->
<div class="wp-block-group asa-card__body"><!-- wp:html -->
<div class="asa-price-switch" data-asa-prices='{"XOF":"45 000 FCFA","EUR":"69 €","USD":"75 $"}'>
	<label class="asa-inline-field">Pays de facturation
		<select class="asa-field__control" data-asa-currency>
			<option value="XOF">Sénégal · FCFA</option>
			<option value="EUR">France · EUR</option>
			<option value="USD">International · USD</option>
		</select>
	</label>
	<div class="asa-price"><span class="asa-price__amount" data-asa-amount>45 000 FCFA</span><span class="asa-price__note">paiement unique</span></div>
	<p class="asa-meta">Accès 12 mois · certificat inclus · TVA incluse</p>
</div>
<!-- /wp:html -->

<!-- wp:buttons {"className":"asa-enrol__actions"} -->
<div class="wp-block-buttons asa-enrol__actions"><!-- wp:button {"className":"asa-enrol__cta"} -->
<div class="wp-block-button asa-enrol__cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $asaka_enrol ); ?>">S'inscrire à cette formation</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="#">Ajouter à ma liste</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:list {"className":"asa-checks asa-enrol__perks"} -->
<ul class="wp-block-list asa-checks asa-enrol__perks"><!-- wp:list-item -->
<li>Certificat vérifiable à la fin du parcours</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>2 devoirs corrigés par le formateur</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Mobile money accepté · Orange, Wave, MTN</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Remboursement sous 14 jours</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"asa-section","layout":{"type":"constrained","contentSize":"1200px"}} -->
<section class="wp-block-group asa-section"><!-- wp:columns {"className":"asa-body-grid"} -->
<div class="wp-block-columns asa-body-grid"><!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%"><!-- wp:heading -->
<h2 class="wp-block-heading">Programme</h2>
<!-- /wp:heading -->

<!-- wp:details {"className":"asa-module","showContent":true} -->
<details class="wp-block-details asa-module" open><summary>Module 1 · Comprendre la chaîne d'approvisionnement</summary><!-- wp:paragraph {"className":"asa-module__meta"} -->
<p class="asa-module__meta">4 leçons · 3 h 40</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"asa-lessons"} -->
<ul class="wp-block-list asa-lessons"><!-- wp:list-item -->
<li>Panorama des acteurs et des flux <em>18 min</em></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Cycle d'approvisionnement d'urgence <em>26 min</em></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Cartographier les fournisseurs locaux <em>22 min</em></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Indicateurs de performance logistique <em>20 min</em></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></details>
<!-- /wp:details -->

<!-- wp:details {"className":"asa-module"} -->
<details class="wp-block-details asa-module"><summary>Module 2 · Appels d'offres et conformité bailleurs</summary><!-- wp:paragraph {"className":"asa-module__meta"} -->
<p class="asa-module__meta">5 leçons · 5 h 10</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"asa-lessons"} -->
<ul class="wp-block-list asa-lessons"><!-- wp:list-item -->
<li>Rédiger un dossier d'appel d'offres <em>32 min</em></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Quiz de module · 10 questions <em>15 min</em></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></details>
<!-- /wp:details -->

<!-- wp:paragraph {"className":"asa-module-more"} -->
<p class="asa-module-more">4 autres modules</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:quote {"className":"asa-review"} -->
<blockquote class="wp-block-quote asa-review"><!-- wp:paragraph {"className":"asa-review__score"} -->
<p class="asa-review__score"><strong>4,8</strong> sur 5 · 312 avis</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>« Les modèles de dossier d'appel d'offres sont directement réutilisables. »</p>
<!-- /wp:paragraph --><cite>Khady D. · Responsable logistique</cite></blockquote>
<!-- /wp:quote -->

<!-- wp:group {"className":"asa-card asa-org","layout":{"type":"default"}} -->
<div class="wp-block-group asa-card asa-org"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Votre organisation peut payer</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Demandez une facture au nom de votre structure : virement bancaire et bon de commande acceptés.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/organisations/#facture' ) ); ?>">Demander une facture</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->
