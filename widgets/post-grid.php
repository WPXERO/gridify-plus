<?php

namespace GridifyPlus\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;


class Post_Grid extends Widget_Base {

    public function get_name() {
        return 'post-grid';
    }

    public function get_title() {
        return __('Post Grid', 'gridify-plus');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['gridify-plus'];
    }
    public function get_keywords() {
        return ['post', 'grid', 'blog', 'custom', 'gridify'];
    }
    // public function get_script_depends() {
    //     return ['gridify-plus'];
    // }
    public function get_style_depends() {
        return ['post-grid'];
    }
    protected function register_controls() {
        /**
         * !Content Section
         */
        $this->start_controls_section(
            'content_section_layout',
            [
                'label' => __('Layout', 'gridify-plus'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_responsive_control(
            'columns',
            [
                'label'          => esc_html__('Columns', 'gridify-plus'),
                'type'           => Controls_Manager::SELECT,
                'default'        => '3',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options'        => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .post-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ],
            ]
        );
        $this->add_responsive_control(
            'items_gap',
            [
                'label'     => esc_html__('Items Gap', 'gridify-plus'),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .post-grid' => 'grid-gap: {{SIZE}}px;',
                ],
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_content_query',
            [
                'label' => esc_html__('Query', 'gridify-plus'),
            ]
        );
        $this->add_control(
            'post_type',
            [
                'label'      => __('Post Type', 'gridify-plus'),
                'type'       => Controls_Manager::HIDDEN,
                'options'    => [
                    'post'   => __('Post', 'gridify-plus'),
                ],
                'default'    => 'post',
                'dynamic'    => ['active' => true],
            ]
        );
        $this->add_control(
            'posts_per_page',
            [
                'label'   => esc_html__('Post Limit', 'gridify-plus'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );
        $this->add_control(
            'source',
            [
                'label'       => _x('Source', 'Posts Query Control', 'gridify-plus'),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    ''        => esc_html__('Show All', 'gridify-plus'),
                    'by_name' => esc_html__('Manual Selection', 'gridify-plus'),
                ],
                'label_block' => true,
            ]
        );


        $this->add_control(
            'posts_categories',
            [
                'label'       => esc_html__('Categories', 'gridify-plus'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => gridifyplus_get_category('category'),
                'default'     => [],
                'label_block' => true,
                'multiple'    => true,
                'condition'   => [
                    'source' => 'by_name',
                ],
            ]
        );

        $this->add_control(
            'posts_excludes',
            [
                'label'       => esc_html__('Exclude Posts(s)', 'gridify-plus'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => 'post_id',
                'label_block' => true,
                'description' => esc_html__('Write post id here, if you want to exclude multiple posts so use comma as separator. Such as 1 , 2', 'gridify-plus'),
            ]
        );




        $this->add_control(
            'orderby',
            [
                'label'   => esc_html__('Order by', 'gridify-plus'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date'  => esc_html__('Date', 'gridify-plus'),
                    'title' => esc_html__('Title', 'gridify-plus'),
                    'ID'    => esc_html__('ID', 'gridify-plus'),
                    'rand'  => esc_html__('Random', 'gridify-plus'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__('Order', 'gridify-plus'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' => esc_html__('Descending', 'gridify-plus'),
                    'ASC'  => esc_html__('Ascending', 'gridify-plus'),
                ],
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => esc_html__('Show Pagination', 'gridify-plus'),
                'type'  => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'separator' => 'before',
            ]
        );


        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__('Posts', 'gridify-plus'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs(
            'item_tabs'
        );
        $this->start_controls_tab(
            'item_tab_normal',
            [
                'label' => esc_html__('Normal', 'gridify-plus'),
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'posts_background',
                'label'     => __('Background', 'gridify-plus'),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .gridify-plus .post-item',
            ]
        );
        $this->add_control(
            'item_padding',
            [
                'label'                 => esc_html__('Padding', 'gridify-plus'),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => ['px', '%', 'em'],
                'selectors'             => [
                    '{{WRAPPER}} .gridify-plus .post-item'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'item_margin',
            [
                'label'                 => esc_html__('Margin', 'gridify-plus'),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => ['px', '%', 'em'],
                'selectors'             => [
                    '{{WRAPPER}} .gridify-plus .post-item'    => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'item_border',
                'label'     => esc_html__('Border', 'gridify-plus'),
                'selector'  => '{{WRAPPER}} .gridify-plus .post-item',
            ]
        );
        $this->add_control(
            'item_radius',
            [
                'label'                 => esc_html__('Radius', 'gridify-plus'),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => ['px', '%', 'em'],
                'selectors'             => [
                    '{{WRAPPER}} .gridify-plus .post-item'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'item_shadow',
                'selector' => '{{WRAPPER}} .gridify-plus .post-item',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'item_tab_hover',
            [
                'label' => esc_html__('Hover', 'gridify-plus'),
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'posts_background_hover',
                'label'     => __('Background', 'gridify-plus'),
                'types'     => ['classic', 'gradient'],
                'selector'  => '{{WRAPPER}} .gridify-plus .post-item:hover',
            ]
        );
        $this->add_control(
            'item_hover_border_color',
            [
                'label'     => esc_html__('Border Color', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .post-item:hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'item_hover_shadow',
                'selector' => '{{WRAPPER}} .gridify-plus .post-item:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'gridify-plus'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs(
            'style_tabs_image'
        );
        $this->start_controls_tab(
            'image_normal',
            [
                'label' => esc_html__('Normal', 'gridify-plus'),
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'image_border',
                'label'    => esc_html__('Image Border', 'gridify-plus'),
                'selector' => '{{WRAPPER}} .gridify-plus .post-item .post-image',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'gridify-plus'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gridify-plus .post-item .post-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_shadow',
                'exclude'  => [
                    'shadow_position',
                ],
                'selector' => '{{WRAPPER}} .gridify-plus .post-item .post-image',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'image_hover',
            [
                'label' => esc_html__('Hover', 'gridify-plus'),
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'image_hover_border',
                'label'    => esc_html__('Image Border', 'gridify-plus'),
                'selector' => '{{WRAPPER}} .gridify-plus .post-item .post-image:hover',
            ]
        );

        $this->add_responsive_control(
            'image_hover_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'gridify-plus'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gridify-plus .post-item .post-image:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'image_hover_shadow',
                'exclude'  => [
                    'shadow_position',
                ],
                'selector' => '{{WRAPPER}} .gridify-plus .post-item .post-image:hover',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_title',
            [
                'label' => esc_html__('Title', 'gridify-plus'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Color', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .post-title .title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'hover_title_color',
            [
                'label'     => esc_html__('Hover Color', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .post-title .title:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__('Margin', 'gridify-plus'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gridify-plus .post-title .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => esc_html__('Typography', 'gridify-plus'),
                'selector' => '{{WRAPPER}} .gridify-plus .post-title .title',
            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_author',
            [
                'label'     => esc_html__('Author', 'gridify-plus'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'author_border_radius',
            [
                'label'      => __('Border Radius', 'gridify-plus'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gridify-plus .post-seller-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'author_image_heading',
            [
                'label'     => esc_html__('Image', 'gridify-plus'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_responsive_control(
            'author_image_spacing',
            [
                'label'     => esc_html__('Spacing', 'gridify-plus'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .author-name' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'author_name_heading',
            [
                'label'     => esc_html__('Name', 'gridify-plus'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'author_name_color',
            [
                'label'     => esc_html__('Color', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .author-name .name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'author_name_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .author-name .name:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'author_name_typography',
                'label'    => esc_html__('Typography', 'gridify-plus'),
                'selector' => '{{WRAPPER}} .gridify-plus .author-name .name',
            ]
        );

        $this->add_control(
            'author_role_heading',
            [
                'label'     => esc_html__('Role', 'gridify-plus'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );

        $this->add_control(
            'author_role_color',
            [
                'label'     => esc_html__('Color', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .author-depertment' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'author_role_typography',
                'label'    => esc_html__('Typography', 'gridify-plus'),
                'selector' => '{{WRAPPER}} .gridify-plus .author-depertment',
            ]
        );

        $this->add_responsive_control(
            'author_role_spacing',
            [
                'label'     => esc_html__('Spacing', 'gridify-plus'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .author-name' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_category',
            [
                'label'     => esc_html__('Category', 'gridify-plus'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'category_border',
                'label'     => esc_html__('Border', 'gridify-plus'),
                'selector'  => '{{WRAPPER}} .gridify-plus .post-category a',
            ]
        );
        $this->add_responsive_control(
            'category_radius',
            [
                'label'                 => esc_html__('Radius', 'gridify-plus'),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => ['px', '%', 'em'],
                'selectors'             => [
                    '{{WRAPPER}} .gridify-plus .post-category a'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'category_padding',
            [
                'label'      => esc_html__('Padding', 'gridify-plus'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gridify-plus .post-category a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'category_margin',
            [
                'label'      => esc_html__('Margin', 'gridify-plus'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gridify-plus .post-category a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'category_typography',
                'label'    => esc_html__('Typography', 'gridify-plus'),
                'selector' => '{{WRAPPER}} .gridify-plus .post-category a',
                'separator' => 'after'
            ]
        );
        $this->start_controls_tabs(
            'category_tabs'
        );
        $this->start_controls_tab(
            'category_tab_normal',
            [
                'label' => esc_html__('Normal', 'gridify-plus'),
            ]
        );
        $this->add_control(
            'category_color',
            [
                'label'     => esc_html__('Color', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .post-category a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'category_bg_color',
            [
                'label'     => esc_html__('Background', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .post-category a' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'category_tab_hover',
            [
                'label' => esc_html__('Hover', 'gridify-plus'),
            ]
        );
        $this->add_control(
            'hover_category_color',
            [
                'label'     => esc_html__('Color', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .post-category a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'hover_category_bg_color',
            [
                'label'     => esc_html__('Background', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .post-category a:hover' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'hover_category_border_color',
            [
                'label'     => esc_html__('Border Color', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus .post-category a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        $this->start_controls_section(
            'section_style_pagination',
            [
                'label'     => esc_html__('Pagination', 'gridify-plus'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_pagination' => 'yes',
                ],
            ]
        );
        $this->start_controls_tabs(
            'pagination_normal'
        );
        $this->start_controls_tab(
            'pagination_tab_active',
            [
                'label' => esc_html__('Active', 'gridify-plus'),
            ]
        );
        $this->add_control(
            'active_pagination_color',
            [
                'label'     => esc_html__('Color', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus-pagination .gridify-plus-active a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'active_pagination_background',
            [
                'label'     => esc_html__('Background', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus-pagination .gridify-plus-active a' => 'background:{{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'active_pagination_border',
                'label'     => esc_html__('Border', 'gridify-plus'),
                'selector'  => '{{WRAPPER}} .gridify-plus-pagination .gridify-plus-active a',
            ]
        );
        $this->add_control(
            'pagination_alignment',
            [
                'label'     => esc_html__('Alignment', 'gridify-plus'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'gridify-plus'),
                        'icon'  => 'eicon-h-align-left',
                    ],
                    'center'     => [
                        'title' => esc_html__('Center', 'gridify-plus'),
                        'icon'  => 'eicon-h-align-center',
                    ],
                    'flex-end'   => [
                        'title' => esc_html__('Right', 'gridify-plus'),
                        'icon'  => 'eicon-h-align-right',
                    ],
                ],
                'default'   => 'center',
                'toggle'    => false,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus-pagination' => 'justify-content: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'pagination_padding',
            [
                'label'                 => __('Padding', 'gridify-plus'),
                'type'                  => Controls_Manager::DIMENSIONS,
                'size_units'            => ['px', '%', 'em'],
                'selectors'             => [
                    '{{WRAPPER}} .gridify-plus-pagination  li a'    => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'pagination_spacing_top',
            [
                'label'     => esc_html__('Top Spacing', 'gridify-plus'),
                'type'      => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus-pagination' => 'margin-top: {{SIZE}}px;',
                ],
            ]
        );
        $this->add_responsive_control(
            'pagination_spacing',
            [
                'label'         => esc_html__('Space Between', 'gridify-plus'),
                'type'          => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus-pagination li' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'pagination_typography',
                'label'    => esc_html__('Typography', 'gridify-plus'),
                'selector' => '{{WRAPPER}} .gridify-plus-pagination li a,
                    {{WRAPPER}} .gridify-plus-pagination li span',
                'exclude'  => ['line_height', 'letter_spacing'],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'pagination_tab_normal',
            [
                'label' => esc_html__('Normal', 'gridify-plus'),
            ]
        );
        $this->add_control(
            'pagination_color',
            [
                'label'     => esc_html__('Color', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus-pagination li:not(.gridify-plus-active) a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'pagination_background',
            [
                'label'     => esc_html__('Background', 'gridify-plus'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gridify-plus-pagination li:not(.gridify-plus-active) a' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'pagination_border',
                'label'     => esc_html__('Border', 'gridify-plus'),
                'selector'  => '{{WRAPPER}} .gridify-plus-pagination li:not(.gridify-plus-active) a',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }
    public function render_custom_post_items() {
        $settings = $this->get_settings_for_display();
        $wp_query = gridifyplus_posts_get_query($settings);
        if (!empty($wp_query)) :
            while ($wp_query->have_posts()) :
                $wp_query->the_post();
                $image_url = wp_get_attachment_image_url(get_post_thumbnail_id(), 'large');
                $category_list = get_the_term_list(get_the_ID(), 'category', '', ', ');
                $categories = explode(',', $category_list);
                $category = $categories[wp_rand(0, count($categories) - 1)];
?>
                <div class="post-item">
                    <div class="post-item-box">
                        <div class="post-image">
                            <a href="<?php echo esc_url(get_the_permalink()); ?>">
                                <img class="post-img" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_html(get_the_title()); ?>">
                            </a>
                        </div>
                        <div class="post-content">
                            <a class="post-title" href="<?php echo esc_url(get_the_permalink()); ?>">
                                <h3 class="title"><?php echo esc_html(get_the_title()); ?></h3>
                            </a>
                            <div class="post-seller-wrap">
                                <div class="post-seller-image">
                                    <?php echo wp_kses_post(get_avatar(get_the_author_meta('ID'), 48)); ?>
                                </div>
                                <div class="seller-info-wrap">
                                    <span class="author-name">
                                        <div class="author-depertment">
                                            <?php
                                            $aid = get_the_author_meta('ID');
                                            echo esc_html(gridifyplus_get_user_role($aid));
                                            ?>
                                        </div>
                                        <a class="name" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                            <?php echo wp_kses_post(get_the_author()); ?>
                                        </a>
                                    </span>

                                    <div class="post-category">
                                        <?php echo wp_kses_post($category); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            endwhile;
            wp_reset_postdata();
        endif;
    }


    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <div class="gridify-plus">
            <div class="post-grid">
                <?php $this->render_custom_post_items(); ?>
            </div>
            <?php if ($this->get_settings_for_display('show_pagination') === 'yes') : ?>
                <div class="gridify-plus-pagination">
                    <?php gridifyplus_pagination($settings);
                    ?>
                </div>
            <?php endif; ?>
        </div>
<?php
    }
}
