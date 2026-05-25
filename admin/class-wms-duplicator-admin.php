<?php

    if (!defined('ABSPATH')) {
        exit;
    }

class WMS_Duplicator_Admin {

    public function run() {

        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));

        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));

        add_filter('post_row_actions', array($this, 'duplicate_link'), 10, 2);

        add_filter('page_row_actions', array($this, 'duplicate_link'), 10, 2);

        add_action('admin_action_wms_duplicate_post', array($this, 'duplicate_post'));

        add_action('admin_menu', array($this, 'admin_menu'));
    }

    public function enqueue_styles() {

        wp_enqueue_style(
            'wms-duplicator-admin',
            WMS_DUPLICATOR_URL . 'admin/css/admin.css',
            array(),
            WMS_DUPLICATOR_VERSION
        );
    }

    public function enqueue_scripts() {

        wp_enqueue_script(
            'wms-duplicator-admin',
            WMS_DUPLICATOR_URL . 'admin/js/admin.js',
            array('jquery'),
            WMS_DUPLICATOR_VERSION,
            true
        );
    }

    public function duplicate_link($actions, $post) {

        /*
        ----------------------------------------
        SKIP WOOCOMMERCE PRODUCTS
        ----------------------------------------
        */

        if ($post->post_type === 'product') {
            return $actions;
        }

        if (!current_user_can('edit_posts')) {
            return $actions;
        }

        $url = wp_nonce_url(
            admin_url(
                'admin.php?action=wms_duplicate_post&post=' . $post->ID
            ),
            'wms_duplicate_post_' . $post->ID
        );

        $actions['wms_duplicate'] =
            '<a href="' . esc_url($url) . '">Duplicate</a>';

        return $actions;
    }

    public function duplicate_post() {

        if (
            !isset($_GET['post']) ||
            !isset($_GET['_wpnonce'])
        ) {
            wp_die('Invalid Request');
        }

        $post_id = absint($_GET['post']);

        check_admin_referer(
            'wms_duplicate_post_' . $post_id
        );

        $post = get_post($post_id);

        if (!$post) {
            wp_die('Post not found');
        }

        $new_post = array(
            'post_title'   => $post->post_title . ' Copy',
            'post_content' => $post->post_content,
            'post_excerpt' => $post->post_excerpt,
            'post_status'  => 'draft',
            'post_type'    => $post->post_type,
            'post_author'  => get_current_user_id(),
        );

        $new_post_id = wp_insert_post($new_post);

        if (is_wp_error($new_post_id)) {
            wp_die('Failed to duplicate content');
        }

        $taxonomies = get_object_taxonomies($post->post_type);

        foreach ($taxonomies as $taxonomy) {

            $terms = wp_get_object_terms(
                $post_id,
                $taxonomy,
                array('fields' => 'ids')
            );

            wp_set_object_terms(
                $new_post_id,
                $terms,
                $taxonomy
            );
        }

        $meta_data = get_post_meta($post_id);

        foreach ($meta_data as $meta_key => $meta_values) {

            /*
            ----------------------------------------
            SKIP OLD SLUG
            ----------------------------------------
            */

            if ($meta_key === '_wp_old_slug') {
                continue;
            }

            foreach ($meta_values as $meta_value) {

                update_post_meta(
                    $new_post_id,
                    $meta_key,
                    maybe_unserialize($meta_value)
                );
            }
        }

        $thumbnail_id = get_post_thumbnail_id($post_id);

        if ($thumbnail_id) {

            set_post_thumbnail(
                $new_post_id,
                $thumbnail_id
            );
        }

        /*
        ----------------------------------------
        REDIRECT TO LIST PAGE
        ----------------------------------------
        */

        wp_safe_redirect(
            admin_url(
                'edit.php?post_type=' . $post->post_type
            )
        );

        exit;
    }

    public function admin_menu() {

        add_menu_page(
            'WMS Duplicator',
            'WMS Duplicator',
            'manage_options',
            'wms-duplicator',
            array($this, 'admin_page'),
            'dashicons-admin-page',
            80
        );
    }

    public function admin_page() {

        require_once WMS_DUPLICATOR_PATH . 'admin/partials/admin-display.php';
    }
}