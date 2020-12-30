asdfasdf

<div class="ac_testimonial_slider theme-slider">

	<?php foreach( $settings->testimonials as $testimonial ) : ?>

		<div class="ac_testimonial_slide">

			<blockquote class="ac_testimonial">

				<?php echo $testimonial->testimonial; ?>

				<?php if( !empty( $testimonial->cite ) ) : ?>
					<cite><?php echo $testimonial->cite; ?></cite>
				<?php endif; ?>

			</blockquote>

			<?php if( $settings->showstars == '1' ) : ?>
				<div class="star-ratings rating-<?php echo $testimonial->stars; ?>">
					<?php for( $i = 0; $i < 5; $i++ ) : ?>
						<span class="<?php echo $i < $testimonial->stars ? 'star-rating gold' : 'star-rating'; ?>"><svg enable-background="new 0 0 448.941 448.941" viewBox="0 0 448.941 448.941" xmlns="http://www.w3.org/2000/svg"><path d="m448.941 168.353h-161.338l-63.132-168.353-63.132 168.353h-161.339l121.478 106.293-65.36 174.295 168.353-84.176 168.353 84.176-65.361-174.296z"/></svg></span>
					<?php endfor; ?>
				</div>
			<?php endif; ?>

		</div>

	<?php endforeach; ?>
</div>