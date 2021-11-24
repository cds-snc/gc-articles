<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cds-default
 */

declare(strict_types=1);

function wpse33151_getSubpages()
{
    global $post;
    $parent_page_is_current_page = '';

    $parents = get_post_ancestors($post->post_id);
    krsort($parents);
    $parents = array_merge(array(), $parents);

    if (is_home() || is_single()) {
        $id = get_option('page_for_posts');
        $parent = get_post_ancestors($id);
        $id = $parent[0];
    } elseif ($parents) {
        $id = $parents[0];
    } else {
        $id = $post->ID;
        $parent_page_is_current_page = "current_page_item";
    }

    $children = wp_list_pages('title_li=&child_of=' . $id . '&echo=0');

    $out = null;

    if ($children) {
        $out = '<div id="subpages" class="nav--about__desktop">';
        $out .= '<div class="nav--about__desktop__title ' . $parent_page_is_current_page . '">' . get_the_title($id) . '</div>';
        $out .= '<nav class="nav--about" aria-label="Table of contents: ' . get_the_title($id) . '">';
        $out .= '<ul>';
        $out .= $children;
        $out .= '</ul>';
        $out .= '</nav>';
        $out .= '</div>';
        $out .= '<details class="nav--about__mobile">';
        $out .= '<summary><div>' . get_the_title($id) . '</div></summary>';
        $out .= '<nav class="nav--about" aria-label="Table of contents: ' . get_the_title($id) . '">';
        $out .= '<ul>';
        $out .= $children;
        $out .= '</ul>';
        $out .= '</nav>';
        $out .= '</details>';
    }

    return $out;
}

$side_nav = wpse33151_getSubpages();
$has_side_nav = $side_nav ? true : false;

get_header();
?>

    <main property="mainContentOfPage" class="page container" resource="#wb-main" typeof="WebPageElement">

        <?php if ($has_side_nav) { ?>
        <div class="wp-block-columns">
            <div class="wp-block-column page__side-nav">   
                <?php echo($side_nav); ?>
            </div>
            <div class="wp-block-column page__content">   
        <?php } ?>

        <?php
        while (have_posts()) {
            the_post();
            get_template_part('template-parts/content', 'page');

            // If comments are open or we have at least one comment, load up the comment template.
            if (comments_open() || get_comments_number()) {
                comments_template();
            }
        } // End of the loop.
        ?>
        <?php if ($has_side_nav) { ?>
            </div>
            </div><!--end of .wp-block-columns -->
        <?php } ?>

    </main><!-- #main -->

<?php
get_footer();
