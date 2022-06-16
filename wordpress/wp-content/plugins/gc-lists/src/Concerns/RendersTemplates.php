<?php

namespace GCLists\Concerns;

trait RendersTemplates
{
    /**
     * Render a php template. Accepts the template name without extension.
     * Template file must be in the resources folder, ie:
     * /resources/templates/[template].php
     *
     * $args is an associative array of variables available to the template.
     *
     * @param  string  $template
     * @param  array  $args
     */
    public function render(string $template, array $args = [])
    {
        extract($args);
        require_once(GC_LISTS_PLUGIN_BASE_PATH . "/resources/templates/{$template}.php");
    }
}
