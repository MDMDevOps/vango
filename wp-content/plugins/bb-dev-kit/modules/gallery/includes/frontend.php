

	<?php

	echo $module->gallery( do_shortcode( "[gallery {$settings->shortcode_atts}]" ) );




	?>

<?php if ( $settings->slider_arrows === '1' ) : ?>

	<button aria-label="Previous" class="prev">
		<?php if ( !empty( $settings->slider_previous ) ) : ?>
			<span class="gallary-nav-icon <?php echo $settings->slider_previous; ?>"></span>
		<?php else : ?>
			<span class="gallary-nav-icon">«</span>
		<?php endif; ?>
	</button>

	<button aria-label="Next" class="next">
		<?php if ( !empty( $settings->slider_previous ) ) : ?>
			<span class="gallary-nav-icon <?php echo $settings->slider_next; ?>"></span>
		<?php else : ?>
			<span class="gallary-nav-icon">»</span>
		<?php endif; ?>
	</button>

<?php endif; ?>





