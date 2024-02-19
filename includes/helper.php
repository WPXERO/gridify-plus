<?php

if (!function_exists('gridifyplus_get_user_role')) {
    function gridifyplus_get_user_role($id) {
        $user = new \WP_User($id);
        return array_shift($user->roles);
    }
}

if (!function_exists('gridifyplus_get_category')) {
    function gridifyplus_get_category($taxonomy = 'category') {
        $post_options = [];
        $post_categories = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
        ]);

        if (is_wp_error($post_categories)) {
            return $post_options;
        }

        if (false !== $post_categories and is_array($post_categories)) {
            foreach ($post_categories as $category) {
                $post_options[$category->slug] = $category->name;
            }
        }

        return $post_options;
    }
}

if (!function_exists('gridifyplus_posts_get_query')) {
    function gridifyplus_posts_get_query($settings) {
        if (get_query_var('paged')) {
            $paged = get_query_var('paged');
        } elseif (get_query_var('page')) {
            $paged = get_query_var('page');
        } else {
            $paged = 1;
        }

        $posts_excludes = ($settings['posts_excludes']) ? explode(',', $settings['posts_excludes']) : [];

        $query_args                  = [
            'post_type'           => $settings['post_type'],
            'post_status'         => 'publish',
            'posts_per_page'      => $settings['posts_per_page'],
            'ignore_sticky_posts' => 1,
            'meta_query'          => [],
            'tax_query'           => ['relation' => 'AND'],
            'paged'               => $paged,
            'order'               => $settings['order'],
            'orderby'             => $settings['orderby'],
            'post__not_in'        => $posts_excludes,
        ];
        if ('by_name' === $settings['source'] and !empty($settings['posts_categories'])) {
            $query_args['tax_query'][] = [
                'taxonomy'     => 'product_cat',
                'field'        => 'slug',
                'terms'        => $settings['posts_categories'],
                'post__not_in' => $posts_excludes,
            ];
        }

        if ($settings['post_type'] == 'product') {
            $product_visibility_term_ids = wc_get_product_visibility_term_ids();

            if ('by_name' === $settings['source'] and !empty($settings['product_tags'])) {
                $query_args['tax_query'][] = [
                    'taxonomy'     => 'product_tag',
                    'field'        => 'slug',
                    'terms'        => $settings['product_tags'],
                    'post__not_in' => $posts_excludes,
                ];
            }

            if ('yes' == $settings['hide_free']) {
                $query_args['meta_query'][] = [
                    'key'     => '_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'DECIMAL',
                ];
            }

            if ('yes' == $settings['hide_out_stock']) {
                $query_args['tax_query'][] = [
                    [
                        'taxonomy' => 'product_visibility',
                        'field'    => 'term_taxonomy_id',
                        'terms'    => $product_visibility_term_ids['outofstock'],
                        'operator' => 'NOT IN',
                    ],
                ]; // WPCS: slow query ok.
            }

            switch ($settings['show_product_type']) {
                case 'featured':
                    $query_args['tax_query'][] = [
                        'taxonomy' => 'product_visibility',
                        'field'    => 'term_taxonomy_id',
                        'terms'    => $product_visibility_term_ids['featured'],
                    ];
                    break;
                case 'onsale':
                    $product_ids_on_sale    = wc_get_product_ids_on_sale();
                    $product_ids_on_sale[]  = 0;
                    $query_args['post__in'] = $product_ids_on_sale;
                    break;
                case 'best_selling':
                    $query_args['meta_key'] = 'total_sales'; // WPCS: slow query ok.
                    $query_args['orderby']  = 'meta_value_num';
                    break;
                case 'top_rated':
                    $query_args['meta_key'] = '_wc_average_rating'; // WPCS: slow query ok.
                    $query_args['orderby']  = 'meta_value_num';
                    break;
                case 'recent':
                    $query_args['orderby'] = 'date';
                    break;
                default:
                    # code...
                    break;
            }
        }


        switch ($settings['orderby']) {
            case 'price':
                $query_args['meta_key'] = '_price'; // WPCS: slow query ok.
                $query_args['orderby']  = 'meta_value_num';
                break;
            case 'rand':
                $query_args['orderby'] = 'rand';
                break;
            case 'sales':
                $query_args['meta_key'] = 'total_sales'; // WPCS: slow query ok.
                $query_args['orderby']  = 'meta_value_num';
                break;
            default:
                $query_args['orderby'] = 'title';
        }
        return new \WP_Query($query_args);
    }
}

if (!function_exists('gridifyplus_pagination')) {
    function gridifyplus_pagination($settings) {

        $wp_query = gridifyplus_posts_get_query($settings);
        /** Stop execution if there's only 1 page */
        if ($wp_query->max_num_pages <= 1) {
            return;
        }

        if (is_front_page()) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        }
        $max = intval($wp_query->max_num_pages);

        /** Add current page to the array */
        if ($paged >= 1) {
            $links[] = $paged;
        }

        /** Add the pages around the current page to the array */
        if ($paged >= 3) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if (($paged + 2) <= $max) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }

        echo '<ul class="gridify-plus-pagination">' . "\n";

        /** Previous Post Link */
        if (get_previous_posts_link()) {
            echo '<li>' . wp_kses_post(get_previous_posts_link('<span class="eicon-arrow-left"></span>')) . '</li>' . "\n";
        }

        /** Link to first page, plus ellipses if necessary */
        if (!in_array(1, $links)) {
            $class = 1 == $paged ? ' class="current"' : '';

            echo '<li' . esc_attr($class) . '><a href="' . esc_url(get_pagenum_link(1)) . '" target="_self">1</a></li>' . "\n";

            if (!in_array(2, $links)) {
                echo '<li class="gridify-plus-pagination-dot-dot"><span>...</span></li>';
            }
        }

        /** Link to current page, plus 2 pages in either direction if necessary */
        sort($links);
        foreach ((array) $links as $link) {
            $class = $paged == $link ? ' class="gridify-plus-active"' : '';
            echo '<li' . esc_attr($class) . '><a href="' . esc_url(get_pagenum_link($link)) . '" target="_self">' . esc_html($link) . '</a></li>' . "\n";
        }

        /** Link to last page, plus ellipses if necessary */
        if (!in_array($max, $links)) {
            if (!in_array($max - 1, $links)) {
                echo '<li class="gridify-plus-pagination-dot-dot"><span>...</span></li>' . "\n";
            }

            $class = $paged == $max ? ' class="gridify-plus-active"' : '';
            echo '<li' . esc_attr($class) . '><a href="' . esc_url(get_pagenum_link($max)) . '" target="_self">' . esc_html($max) . '</a></li>' . "\n";
        }

        /** Next Post Link */
        if (get_next_posts_link()) {
            echo '<li>' . wp_kses_post(get_next_posts_link('<span class="eicon-arrow-right"></span>')) . '</li>' . "\n";
        }

        echo '</ul>' . "\n";
    }
}
