<?php

declare(strict_types=1);

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package cds-default
 */

if (! function_exists('cds_posted_on')) {
    /**
     * Prints HTML with meta information for the current post-date/time.
     */
    function cds_posted_on(): void
    {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if (get_the_time('U') !== get_the_modified_time('U')) {
            $time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf(
            $time_string,
            esc_attr(get_the_date(DATE_W3C)),
            esc_html(get_the_date()),
            esc_attr(get_the_modified_date(DATE_W3C)),
            esc_html(get_the_modified_date())
        );

        $posted_on = $time_string;

        echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}

if (! function_exists('cds_posted_by')) {
    /**
     * Prints HTML with meta information for the current author.
     */
    function cds_posted_by(): void
    {
        $byline = sprintf(
            /* translators: %s: post author. */
            esc_html_x('by %s', 'post author', 'cds-snc'),
            '<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
        );

        echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}

if (! function_exists('cds_entry_footer')) {
    /**
     * Prints HTML with meta information for the categories, tags and comments.
     */
    function cds_entry_footer(): void
    {
        // Hide category and tag text for pages.
        if (get_post_type() === 'post') {
            $pId = get_the_ID();
            /* translators: used between list items, there is a space after the comma */
            // $categories_list = get_the_category_list(esc_html__(', ', 'cds-snc'));
            echo "<ul class='list-inline'>".cds_category_links($pId).'</ul>';
        }

        // edit_post_link(
            // sprintf(
                // wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    // __('Edit <span class="screen-reader-text">%s</span>', 'cds-snc'),
                    // [
                        // 'span' => [
                            // 'class' => [],
                        // ],
                    // ]
                // ),
                // wp_kses_post(get_the_title())
            // ),
            // '<span class="edit-link">',
            // '</span>'
        // );
    }
}

if (! function_exists('cds_post_thumbnail')) {
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function cds_post_thumbnail(): void
    {
        if (post_password_required() || is_attachment() || ! has_post_thumbnail()) {
            return;
        }

        if (is_singular()) {
            ?>

            <div class="post-thumbnail">
                <?php the_post_thumbnail('full', ['class' => 'img-responsive full-width']); ?>
            </div><!-- .post-thumbnail -->

        <?php
        } else { ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php
                    the_post_thumbnail(
                'post-thumbnail',
                [
                    'alt' => the_title_attribute(
                        [
                            'echo' => false,
                        ]
                    ),
                    'class' => 'img-responsive thumbnail',
                ]
            );
                ?>
            </a>

            <?php
        } // End is_singular().
    }
}

if (! function_exists('wp_body_open')) {
    /**
     * Shim for sites older than 5.2.
     *
     * @link https://core.trac.wordpress.org/ticket/12563
     */
    function wp_body_open(): void
    {
        do_action('wp_body_open');
    }
}
