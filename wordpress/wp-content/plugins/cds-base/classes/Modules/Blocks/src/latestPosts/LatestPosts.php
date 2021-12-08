<?php

namespace CDS\Modules\Blocks\src\latestPosts;

use Wa72\HtmlPageDom\HtmlPage;

class LatestPosts
{
    public const HIDE_READ_MORE_CLASS = 'read-more--hidden';

    public static function renderBlock($block_content = '', $block = [])
    {
        $block_content = str_replace([',…', '!…', '.…'], '…', $block_content);

        $crawler = @new HtmlPage($block_content);

        if (isset($block['attrs']['displayAuthor']) && $block['attrs']['displayAuthor']) {
            // fails gracefully if it doesn't find anything
            $crawler->filter('.wp-block-latest-posts__post-author')->each(function ($node) {

                $authorUsername = str_replace('by ', '', $node->text());
                $authorLink = sprintf(
                    'by <a href="%s">%s</a>',
                    esc_url_raw(get_site_url(get_current_blog_id()) . "/author/" . $authorUsername),
                    esc_html($authorUsername)
                );

                $node->setInnerHtml($authorLink);
            });
        }

        if (isset($block['attrs']['showReadMore']) && !$block['attrs']['showReadMore']) {
            $crawler->filter('.wp-block-latest-posts__list')->addClass(self::HIDE_READ_MORE_CLASS);
        }

        return $crawler;
    }

    public static function excerptMore($more)
    {
        global $post;

        return sprintf(
            '… <a class="read-more" href="%s">%s<span class="wb-sl"> of %s</span></a>',
            get_permalink($post->ID),
            __('Read more', 'cds-snc'),
            esc_html($post->post_title)
        );
    }
}
