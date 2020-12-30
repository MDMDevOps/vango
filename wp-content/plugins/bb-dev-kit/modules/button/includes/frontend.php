<?php

printf( '<a href="%s" class="%s" target="%s" rel="%s">',
	$settings->link,
	$module->getClass( $settings ),
	$settings->link_target,
	$settings->link_rel,
);

echo '<span class="bbdk-button-inner">';

if( !empty( $settings->icon ) && $settings->icon_position === 'before' ) :

	printf( '<span class="bbdk-button-icon-container"><span class="%s"></span></span>', $settings->icon );

endif;

printf( '<span class="bbdk-button-text">%s</span>', $settings->text );

if( !empty( $settings->icon ) && $settings->icon_position === 'after' ) :

	printf( '<span class="bbdk-button-icon-container"><span class="%s"></span></span>', $settings->icon );

endif;

echo '</span>';

echo '</a>';