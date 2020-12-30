<?php

echo '<div class="bbdk-button-group">';

foreach( $settings->buttons as $button ) {

	echo '<div class="button-container">';

	\FLBuilder::render_module_html( 'DKButton', $button );

	echo '</div>';

}

echo '</div>';

