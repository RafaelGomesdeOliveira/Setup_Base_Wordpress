<?php
    if(!function_exists('themeSetup')):
        function themeSetup(){
            // Agrega soporte para title tag
            add_theme_support('title-tag');
            add_theme_support('custom-logo');

        }   

        add_action('after_setup_theme', 'themeSetup');
    endif;

    if(!function_exists('themeMenus')):
        function themeMenus(){
            register_nav_menus(array(
                'primary' => esc_html__('Menu Principal', 'themeBase'),
                'secondary' => esc_html__('Menu Footer', 'themeBase'),
            ));
        }   

        add_action('after_setup_theme', 'themeMenus');
    endif;

    if(!function_exists('base_get_card_data')):
        // Dados do post atual (dentro do loop) usados em templates-parts/blog/card.php
        function base_get_card_data($post = null){
            $post = get_post($post);

            if(!$post){
                return array();
            }

            $categories = get_the_category($post->ID);

            return array(
                'url'       => get_permalink($post),
                'title'     => get_the_title($post),
                'excerpt'   => wp_strip_all_tags(get_the_excerpt($post)),
                'thumbnail' => get_the_post_thumbnail($post, 'medium_large', array('class' => 'w-full h-full object-cover')),
                'category'  => !empty($categories) ? $categories[0] : null,
                'date'      => get_the_date('', $post),
                'date_iso'  => get_the_date('c', $post),
            );
        }
    endif;