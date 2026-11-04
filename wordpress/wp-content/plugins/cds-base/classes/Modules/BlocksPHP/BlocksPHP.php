<?php

namespace CDS\Modules\BlocksPHP;

use CDS\Utils;

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
        // return early if viewing the admin interface but not a superadmin
        if (is_admin() && !is_super_admin() && !Utils::isWpEnv()) {
            return;
        }

        register_block_type(__DIR__ . '/build/site-counter/', [
            'render_callback' => function ($attributes, $content, $block): string {
                try {
                    return sprintf("<div class='site-counter'>%s</div>", wp_count_sites()["all"]);
                } catch (e) {
                    return "";
                }
            },
            'attributes' => []
        ]);
    }
}
