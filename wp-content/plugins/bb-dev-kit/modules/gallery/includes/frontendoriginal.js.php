<?php

// Mobile-first defaults
// slidesToShow: 1,
// slidesToScroll: 1,
// scrollLock: true,
// dots: '#resp-dots',
// arrows: {
//   prev: '.glider-prev',
//   next: '.glider-next'
// },
// responsive: [
//   {
//     // screens greater than >= 775px
//     breakpoint: 775,
//     settings: {
//       // Set to `auto` and provide item width to adjust to viewport
//       slidesToShow: 'auto',
//       slidesToScroll: 'auto',
//       itemWidth: 150,
//       duration: 0.25
//     }
//   },{
//     // screens greater than >= 1024px
//     breakpoint: 1024,
//     settings: {
//       slidesToShow: 2,
//       slidesToScroll: 1,
//       itemWidth: 150,
//       duration: 0.25
//     }
//   }
// ]

$desktop = [
	'slidesToShow' => 4,
	'slidesToScroll' => 1,
	'arrows' => [
		'prev' => ".fl-node-{$id} .glider-prev",
		'next' => ".fl-node-{$id} .glider-next"
	],
	'dots' => ".fl-node-{$id} .dots",
	'rewind' => true,
];

$tablet = [
	'slidesToShow' => 3,
];

$phone = [
	'slidesToShow' => 1,
];
/**
 * If either phone or tablet, we have to work our way up in size
 */
if ( !empty( $phone ) || !empty( $tablet ) ) {
	/**
	 * Has both
	 */
	if ( !empty( $phone ) && !empty( $tablet ) ) {
		/**
		 * Make phone the default settings
		 */
		$settings = array_merge( $desktop, $phone );
		/**
		 * Add tablet as a responsive setting
		 */
		$settings['responsive'][] = [
			'breakpoint' => 767, // responsive breakpoint
			'settings' => $tablet
		];
		/**
		 * Add desktop as a responsive setting
		 */
		$settings['responsive'][] = [
			'breakpoint' => 921, // medium breakpoint
			'settings' => $desktop
		];
	}
	/**
	 * Has only phone
	 */
	elseif ( !empty( $phone ) ) {
		/**
		 * Make phone the default settings
		 */
		$settings = array_merge( $desktop, $phone );
		/**
		 * Add desktop as a responsive setting
		 */
		$settings['responsive'][] = [
			'breakpoint' => 921, // medium breakpoint
			'settings' => $desktop
		];
	}
	/**
	 * Has only tablet
	 */
	elseif ( !empty( $tablet ) ) {
		/**
		 * Make tablet the default settings
		 */
		$settings = array_merge( $desktop, $tablet );
		/**
		 * Add desktop as a responsive setting
		 */
		$settings['responsive'][] = [
			'breakpoint' => 921, // medium breakpoint
			'settings' => $desktop
		];
	}
}
/**
 * Else if it's just desktop settings
 */
else {
	$settings = $desktop;
}
?>

jQuery(function ($) {
	'use strict';
	new Glider(document.querySelector('.fl-node-<?php echo $id; ?> .gallery'), <?php echo json_encode( $settings ); ?>);
});