<?php
namespace ElementorHelloWorld\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Elementor Hello World
*
* Elementor widget for hello world.
*
* @since 1.0.0
*/
class Hello_World extends Widget_Base {

/**
* Retrieve the widget name.
*
* @since 1.0.0
*
* @access public
*
* @return string Widget name.
*/
public function get_name() {
	return 'hello-world';
}

/**
* Retrieve the widget title.
*
* @since 1.0.0
*
* @access public
*
* @return string Widget title.
*/
public function get_title() {
	return __( 'Hello World', 'elementor-hello-world' );
}

/**
* Retrieve the widget icon.
*
* @since 1.0.0
*
* @access public
*
* @return string Widget icon.
*/
public function get_icon() {
	return 'eicon-posts-ticker';
}

/**
* Retrieve the list of categories the widget belongs to.
*
* Used to determine where to display the widget in the editor.
*
* Note that currently Elementor supports only one category.
* When multiple categories passed, Elementor uses the first one.
*
* @since 1.0.0
*
* @access public
*
* @return array Widget categories.
*/
public function get_categories() {
	return [ 'general' ];
}

/**
* Retrieve the list of scripts the widget depended on.
*
* Used to set scripts dependencies required to run the widget.
*
* @since 1.0.0
*
* @access public
*
* @return array Widget scripts dependencies.
*/
public function get_script_depends() {
	return [ 'elementor-hello-world' ];
}

/**
* Register the widget controls.
*
* Adds different input fields to allow the user to change and customize the widget settings.
*
* @since 1.0.0
*
* @access protected
*/

public function get_random_text() {
    return ['random text'];
}

public function my_posts_list() {
    $list = get_posts( array(
        'post_type'         => 'post',
    ) );

    $options = array();

    if ( ! empty( $list ) && ! is_wp_error( $list ) ) {
        foreach ( $list as $post ) {
           $options[ $post->ID ] = $post->post_title;
        }
    }

    return $options;
}

protected function _register_controls() {
	$this->start_controls_section(
		'section_content',
		[
			'label' => __( 'Content', 'elementor-hello-world' ),
		]
	);

	$this->add_control(
		'title',
		[
			'label' => __( 'Title', 'elementor-hello-world' ),
			'type' => Controls_Manager::TEXT,
		]
	);

	$this->add_control(
		'show_comments',
		[
			'label' => __( 'Show comments', 'elementor-hello-world' ),
			'type' => Controls_Manager::SWITCHER,
		]
	);

    $repeater = new \Elementor\Repeater();

    $repeater->add_control(
            'list_content', [
                'label' => __( 'Content', 'elementor-hello-world' ),
                'type' => \Elementor\Controls_Manager::WYSIWYG,
                'default' => __( 'List Content' , 'elementor-hello-world' ),
                'show_label' => false,
            ]
        );

    $this->add_control(
            'list',
            [
                'label' => __( 'Repeater List', 'plugin-domain' ),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'list_content' => __( 'Item content. Click the edit button to change this text.', 'elementor-hello-world' ),
                    ],
                    [
                        'list_content' => __( 'Item content. Click the edit button to change this text.', 'elementor-hello-world' ),
                    ],
                ],
            ]
        );


    $this->add_control(
        'choose_source',
        [
            'label' => __( 'choose_source', 'elementor-hello-world' ),
            'type' => Controls_Manager::SELECT,
            'default' => 'dsdssd',
            'options' => [
                    'posts'  =>  'posts choice',
                    'dupa'  => 'dupa choice',
                    'pipa'  =>  __('pipa choice', 'elementor-hello-world')],
        ]
    );

    $this->add_control(
            'content',
            [
                'label' => __( 'Content', 'elementor-hello-world' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __( 'Content', 'elementor-hello-world' ),
            ]
        );


	$this->end_controls_section();

	$this->start_controls_section(
		'section_style',
		[
			'label' => __( 'Style', 'elementor-hello-world' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_control(
		'text_transform',
		[
			'label' => __( 'Text Transform', 'elementor-hello-world' ),
			'type' => Controls_Manager::SELECT,
			'default' => '',
			'options' => [
				'' => __( 'None', 'elementor-hello-world' ),
				'uppercase' => __( 'UPPERCASE', 'elementor-hello-world' ),
				'lowercase' => __( 'lowercase', 'elementor-hello-world' ),
				'capitalize' => __( 'Capitalize', 'elementor-hello-world' ),
			],
			'selectors' => [
				'{{WRAPPER}} .title' => 'text-transform: {{VALUE}};',
			],
		]
	);

	$this->end_controls_section();
}

/**
* Render the widget output on the frontend.
*
* Written in PHP and used to generate the final HTML.
*
* @since 1.0.0
*
* @access protected
*/
protected function render() {
	$settings = $this->get_settings_for_display();

	echo '<div class="title">';
	echo $settings['title'];
	echo '</div>';
    echo $settings['choose_source'];

    if ( $settings['list'] ) {
            echo '<dl>';
            foreach (  $settings['list'] as $item ) {
                echo '<dd>' . $item['list_content'] . '</dd>';
            }
            echo '</dl>';
        }

 if ( $settings['choose_source'] == 'posts') {
        $args = array(
        'numberposts' => 10
    );

global $post; // required
$args = array('category' => -5); // exclude category 9
$custom_posts = get_posts($args);
foreach($custom_posts as $post) : setup_postdata($post);
    ?> <h3> <?php echo the_title(); ?> </h3> <?php
    // echo $settings['show_comments'];
    if ( $settings['show_comments'] == "yes") {
        echo get_comments_number() . " comments";
    }
endforeach;
        }



            $this->add_inline_editing_attributes( 'content', 'advanced' );?>
                    <div <?php echo $this->get_render_attribute_string( 'content' ); ?>><?php echo $settings['content']; ?></div> <?php

}







/**
* Render the widget output in the editor.
*
* Written as a Backbone JavaScript template and used to generate the live preview.
*
* @since 1.0.0
*
* @access protected
*/
protected function _content_template() {
	?>
    <# 
        view.addInlineEditingAttributes( 'content', 'advanced' );
    #>



    <div class="title">
        {{{ settings.title }}}
    </div>
    <# if ( settings.list.length ) { #>
        <dl>
            <# _.each( settings.list, function( item ) { #>
                <dt class="elementor-repeater-item-{{ item._id }}">{{{ item.list_title }}}</dt>
                <dd>{{{ item.list_content }}}</dd>
            <# }); #>
            </dl>
        <# } #>

    <div>
                <div>{{{ settings.content }}}</div>
    </div>
	<?php
}
}
