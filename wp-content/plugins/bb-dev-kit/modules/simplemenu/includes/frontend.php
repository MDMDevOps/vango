<nav class="dk-simple-menu">

	<ul class="menu">

		<?php foreach ( $settings->links as $link ) : ?>

			<li class="<?php echo $module->getMenuItemClass( $link ); ?>">

				<a href="<?php echo $link->link; ?>">

					<span class="menu-item-content"><?php echo $link->text; ?></span>

					<?php if( !empty( $link->icon ) ) : ?>
						<span class="menu-item-icon <?php echo $link->icon; ?>"></span>
					<?php endif; ?>

				</a>

			</li>

		<?php endforeach; ?>

	</ul>

</nav>