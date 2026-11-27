<?php

declare(strict_types=1);

require_once __DIR__ . '/block-rendering.php';

function cds_gcds_block_definitions(): array
{
    $definitions = [
        'heading' => [
            'title' => __('GCDS Heading', 'cds-snc'),
            'content' => true,
            'fields' => ['tag' => ['type' => 'string', 'enum' => ['h2', 'h3', 'h4', 'h5', 'h6'], 'default' => 'h2']],
        ],
        'text' => [
            'title' => __('GCDS Text', 'cds-snc'),
            'content' => true,
            'fields' => [
                'size' => ['type' => 'string', 'enum' => ['body', 'small'], 'default' => 'body'],
                'text-role' => ['type' => 'string', 'enum' => ['primary', 'secondary', 'light'], 'default' => 'primary'],
            ],
        ],
        'link' => [
            'title' => __('GCDS Link', 'cds-snc'),
            'content' => true,
            'fields' => [
                'href' => ['type' => 'string', 'default' => '', 'required' => true],
                'target' => ['type' => 'string', 'enum' => ['_self', '_blank'], 'default' => '_self'],
            ],
        ],
        'button' => [
            'title' => __('GCDS Button link', 'cds-snc'),
            'content' => true,
            'fields' => [
                'href' => ['type' => 'string', 'default' => '', 'required' => true],
                'button-role' => ['type' => 'string', 'enum' => ['primary', 'secondary', 'start', 'danger'], 'default' => 'primary'],
                'size' => ['type' => 'string', 'enum' => ['regular', 'small'], 'default' => 'regular'],
            ],
            'fixed' => ['type' => 'link'],
        ],
        'card' => [
            'title' => __('GCDS Card', 'cds-snc'),
            'content' => true,
            'fields' => [
                'card-title' => ['type' => 'string', 'default' => '', 'required' => true],
                'href' => ['type' => 'string', 'default' => '', 'required' => true],
                'card-title-tag' => ['type' => 'string', 'enum' => ['h3', 'h4', 'h5', 'h6'], 'default' => 'h3'],
                'img-src' => ['type' => 'string', 'default' => ''],
                'img-alt' => ['type' => 'string', 'default' => ''],
                'badge' => ['type' => 'string', 'default' => ''],
            ],
        ],
        'container' => [
            'title' => __('GCDS Container', 'cds-snc'),
            'inner' => true,
            'fields' => [
                'size' => ['type' => 'string', 'enum' => ['full', 'xl', 'lg', 'md', 'sm', 'xs'], 'default' => 'full'],
                'padding' => ['type' => 'string', 'enum' => ['0', '150', '300', '400', '600'], 'default' => '0'],
                'border' => ['type' => 'boolean', 'default' => false],
            ],
        ],
        'grid' => [
            'title' => __('GCDS Grid', 'cds-snc'),
            'inner' => true,
            'allowed' => ['cds-gcds/grid-col'],
            'template' => [['cds-gcds/grid-col'], ['cds-gcds/grid-col']],
            'fields' => ['gap' => ['type' => 'string', 'enum' => ['150', '200', '300', '400', '600'], 'default' => '300']],
        ],
        'grid-col' => [
            'title' => __('GCDS Grid column', 'cds-snc'),
            'inner' => true,
            'parent' => ['cds-gcds/grid'],
            'fields' => [
                'tablet' => ['type' => 'string', 'enum' => ['1', '2', '3', '4', '5', '6'], 'default' => '3'],
                'desktop' => ['type' => 'string', 'enum' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'], 'default' => '6'],
            ],
        ],
        'details' => [
            'title' => __('GCDS Details', 'cds-snc'),
            'inner' => true,
            'fields' => [
                'details-title' => ['type' => 'string', 'default' => '', 'required' => true],
                'open' => ['type' => 'boolean', 'default' => false],
            ],
        ],
        'notice' => [
            'title' => __('GCDS Notice', 'cds-snc'),
            'inner' => true,
            'fields' => [
                'notice-title' => ['type' => 'string', 'default' => '', 'required' => true],
                'notice-role' => ['type' => 'string', 'enum' => ['info', 'success', 'warning', 'danger'], 'default' => 'info'],
                'notice-title-tag' => ['type' => 'string', 'enum' => ['h2', 'h3', 'h4', 'h5'], 'default' => 'h2'],
            ],
        ],
        'table' => [
            'title' => __('GCDS Table', 'cds-snc'),
            'fields' => [
                'caption' => ['type' => 'string', 'default' => '', 'required' => true],
                'columns' => ['type' => 'string', 'default' => '[{"field":"name","header":"Name"}]', 'json' => true],
                'data' => ['type' => 'string', 'default' => '[{"name":""}]', 'json' => true],
                'sort' => ['type' => 'boolean', 'default' => false],
                'filter' => ['type' => 'boolean', 'default' => false],
                'pagination' => ['type' => 'boolean', 'default' => false],
            ],
        ],
        'sr-only' => [
            'title' => __('GCDS Screenreader-only text', 'cds-snc'),
            'content' => true,
            'fields' => ['tag' => ['type' => 'string', 'enum' => ['span', 'p'], 'default' => 'span']],
        ],
        'icon' => [
            'title' => __('GCDS Icon', 'cds-snc'),
            'fields' => [
                'name' => ['type' => 'string', 'enum' => ['arrow-down', 'arrow-up', 'arrow-up-down', 'checkmark-circle', 'chevron-down', 'chevron-left', 'chevron-right', 'chevron-up', 'close', 'download', 'email', 'exclamation-circle', 'external', 'filter', 'info-circle', 'phone', 'search', 'sort', 'tune', 'warning-triangle'], 'default' => 'info-circle'],
                'label' => ['type' => 'string', 'default' => ''],
            ],
        ],
        'pagination' => [
            'title' => __('GCDS Pagination', 'cds-snc'),
            'fields' => [
                'label' => ['type' => 'string', 'default' => '', 'required' => true],
                'previous-href' => ['type' => 'string', 'default' => ''],
                'previous-label' => ['type' => 'string', 'default' => ''],
                'next-href' => ['type' => 'string', 'default' => ''],
                'next-label' => ['type' => 'string', 'default' => ''],
            ],
            'fixed' => ['display' => 'simple'],
        ],
        'side-nav' => [
            'title' => __('GCDS Side navigation', 'cds-snc'),
            'inner' => true,
            'allowed' => ['cds-gcds/nav-link', 'cds-gcds/nav-group'],
            'fields' => ['label' => ['type' => 'string', 'default' => '', 'required' => true]],
        ],
        'nav-group' => [
            'title' => __('GCDS Navigation group', 'cds-snc'),
            'inner' => true,
            'allowed' => ['cds-gcds/nav-link', 'cds-gcds/nav-group'],
            'parent' => ['cds-gcds/side-nav', 'cds-gcds/nav-group'],
            'fields' => [
                'menu-label' => ['type' => 'string', 'default' => '', 'required' => true],
                'open-trigger' => ['type' => 'string', 'default' => '', 'required' => true],
                'open' => ['type' => 'boolean', 'default' => false],
            ],
        ],
        'nav-link' => [
            'title' => __('GCDS Navigation link', 'cds-snc'),
            'content' => true,
            'parent' => ['cds-gcds/side-nav', 'cds-gcds/nav-group'],
            'fields' => [
                'href' => ['type' => 'string', 'default' => '', 'required' => true],
                'current' => ['type' => 'boolean', 'default' => false],
            ],
        ],
    ];
    $labels = [
        'tag' => __('Heading or element level', 'cds-snc'),
        'size' => __('Size', 'cds-snc'),
        'text-role' => __('Text colour', 'cds-snc'),
        'href' => __('Link URL', 'cds-snc'),
        'target' => __('Link target', 'cds-snc'),
        'button-role' => __('Button style', 'cds-snc'),
        'card-title' => __('Card title', 'cds-snc'),
        'card-title-tag' => __('Card heading level', 'cds-snc'),
        'img-src' => __('Image URL', 'cds-snc'),
        'img-alt' => __('Image alternative text', 'cds-snc'),
        'badge' => __('Badge', 'cds-snc'),
        'padding' => __('Padding', 'cds-snc'),
        'border' => __('Border', 'cds-snc'),
        'gap' => __('Column gap', 'cds-snc'),
        'tablet' => __('Tablet column span', 'cds-snc'),
        'desktop' => __('Desktop column span', 'cds-snc'),
        'details-title' => __('Details title', 'cds-snc'),
        'open' => __('Initially expanded', 'cds-snc'),
        'notice-title' => __('Notice title', 'cds-snc'),
        'notice-role' => __('Notice type', 'cds-snc'),
        'notice-title-tag' => __('Notice heading level', 'cds-snc'),
        'caption' => __('Table caption', 'cds-snc'),
        'columns' => __('Columns (JSON array)', 'cds-snc'),
        'data' => __('Rows (JSON array)', 'cds-snc'),
        'sort' => __('Enable sorting', 'cds-snc'),
        'filter' => __('Enable filtering', 'cds-snc'),
        'pagination' => __('Enable pagination', 'cds-snc'),
        'name' => __('Icon', 'cds-snc'),
        'label' => __('Accessible label', 'cds-snc'),
        'previous-href' => __('Previous page URL', 'cds-snc'),
        'previous-label' => __('Previous page label', 'cds-snc'),
        'next-href' => __('Next page URL', 'cds-snc'),
        'next-label' => __('Next page label', 'cds-snc'),
        'menu-label' => __('Submenu label', 'cds-snc'),
        'open-trigger' => __('Submenu button text', 'cds-snc'),
        'current' => __('Current page', 'cds-snc'),
    ];
    foreach ($definitions as $name => $definition) {
        foreach ($definition['fields'] as $key => $field) {
            $definitions[$name]['fields'][$key]['label'] = $labels[$key];
        }
    }
    return $definitions;
}

function cds_gcds_render_component(string $name, array $attributes, string $content): string
{
    $definition = cds_gcds_block_definitions()[$name];
    $values = $definition['fixed'] ?? [];
    foreach ($definition['fields'] as $key => $field) {
        $value = $attributes[$key] ?? $field['default'];
        if ($field['type'] === 'boolean') {
            if ($value) {
                $values[$key] = 'true';
            }
            continue;
        }
        if (
            !is_string($value) || (isset($field['enum']) && !in_array($value, $field['enum'], true))
            || (!empty($field['required']) && trim($value) === '')
        ) {
            return cds_gcds_block_error($definition['title'], sprintf(__('Invalid or missing attribute: %s', 'cds-snc'), $key));
        }
        if (!empty($field['json'])) {
            $decoded = json_decode($value, true);
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded) || !array_is_list($decoded)) {
                return cds_gcds_block_error($definition['title'], sprintf(__('Invalid JSON array: %s', 'cds-snc'), $key));
            }
            foreach ($decoded as $entry) {
                if (!is_array($entry) || array_is_list($entry)) {
                    return cds_gcds_block_error($definition['title'], sprintf(__('Expected objects in JSON array: %s', 'cds-snc'), $key));
                }
            }
            if ($key === 'columns') {
                $fields = [];
                foreach ($decoded as $column) {
                    if (
                        empty($column['field']) || !is_string($column['field'])
                        || !isset($column['header']) || !is_string($column['header'])
                        || in_array($column['field'], $fields, true)
                        || isset($column['slotted']) || isset($column['managed'])
                    ) {
                        return cds_gcds_block_error($definition['title'], __('Each column needs a unique field and a header. Custom cell templates are not supported in this block.', 'cds-snc'));
                    }
                    $fields[] = $column['field'];
                }
                if (!$fields) {
                    return cds_gcds_block_error($definition['title'], __('Add at least one table column.', 'cds-snc'));
                }
            }
            $value = wp_json_encode($decoded);
        } elseif (str_contains($key, 'href') || $key === 'img-src') {
            $url = esc_url_raw($value);
            if ($value !== '' && !$url) {
                return cds_gcds_block_error($definition['title'], sprintf(__('Invalid URL: %s', 'cds-snc'), $key));
            }
            $value = $url;
        } else {
            $value = sanitize_text_field($value);
        }
        if ($value !== '') {
            $values[$key] = $value;
        }
    }
    if (!empty($definition['content'])) {
        $richText = $attributes['content'] ?? '';
        if (in_array($name, ['heading', 'link', 'button', 'nav-link', 'sr-only'], true) && trim(wp_strip_all_tags($richText)) === '') {
            return cds_gcds_block_error($definition['title'], __('Enter content', 'cds-snc'));
        }
        if ($name === 'button') {
            $content = esc_html(wp_strip_all_tags($richText));
        } elseif (in_array($name, ['link', 'nav-link', 'card'], true)) {
            $content = wp_kses($richText, ['strong' => [], 'em' => [], 'abbr' => ['title' => true], 'br' => []]);
        } else {
            $content = cds_gcds_convert_html(wp_kses_post($richText));
        }
    }
    if ($name === 'table') {
        $content = '<span slot="caption">' . esc_html($values['caption']) . '</span>';
        unset($values['caption']);
    }
    $props = '';
    foreach ($values as $key => $value) {
        $props .= ' ' . $key . "='" . esc_attr($value) . "'";
    }
    $anchor = $attributes['anchor'] ?? '';
    if ($anchor !== '') {
        $props .= ' id="' . esc_attr($anchor) . '"';
    }
    return '<gcds-' . $name . $props . '>' . $content . '</gcds-' . $name . '>';
}

function cds_gcds_block_error(string $title, string $message): string
{
    error_log("cds-gcds: $title: $message");
    return '<gcds-notice notice-role="danger" notice-title="' . esc_attr($title) . '" notice-title-tag="h2"><gcds-text>' . esc_html($message) . '</gcds-text></gcds-notice>';
}

add_action('init', function (): void {
    foreach (cds_gcds_block_definitions() as $name => $definition) {
        $attributes = [
            'content' => ['type' => 'string', 'default' => ''],
            'anchor' => ['type' => 'string', 'default' => ''],
        ];
        foreach ($definition['fields'] as $key => $field) {
            $attributes[$key] = array_intersect_key($field, array_flip(['type', 'default', 'enum']));
        }
        register_block_type('cds-gcds/' . $name, [
            'api_version' => 3,
            'title' => $definition['title'],
            'category' => 'cds-gcds',
            'attributes' => $attributes,
            'supports' => ['html' => false, 'anchor' => true, 'customClassName' => false],
            'parent' => $definition['parent'] ?? null,
            'render_callback' => static fn($attributes, $content) => cds_gcds_render_component($name, $attributes, $content),
        ]);
    }
});

add_filter('block_categories_all', function ($categories) {
    $categories[] = ['slug' => 'cds-gcds', 'title' => __('GC Design System', 'cds-snc')];
    return $categories;
});

add_action('enqueue_block_editor_assets', function (): void {
    wp_enqueue_script('cds-gcds-editor', get_template_directory_uri() . '/js/blocks.js', ['wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'wp-hooks', 'wp-server-side-render'], _S_VERSION, true);
    wp_add_inline_script('cds-gcds-editor', 'window.cdsGcdsBlocks = ' . wp_json_encode(cds_gcds_block_definitions()) . ';', 'before');
    wp_set_script_translations('cds-gcds-editor', 'cds-snc', get_template_directory() . '/languages');
});
