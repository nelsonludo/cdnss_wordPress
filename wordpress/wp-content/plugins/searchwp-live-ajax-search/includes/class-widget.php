<?php

use SearchWP\Settings as SearchWP_Settings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SearchWP_Live_Search_Widget.
 *
 * The SearchWP Live Ajax Search Widget
 *
 * @since 1.0
 */
class SearchWP_Live_Search_Widget extends WP_Widget {

	/**
	 * Register the Widget with WordPress.
     *
     * @since 1.0
	 */
	public function __construct() {

		parent::__construct(
			'searchwp_live_search',
			esc_html__( 'SearchWP Live Search', 'searchwp-live-ajax-search' ),
			[ 'description' => esc_html__( 'SearchWP Live Search', 'searchwp-live-ajax-search' ) ]
		);
	}

	/**
	 * Front-end display of widget.
     *
     * @since 1.0
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		/**
		 * Filter the widget title.
		 *
		 * @since 1.0
		 *
		 * @param string $title    The widget title.
		 */
		$title = apply_filters( 'widget_title', $instance['title'] );

		$destination = empty( $instance['destination'] ) ? '' : $instance['destination'];
		$placeholder = empty( $instance['placeholder'] ) ? esc_html__( 'Search for...', 'searchwp-live-ajax-search' ) : $instance['placeholder'];
		$engine      = empty( $instance['engine'] ) ? 'default' : $instance['engine'];
		$config      = empty( $instance['config'] ) ? 'default' : $instance['config'];

		echo wp_kses_post( $args['before_widget'] );

		/**
		 * Action hook before the widget.
		 *
		 * @since 1.0
		 */
		do_action( 'searchwp_live_search_before_widget' );

		if ( ! empty( $title ) ) {
			echo wp_kses_post( $args['before_title'] . $title . $args['after_title'] );
		}

		/**
		 * Action hook for the widget title.
		 *
		 * @since 1.0
		 *
		 * @param array $args The widget arguments.
		 */
		do_action(
			'searchwp_live_search_widget_title',
			[
				'before_title' => $args['before_title'],
				'title'        => $title,
				'after_title'  => $args['after_title'],
			]
		);

		/**
		 * Action hook before the form.
		 *
		 * @since 1.0
		 */
		do_action( 'searchwp_live_search_widget_before_form' );
		?>
        <form role="search" method="get" class="searchwp-live-search-widget-search-form" action="<?php echo esc_url( $destination ); ?>">
            <?php
			/**
			 * Action hook before the field.
			 *
			 * @since 1.0
			 */
			do_action( 'searchwp_live_search_widget_before_field' );
			?>
            <label>
                <span class="screen-reader-text"><?php esc_html_e( 'Search for:', 'searchwp-live-ajax-search' ); ?></span>
                <input type="search" class="search-field" placeholder="<?php echo esc_attr( $placeholder ); ?>" value="" name="swpquery" data-swplive="true" data-swpengine="<?php echo esc_attr( $engine ); ?>" data-swpconfig="<?php echo esc_attr( $config ); ?>" title="<?php echo esc_attr( $placeholder ); ?>" autocomplete="off">
            </label>
            <?php
			/**
			 * Action hook after the field.
			 *
			 * @since 1.0
			 */
			do_action( 'searchwp_live_search_widget_after_field' );
			?>
            <input type="submit" class="search-submit" value="<?php esc_html_e( 'Search', 'searchwp-live-ajax-search' ); ?>">
            <?php
			/**
			 * Action hook after submit.
			 *
			 * @since 1.0
			 */
			do_action( 'searchwp_live_search_widget_after_submit' );
			?>
        </form>
        <?php
		/**
		 * Action hook after the form.
		 *
		 * @since 1.0
		 */
		do_action( 'searchwp_live_search_widget_after_form' );
		?>
		<?php

		echo wp_kses_post( $args['after_widget'] );

		/**
		 * Action hook after the widget.
		 *
		 * @since 1.0
		 */
		do_action( 'searchwp_live_search_after_widget' );
	}

	/**
	 * Back-end widget form.
     *
     * @since 1.0
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

        $this->form_title_html( $instance );
        $this->form_engines_html( $instance );
        $this->form_configs_html( $instance );
        $this->form_advanced_html( $instance );
	}

	/**
	 * Get available engines.
     *
     * @since 1.7.0
     *
	 * @return array
	 */
	private function form_get_engines() {

		$engines = [];

		if ( ! class_exists( 'SearchWP' ) ) {
			return $engines;
		}

		$searchwp_engines = SearchWP_Settings::get_engines();

		foreach ( $searchwp_engines as $engine => $engine_settings ) {
			$engines[ $engine ] = $engine_settings->get_label();
		}

		return $engines;
	}

	/**
     * Back-end widget form part: Title.
     *
     * @since 1.7.0
     *
	 * @param array $instance Previously saved values from database.
	 */
	private function form_title_html( $instance ) {

		$widget_title = isset( $instance['title'] ) ? $instance['title'] : esc_html__( 'Search', 'searchwp-live-ajax-search' );

		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'searchwp-live-ajax-search' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $widget_title ); ?>">
        </p>
		<?php
	}

	/**
	 * Back-end widget form part: Engines.
	 *
	 * @since 1.7.0
	 *
	 * @param array $instance Previously saved values from database.
	 */
	private function form_engines_html( $instance ) {

		$engines = $this->form_get_engines();

		if ( empty( $engines ) ) {
			return;
		}

		// We'll piggyback SearchWP itself to pull a list of search engines.
		$widget_engine = isset( $instance['engine'] ) ? $instance['engine'] : 'default';
		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'engine' ) ); ?>"><?php esc_html_e( 'SearchWP Engine:', 'searchwp-live-ajax-search' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'engine' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'engine' ) ); ?>">
				<?php foreach ( $engines as $engine_name => $engine_label ) : ?>
                    <option value="<?php echo esc_attr( $engine_name ); ?>" <?php selected( $widget_engine, $engine_name ); ?>><?php echo esc_html( $engine_label ); ?></option>
				<?php endforeach; ?>
            </select>
        </p>
		<?php
	}

	/**
	 * Back-end widget form part: Configs.
	 *
	 * @since 1.7.0
	 *
	 * @param array $instance Previously saved values from database.
	 */
	private function form_configs_html( $instance ) {

		// We're going to utilize SearchWP_Live_Search_Form to populate the config dropdown.
		$widget_config = isset( $instance['config'] ) ? $instance['config'] : 'default';

		$form = searchwp_live_search()->get( 'Form' );

		?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'config' ) ); ?>"><?php esc_html_e( 'Configuration:', 'searchwp-live-ajax-search' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'config' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'config' ) ); ?>">
				<?php foreach ( $form->configs as $config => $val ) : ?>
                    <option value="<?php echo esc_attr( $config ); ?>" <?php selected( $widget_config, $config ); ?>><?php echo esc_html( $config ); ?></option>
				<?php endforeach; ?>
            </select>
        </p>
		<?php
	}

	/**
	 * Back-end widget form part: Advanced.
	 *
	 * @since 1.7.0
	 *
	 * @param array $instance Previously saved values from database.
	 */
	private function form_advanced_html( $instance ) {

		$widget_placeholder = isset( $instance['placeholder'] ) ? $instance['placeholder'] : esc_html__( 'Search for...', 'searchwp-live-ajax-search' );
		$widget_destination = isset( $instance['destination'] ) ? $instance['destination'] : '';

		$swp_uniqid = uniqid( 'swp' );

		?>
        <p>
	        <a href="#" class="button" onclick="this.parentNode.nextElementSibling.style.display = 'block'; this.parentNode.style.display = 'none';"><?php esc_html_e( 'Advanced', 'searchwp-live-ajax-search' ); ?></a>
	        <div id="searchwp-live-search-widget-advanced-<?php echo sanitize_key( $swp_uniqid ); ?>" style="display:none;">
	            <p>
	                <label for="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>"><?php esc_html_e( 'Placeholder:', 'searchwp-live-ajax-search' ); ?></label>
	                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'placeholder' ) ); ?>" type="text" value="<?php echo esc_attr( $widget_placeholder ); ?>">
	            </p>
	            <p>
	                <label for="<?php echo esc_attr( $this->get_field_id( 'destination' ) ); ?>"><?php esc_html_e( 'Destination fallback URL (optional):', 'searchwp-live-ajax-search' ); ?></label>
	                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'destination' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'destination' ) ); ?>" type="text" value="<?php echo esc_attr( $widget_destination ); ?>">
	            </p>
	        </div>
				</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
     *
     * @since 1.0
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {

		return [
			'title'       => ! empty( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '',
			'destination' => ! empty( $new_instance['destination'] ) ? wp_strip_all_tags( $new_instance['destination'] ) : '',
			'placeholder' => ! empty( $new_instance['placeholder'] ) ? wp_strip_all_tags( $new_instance['placeholder'] ) : '',
			'engine'      => ! empty( $new_instance['engine'] ) ? wp_strip_all_tags( $new_instance['engine'] ) : '',
			'config'      => ! empty( $new_instance['config'] ) ? wp_strip_all_tags( $new_instance['config'] ) : '',
		];
	}
}
