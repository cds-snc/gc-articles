<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

use WP_Posts_List_Table;

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}
if (!class_exists('WP_Posts_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php');
}

class PostTable extends WP_Posts_List_Table
{
    // phpcs:disable
    public function column_title($post)
    {
        global $mode;

        if ($this->hierarchical_display) {
            if (0 === $this->current_level && (int) $post->post_parent > 0) {
                // Sent level 0 by accident, by default, or because we don't know the actual level.
                $find_main_page = (int) $post->post_parent;

                while ($find_main_page > 0) {
                    $parent = get_post($find_main_page);

                    if (is_null($parent)) {
                        break;
                    }

                    $this->current_level++;
                    $find_main_page = (int) $parent->post_parent;

                    if (! isset($parent_name)) {
                        /** This filter is documented in wp-includes/post-template.php */
                        $parent_name = apply_filters('the_title', $parent->post_title, $parent->ID);
                    }
                }
            }
        }
        // phpcs:enable

        $can_edit_post = current_user_can('edit_post', $post->ID);

        if ($can_edit_post && 'trash' !== $post->post_status) {
            $lock_holder = wp_check_post_lock($post->ID);

            if ($lock_holder) {
                $lock_holder   = get_userdata($lock_holder);
                $locked_avatar = get_avatar($lock_holder->ID, 18);
                /* translators: %s: User's display name. */
                $locked_text = esc_html(sprintf(__('%s is currently editing'), $lock_holder->display_name));
            } else {
                $locked_avatar = '';
                $locked_text   = '';
            }

            echo '<div class="locked-info"><span class="locked-avatar">' . $locked_avatar . '</span> <span class="locked-text">' . $locked_text . "</span></div>\n";
        }

        $pad = str_repeat('&#8212; ', $this->current_level);
        echo '<strong>';

        $title = _draft_or_post_title();

        // @CDS-SNC start modification
        _post_states($post);
        print("<span>&mdash; </span>");
        // end modification

        if ($can_edit_post && 'trash' !== $post->post_status) {
            printf(
                '<a class="row-title" href="%s" aria-label="%s">%s%s</a>',
                get_edit_post_link($post->ID),
                /* translators: %s: Post title. */
                esc_attr(sprintf(__('&#8220;%s&#8221; (Edit)'), $title)),
                $pad,
                $title
            );
        } else {
            printf(
                '<span>%s%s</span>',
                $pad,
                $title
            );
        }


        if (isset($parent_name)) {
            $post_type_object = get_post_type_object($post->post_type);
            echo ' | ' . $post_type_object->labels->parent_item_colon . ' ' . esc_html($parent_name);
        }

        echo "</strong>\n";

        if (
            'excerpt' === $mode
            && ! is_post_type_hierarchical($this->screen->post_type)
            && current_user_can('read_post', $post->ID)
        ) {
            if (post_password_required($post)) {
                echo '<span class="protected-post-excerpt">' . esc_html(get_the_excerpt()) . '</span>';
            } else {
                echo esc_html(get_the_excerpt());
            }
        }

        get_inline_data($post);
    }
}
