<?php
class Elementor_JmAutocomplete_Maps_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'jm_autocomplete_hero';
	}

	public function get_title() {
		return esc_html__( 'Hai Plugin Mapbox', 'jm-autocomplete' );
	}

	public function get_icon() {
		return 'eicon-image-hotspot';
	}

	public function get_categories() {
		return [ 'hai-plugin-category' ];
	}

	public function get_keywords() {
		return [ 'hai plugin', 'map' ];
	}

	protected function register_controls() {

		// Content Tab Start

		$this->start_controls_section(
			'section_maps',
			[
				'label' => esc_html__( 'Hai Plugin Mapbox', 'jm-autocomplete' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'jm-autocomplete' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Mapbox', 'jm-autocomplete' ),
			]
		);

		$this->end_controls_section();

		// Content Tab End


	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		?>

		<!-- Maps Start -->
        <section class="mapbox">
            <div class="wrap">
				<div id="directions-map" style="width: 100%; height: 400px;"></div>
            </div> 
        </section><!--end section-->
        <!-- Maps  End -->

		<?php
	}
}