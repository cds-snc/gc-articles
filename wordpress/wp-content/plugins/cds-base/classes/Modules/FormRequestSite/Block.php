<?php

namespace CDS\Modules\FormRequestSite;

use CDS\Modules\FormRequestSite\RequestSite;
use CDS\Utils;

class Block
{
    public function __construct()
    {
        add_action('plugins_loaded', [ $this, 'init' ]);
    }

    public function init(): void
    {
        add_action('init', [ $this, 'registerBlock' ]);
    }

    public function registerBlock()
    {
        if (is_admin() && !is_super_admin() && !Utils::isWpEnv()) {
            return;
        }

        /* TODO */
        register_block_type_from_metadata(
            __DIR__,
            [
                'render_callback' => [ $this, 'renderCallback' ],
                'attributes' => [
                    'placeholderValue' => [
                        'type' => 'string',
                        "default" => "preview@example.com"
                    ]
                ]
            ]
        );
    }

    public function renderCallback($attributes, $content, $block): string
    {
        $form = new RequestSite();
        return $form->render($attributes);
    }
}
