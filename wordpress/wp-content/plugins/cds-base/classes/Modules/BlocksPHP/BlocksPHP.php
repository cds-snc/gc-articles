<?php

namespace CDS\Modules\BlocksPHP;

use CDS\Modules\Contact\ContactForm;
use CDS\Modules\FormRequestSite\RequestSite;
use CDS\Modules\Subscribe\SubscriptionForm as SubscriptionForm;

class BlocksPHP
{
    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'init']);
    }

    public function init(): void
    {
        add_action('init', [$this, 'registerBlocks']);
    }

    public function registerBlocks()
    {
        register_block_type(__DIR__ . '/build/subscribe/', [
            'render_callback' => function ($attributes, $content, $block): string {
                $form = new SubscriptionForm();
                return $form->render($attributes);
            },
            'attributes' => [
                'placeholderValue' => [
                    'type' => 'string',
                    "default" => "preview@example.com"
                ],
                'listId' => [
                    'type' => 'string',
                    "default" => ""
                ],
                'emailLabel' => [
                    'type' => 'string',
                    "default" => __("Enter your email:", "cds-snc")
                ],
                'subscribeLabel' => [
                    'type' => 'string',
                    "default" => __("Subscribe", "cds-snc")
                ],
            ]
        ]);

        // internal blocks
        if (is_super_admin()) {
            register_block_type(__DIR__ . '/build/contact/', [
                'render_callback' => function ($attributes, $content, $block): string {
                    $form = new ContactForm();
                    return $form->render($attributes);
                },
                'attributes' => [
                    'placeholderValue' => [
                        'type' => 'string',
                        "default" => "preview@example.com"
                    ]
                ]
            ]);

            register_block_type(__DIR__ . '/build/request/', [
                'render_callback' => function ($attributes, $content, $block): string {
                    $form = new RequestSite();
                    return $form->render($attributes);
                },
                'attributes' => [
                    'placeholderValue' => [
                        'type' => 'string',
                        "default" => "preview@example.com"
                    ]
                ]
            ]);
        }
    }
}
