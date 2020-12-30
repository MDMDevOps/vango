<div class="ac_image_slider theme-slider">

	<?php foreach( $settings->images as $image ) : ?>

		<figure class="image-slider-item">

			<?php $src = wp_get_attachment_image_src( intval( $image->photo ), $settings->imagesize ); ?>

			<?php if( !empty( $image->link ) ) : ?>
				<a href="<?php echo $image->link; ?>" rel="<?php echo _ac_flbuilder_link_rel( $image ); ?>" target="<?php echo $image->link_target; ?>">
			<?php else : ?>
				<span class="image-wrapper">
			<?php endif; ?>
				<img src="<?php echo esc_url_raw( $src[0] ); ?>" alt="<?php echo $image->title; ?>" title="<?php echo $image->title; ?>">
			<?php if( !empty( $image->link ) ) : ?>
				</a>
			<?php else : ?>
				</span>
			<?php endif; ?>
		</figure>

	<?php endforeach; ?>

</div>