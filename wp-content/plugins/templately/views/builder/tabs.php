<div class="templately-builder-nav">
	<ul class="nav-tab-wrapper">
		<?php
			/**
			 * @var array $template_types
			 */
			foreach ( $template_types as $typename => $type ) {
				echo wp_kses( sprintf( '<li class="nav-tab %1$s"><a href="%2$s">%3$s</a></li>', esc_attr( $typename ), esc_url( $type['url'] ), $type['label'] ), 'post' );
			}
		?>
	</ul>

	<ul class="subsubsub">
		<?php
			/**
			 * @var array $tabs
			 */
			if(!empty($tabs)){
				foreach ( $tabs as $class => $tab ) {
					echo sprintf( '<li class="%1$s">%2$s</li>', esc_attr( $class ), wp_kses( $tab, 'post' ) );
				}
			}
		?>
	</ul>
</div>