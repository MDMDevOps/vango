<?php

$global_settings  = \FLBuilderModel::get_global_settings();

$args = [
	'infinite' => true,
	'dots' => $settings->slider_dots == '1',
	'slidesToShow' => intval( $settings->gallery_columns ),
	'autoplay' => $settings->slider_autoplay == '1',
	'speed' => !empty( $settings->slider_speed ) ? intval( $settings->slider_speed ) : 300,
	'arrows' => $settings->slider_arrows == '1',
	'autoplaySpeed' => intval( $settings->autoplaySpeed ),
	'focusOnSelect' => true,
	'prevArrow' => ".fl-node-{$id} .prev",
	'nextArrow' => ".fl-node-{$id} .next",
];