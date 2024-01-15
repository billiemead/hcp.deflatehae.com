<?php

class ConallEdgeClassFullScreenMenuOpener extends ConallEdgeClassWidget {
    public function __construct() {
        parent::__construct(
            'edgtf_full_screen_menu_opener', // Base ID
            esc_html__( 'Conall Full Screen Menu Opener', 'conall' ),
	        array( 'description' => esc_html__( 'Display a "hamburger" icon that opens the full screen menu area', 'conall' ) )
        );

		$this->setParams();
    }

	protected function setParams() {

		$this->params = array(
			array(
				'name'			=> 'fullscreen_menu_opener_icon_color',
				'type'			=> 'textfield',
				'title'			=> esc_html__( 'Icon Color', 'conall' ),
				'description'	=> esc_html__( 'Define color for Side Area opener icon', 'conall' )
			),
            array(
                'name' => 'fullscreen_menu_opener_predefined_icon_size',
                'type' => 'dropdown',
                'title' => esc_html__( 'Predefined Icon Size', 'conall' ),
                'options' => array(
                    '' => esc_html__( 'Default', 'conall' ),
                    'small' => esc_html__( 'Small', 'conall' ),
                    'normal' => esc_html__( 'Normal', 'conall' ),
                    'medium' => esc_html__( 'Medium', 'conall' ),
                    'large' => esc_html__( 'Large', 'conall' )
                )
            )
		);
	}

    public function widget($args, $instance) {

		$fullscreen_icon_styles = array();

		if ( !empty($instance['fullscreen_menu_opener_icon_color']) ) {
			$fullscreen_icon_styles[] = 'background-color: ' . $instance['fullscreen_menu_opener_icon_color'];
		}

        $icon_size = '';
        if ( conall_edge_options()->getOptionValue('fullscreen_menu_opener_predefined_icon_size') ) {
            $icon_size = conall_edge_options()->getOptionValue('fullscreen_menu_opener_predefined_icon_size');
        } 
        if (!empty($instance['fullscreen_menu_opener_predefined_icon_size']) && $instance['fullscreen_menu_opener_predefined_icon_size'] !== '') {
            $icon_size = $instance['fullscreen_menu_opener_predefined_icon_size'];
        }
		?>
        <a href="javascript:void(0)" class="edgtf-fullscreen-menu-opener <?php echo esc_attr( $icon_size ); ?>">
        	<span class="edgtf-fullscreen-menu-button-wrapper">
        		<span class="edgt-fullscreen-menu-lines">
        			<span class="edgtf-fullscreen-menu-line edgtf-line-1" <?php conall_edge_inline_style($fullscreen_icon_styles) ?>></span>
        			<span class="edgtf-fullscreen-menu-line edgtf-line-2" <?php conall_edge_inline_style($fullscreen_icon_styles) ?>></span>
        			<span class="edgtf-fullscreen-menu-line edgtf-line-3" <?php conall_edge_inline_style($fullscreen_icon_styles) ?>></span>
        		</span>
        	</span> 
        </a>
    <?php }
}