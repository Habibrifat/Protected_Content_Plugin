<?php

namespace Elementor_Protected_content;

use \Elementor\Widget_Base;


class Elementor_Protected_content_widget extends Widget_Base
{
    public function get_name()
    {
        return 'Protected-Content';
    }

    public function get_title()
    {
        return esc_html__( 'Protected-Content', 'epc' );
    }

    public function get_icon()
    {
        return 'eicon-lock';
    }

    public function get_custom_help_url()
    {
        return 'https://go.elementor.com/widget-name';
    }

    public function get_categories()
    {
        return [ 'basic','general' ];
    }

    public function get_keywords()
    {
        return [ 'keyword', 'keyword' ];
    }

    public function get_script_depends()
    {
        return [ 'script-handle' ];
    }

    public function get_style_depends()
    {
        return [ 'style-handle' ];
    }

    protected function register_controls()
    {

        $this->register_content_controls();
        $this->register_style_controls();


    }

    function register_content_controls(){

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'epc' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'password',
            [
                'label' => esc_html__( 'Password', 'epc' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Password', 'epc' ),
            ]
        );
        $this->add_control(
            'message',
            [
                'label' => esc_html__( 'Message', 'epc' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Non Protected Message', 'epc' ),
            ]
        );
        $this->add_control(
            'protected_message',
            [
                'label' => esc_html__( 'Protected Message', 'epc' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__( 'Protected Message', 'epc' ),
            ]
        );


        $this->end_controls_section();
    }

    function register_style_controls(){

        $this->start_controls_section(
            'style_section_message',
            [
                'label' => esc_html__( 'Protected Message Style', 'epc' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'message_color',
            [
                'label' => esc_html__( 'Protected Message Color', 'epc' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default'=> '#000000',
                'selectors' => [
                    '{{WRAPPER}} .protected_message' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'protected_message_typography',
                'selector' => '{{WRAPPER}} .protected_message',
            ]
        );

        $this->end_controls_section();

    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_inline_editing_attributes( 'message', 'none' );


        $this->add_render_attribute( 'message',[
            'class'=>'protected_message'
        ] );

     if ( !isset($_POST['submit'])){
         ?>
         <p <?php echo $this->get_render_attribute_string( 'message' ); ?>><?php echo esc_html($settings['message']); ?></p>
         <div>
             <form action="<?php the_permalink(); ?>" method="post">
                 <input type="hidden" name="ph" value="<?php echo md5($settings['password'])?>">
                 <label>Input Password</label><br/>
                 <input type="password" name="password"><br/>
                 <button type="submit" name="submit">Submit</button>
             </form>
         </div>
         <?php
     }else{
         if (isset($_POST['password']) && ($_POST['password']) != '' ){
             $hash = $_POST['ph'];
             $password = $_POST['password'];
             if ( $hash == md5($password)){
                 ?>
                        <p><?php echo esc_html($settings['protected_message'])?></p>
                 <?php
             }else{
                 ?>
                 <p>Password Didn't Match</p>
                 <?php
             }
         }
     }


    }

    protected function content_template_()
    {
        ?>
        <#
        console.log(settings);
        view.addInlineEditingAttributes( 'heading_one', 'none' );
        view.addInlineEditingAttributes( 'heading_two', 'basic' );

        view.addRenderAttribute( 'heading_one', {'class':'heading-one-style'} );
        view.addRenderAttribute( 'heading_two', {'class':'heading-two-style'} );
        #>
        <{{{ settings.heading_selector }}}>
        <span {{{ view.getRenderAttributeString( 'heading_one' ) }}} >{{{ settings.heading_one }}}</span><span> </span>
        <span {{{ view.getRenderAttributeString( 'heading_two' ) }}} >{{{ settings.heading_two }}}</span>

        </{{{ settings.heading_selector }}}>

        <?php
    }
}