<?php

declare(strict_types=1);

function cds_posted_on(): void
{
    echo '<gcds-text size="small"><time datetime="' . esc_attr(get_the_date(DATE_W3C)) . '">' . esc_html(get_the_date()) . '</time></gcds-text>';
}

function cds_posted_by(): void
{
    echo '<gcds-text size="small">' . esc_html_x('by', 'post author', 'cds-snc') . ' <gcds-link href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</gcds-link></gcds-text>';
}

function cds_entry_footer(): void
{
    if (get_post_type() !== 'post') {
        return;
    }
    echo '<ul class="list-inline">';
    foreach (get_the_category() as $category) {
        echo '<li><gcds-link href="' . esc_url(get_category_link($category)) . '" rel="category tag">[' . esc_html($category->name) . ']</gcds-link></li>';
    }
    echo '</ul>';
}

function cds_post_thumbnail(): void
{
    if (post_password_required() || is_attachment() || !has_post_thumbnail()) {
        return;
    }
    if (is_singular()) {
        the_post_thumbnail('full');
    } else {
        echo '<gcds-link href="' . esc_url(get_permalink()) . '">';
        the_post_thumbnail('post-thumbnail', ['alt' => get_the_title()]);
        echo '</gcds-link>';
    }
}
