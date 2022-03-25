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

    public static function render($content)
    {
        $converter = new HtmlConverter(array('header_style' => 'atx'));
        return $converter->convert($content);
    }

    public function addMarkdownToPages()
    {

        $markdown = false;

        if (isset($_GET["markdown"])) {
            $markdown = filter_var($_GET["markdown"], FILTER_VALIDATE_BOOLEAN);
        }

        if (!$markdown) {
            return;
        }

        /**
         * Add a 'markdown' field to the REST response for a page
         * Returns content rendered in Markdown format
         */
         register_rest_field('page', 'markdown', array(
            'get_callback' => function ($post, $field_name, $request) {
                return [
                    'excerpt' => ["rendered" => Markdown::render($post['excerpt']['rendered'])],
                    'content' => ["rendered" => Markdown::render($post['content']['rendered'])]
                ];
            },
            'update_callback' => null,
            'schema' => array(
                'description' => __('Page content markdown', 'cds-snc'),
                'type'        => 'string'
            ),
         ));
    }
}
