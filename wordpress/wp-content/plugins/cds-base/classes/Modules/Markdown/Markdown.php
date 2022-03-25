<?php

declare(strict_types=1);

namespace CDS\Modules\Markdown;

use League\HTMLToMarkdown\HtmlConverter;

class Markdown
{
    public static function register()
    {
        $instance = new self();

        add_action('rest_api_init', [$instance, 'addMarkdownToPages']);
    }

    public static function render($post)
    {
        $converter = new HtmlConverter(array('header_style' => 'atx'));
        return $converter->convert($post['content']['rendered']);
    }

    public function addMarkdownToPages()
    {
        /**
         * Add a 'markdown' field to the REST response for a page
         * Returns a `rendered` content in Markdown format
         */
        register_rest_field('page', 'markdown', array(
            'get_callback' => function ($post, $field_name, $request) {
                return Markdown::render($post);
            },
            'update_callback' => null,
            'schema' => array(
                'description' => __('Page content markdown', 'cds-snc'),
                'type'        => 'string'
            ),
        ));
    }
}
