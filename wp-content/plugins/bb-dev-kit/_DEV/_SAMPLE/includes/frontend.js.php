<?php

if( $settings->gallery_type === 'slider' ) :

	$global_settings  = \FLBuilderModel::get_global_settings();


	$args = array(
		'infinite' => true,
		'dots' => $settings->dots == '1',
		'slidesToShow' => !empty( $settings->gallery_columns ) ? intval( $settings->gallery_columns ) : 3,
		'autoplay' => $settings->autoplay == '1',
		'speed' => !empty( $settings->speed ) ? intval( $settings->speed ) : 300,
		'arrows' => $settings->arrows == '1',
		'autoplaySpeed' => intval( $settings->autoplaySpeed ),
		'centerMode' => $settings->centermode == '1',
		'centerPadding' => 0,
	);

	if( $settings->centermode == '1' ) {
		$args['centerPadding'] = '20%';
	}

	if( !empty( $settings->gallery_columns_medium ) || !empty( $settings->gallery_columns_responsive ) ) {

		$args['responsive'] = array();

		if( !empty( $settings->gallery_columns_medium ) ) {
			$args['responsive'][] = array(
				'breakpoint' => intval( $global_settings->medium_breakpoint ),
				'settings'   => array(
					'slidesToShow' => intval( $settings->gallery_columns_medium ),
				),
			);
		}

		if( !empty( $settings->gallery_columns_responsive ) ) {
			$args['responsive'][] = array(
				'breakpoint' => intval( $global_settings->responsive_breakpoint ),
				'settings'   => array(
					'slidesToShow' => intval ( $settings->gallery_columns_responsive ),
				),
			);
		}
	}
?>

jQuery( function ( $ ) {

	$( '.fl-node-<?php echo $id; ?> .gallery' ).on('init', function( event, slick ){

	    let $slick_buttons = $('.fl-node-<?php echo $id; ?>').find( '.slick-next, .slick-prev, .slick-dots');

	    $slick_buttons.on( 'click', function( event ) {
	    	event.stopPropagation();
	    });
	});

	$( '.fl-node-<?php echo $id ?> .gallery' ).slick( <?php echo json_encode( $args ) ?> );

});

<?php elseif( $settings->gallery_type === 'masonry' ) : ?>

	jQuery( function ( $ ) {

		$( '.fl-node-<?php echo $id; ?> .gallery' ).masonry({
			itemSelector: '.gallery-item',
			columnWidth: '.gallery-item',
			percentPosition: true,
			isAnimated: true,
			isFitWidth: true,
		});

	});

<?php endif; ?>