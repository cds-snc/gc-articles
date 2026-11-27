<?php

declare(strict_types=1);

function cds_gcds_dom(string $html): ?DOMDocument
{
    $dom = new DOMDocument('1.0', 'UTF-8');
    $previous = libxml_use_internal_errors(true);
    $loaded = $dom->loadHTML(
        '<?xml encoding="UTF-8"><html><body><div id="cds-gcds-fragment">' . $html . '</div></body></html>',
        LIBXML_NONET | LIBXML_HTML_NODEFDTD
    );
    $errors = libxml_get_errors();
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
    if (!$loaded) {
        error_log('cds-gcds: could not parse block markup: ' . implode('; ', array_map(static fn($error) => trim($error->message), $errors)));
        return null;
    }
    return $dom;
}

function cds_gcds_inner_html(DOMNode $node): string
{
    $html = '';
    foreach ($node->childNodes as $child) {
        $html .= $node->ownerDocument->saveHTML($child);
    }
    return $html;
}

function cds_gcds_replace(DOMElement $node, string $tag, array $attributes = []): DOMElement
{
    $replacement = $node->ownerDocument->createElement($tag);
    foreach ($node->attributes as $attribute) {
        if (
            in_array($attribute->name, ['id', 'class', 'lang', 'dir', 'slot', 'title', 'style', 'tabindex'], true)
            || str_starts_with($attribute->name, 'aria-')
            || str_starts_with($attribute->name, 'data-')
        ) {
            $replacement->setAttribute($attribute->name, $attribute->value);
        }
    }
    foreach ($attributes as $name => $value) {
        $replacement->setAttribute($name, (string) $value);
    }
    while ($node->firstChild) {
        $replacement->appendChild($node->firstChild);
    }
    $node->parentNode->replaceChild($replacement, $node);
    return $replacement;
}

function cds_gcds_convert_node(DOMElement $node): void
{
    if ($node->tagName === 'pre') {
        cds_gcds_add_class($node, 'font-family-monospace', 'bg-light', 'p-300', 'mb-400');
        return;
    }
    if (str_starts_with($node->tagName, 'gcds-') || in_array($node->tagName, ['script', 'style', 'code', 'svg', 'form'], true)) {
        return;
    }
    $classes = $node->getAttribute('class');
    if (preg_match('/\b(screen-reader-text|sr-only|wb-inv|wb-sl)\b/', $classes)) {
        $hidden = $node->ownerDocument->createElement('gcds-sr-only');
        $hidden->setAttribute('tag', 'span');
        $node->parentNode->replaceChild($hidden, $node);
        $hidden->appendChild($node);
    }
    if ($node->tagName !== 'details' && preg_match('/\balert-(info|success|warning|danger)\b/', $classes, $match)) {
        $heading = null;
        foreach ($node->childNodes as $child) {
            if ($child instanceof DOMElement && preg_match('/^h[2-5]$/', $child->tagName)) {
                $heading = $child;
                break;
            }
        }
        if ($heading) {
            $attributes = [
                'notice-role' => $match[1],
                'notice-title' => trim($heading->textContent),
                'notice-title-tag' => $heading->tagName,
            ];
            $node->removeChild($heading);
            $node = cds_gcds_replace($node, 'gcds-notice', $attributes);
        }
    }
    if ($node->tagName === 'details') {
        $summary = $node->getElementsByTagName('summary')->item(0);
        if ($summary) {
            $title = trim($summary->textContent);
            $heading = null;
            foreach ($summary->childNodes as $child) {
                if ($child instanceof DOMElement && preg_match('/^h[2-5]$/', $child->tagName)) {
                    $heading = $child;
                    break;
                }
            }
            $summary->parentNode->removeChild($summary);
            if (preg_match('/\balert-(info|success|warning|danger)\b/', $classes, $match)) {
                $node = cds_gcds_replace($node, 'gcds-notice', [
                    'notice-role' => $match[1],
                    'notice-title' => $title,
                    'notice-title-tag' => $heading ? $heading->tagName : 'h2',
                ]);
            } else {
                $attributes = ['details-title' => $title];
                if ($node->hasAttribute('open')) {
                    $attributes['open'] = 'true';
                }
                $node = cds_gcds_replace($node, 'gcds-details', $attributes);
            }
        }
    }
    foreach (iterator_to_array($node->childNodes) as $child) {
        if ($child instanceof DOMElement) {
            cds_gcds_convert_node($child);
        }
    }
    $classes = $node->getAttribute('class');
    if ($node->tagName === 'p' || preg_match('/^h[1-6]$/', $node->tagName)) {
        $heading = $node->tagName !== 'p';
        $attrs = $heading ? ['tag' => $node->tagName] : [];
        if (preg_match('/\bhas-(white|light-blue)-color\b/', $classes)) {
            $attrs[$heading ? 'heading-role' : 'text-role'] = 'light';
        }
        $node = cds_gcds_replace($node, $heading ? 'gcds-heading' : 'gcds-text', $attrs);
        $node->removeAttribute('style');
    } elseif ($node->tagName === 'a' && $node->hasAttribute('href')) {
        $attrs = [];
        foreach (['href', 'target', 'rel', 'download', 'type'] as $name) {
            if ($node->hasAttribute($name)) {
                $attrs[$name] = $node->getAttribute($name);
            }
        }
        if (preg_match('/\b(?:wp-block-button__link|wp-block-file__button|btn)\b/', $classes)) {
            $attrs['type'] = 'link';
            $parentClasses = $node->parentNode instanceof DOMElement ? $node->parentNode->getAttribute('class') : '';
            $attrs['button-role'] = str_contains($classes . ' ' . $parentClasses, 'is-style-outline') || str_contains($classes, 'btn-default') ? 'secondary' : 'primary';
            $button = cds_gcds_replace($node, 'gcds-button', $attrs);
            $button->removeAttribute('style');
            $button->removeAttribute('class');
        } else {
            cds_gcds_replace($node, 'gcds-link', $attrs);
        }
    } elseif ($node->tagName === 'ul' && preg_match('/\btoc\b/', $classes)) {
        cds_gcds_replace($node, 'gcds-grid', ['tag' => 'ul', 'columns' => '1fr', 'columns-tablet' => '1fr 1fr', 'columns-desktop' => '1fr 1fr 1fr']);
    } elseif (in_array($node->tagName, ['ul', 'ol'], true)) {
        cds_gcds_convert_list($node);
    } elseif ($node->tagName === 'img') {
        cds_gcds_add_class($node, 'img-responsive');
    } elseif ($node->tagName === 'figcaption' || ($node->tagName === 'cite' && in_array($node->parentNode->nodeName, ['blockquote', 'figure'], true))) {
        cds_gcds_convert_caption($node);
    } elseif ($node->tagName === 'blockquote') {
        cds_gcds_add_class($node, 'ps-300', 'mb-400');
    } elseif ($node->tagName === 'hr') {
        cds_gcds_add_class($node, 'my-400');
    } elseif (preg_match('/\bwp-block-spacer\b/', $classes)) {
        cds_gcds_convert_spacer($node);
    }
}

function cds_gcds_add_class(DOMElement $node, string ...$names): void
{
    $classes = preg_split('/\s+/', trim($node->getAttribute('class')), -1, PREG_SPLIT_NO_EMPTY);
    $node->setAttribute('class', implode(' ', array_unique(array_merge($classes, $names))));
}

function cds_gcds_convert_list(DOMElement $node): void
{
    $classes = $node->getAttribute('class');
    if (preg_match('/\blist-unstyled\b/', $classes)) {
        cds_gcds_add_class($node, 'list-none');
        return;
    }
    // Only style authored content lists; block-generated lists (latest posts, archives, galleries, menus) keep their layout.
    if (preg_match('/\b(?:list-[a-z-]+|wp-block-(?!list\b)[a-z-]+|blocks-gallery-grid|menu|nav)\b/', $classes)) {
        return;
    }
    if ($node->tagName === 'ol') {
        $types = ['a' => 'list-lower-alpha', 'A' => 'list-upper-alpha', 'i' => 'list-lower-roman', 'I' => 'list-upper-roman'];
        $style = $types[$node->getAttribute('type')] ?? 'list-decimal';
    } else {
        $style = $node->parentNode && $node->parentNode->nodeName === 'li' ? 'list-circle' : 'list-disc';
    }
    cds_gcds_add_class($node, $style);
}

function cds_gcds_convert_caption(DOMElement $node): void
{
    // Table captions are converted with their table; wrapping them here would hide simple tables from gcds-table.
    $figure = $node->parentNode;
    if ($figure instanceof DOMElement && preg_match('/\bwp-block-table\b/', $figure->getAttribute('class'))) {
        return;
    }
    foreach ($node->childNodes as $child) {
        if ($child instanceof DOMElement && $child->tagName === 'gcds-text') {
            return;
        }
    }
    if ($node->parentNode->nodeName === 'gcds-text') {
        return;
    }
    $text = $node->ownerDocument->createElement('gcds-text');
    $text->setAttribute('size', 'small');
    $text->setAttribute('text-role', 'secondary');
    $text->setAttribute('margin-top', '100');
    $text->setAttribute('margin-bottom', '0');
    if ($node->tagName === 'cite') {
        $node->parentNode->replaceChild($text, $node);
        $text->appendChild($node);
        return;
    }
    while ($node->firstChild) {
        $text->appendChild($node->firstChild);
    }
    $node->appendChild($text);
}

function cds_gcds_convert_spacer(DOMElement $node): void
{
    if (!preg_match('/(?:^|;)\s*height\s*:\s*(\d+(?:\.\d+)?)(px|rem|em)\s*(?:;|$)/', $node->getAttribute('style'), $match)) {
        return;
    }
    $rem = $match[2] === 'px' ? (float) $match[1] / 16 : (float) $match[1];
    $tokens = array_merge(range(0, 250, 25), range(300, 1250, 50));
    if ($rem > 6.25) {
        return;
    }
    $nearest = 0;
    foreach ($tokens as $token) {
        if (abs($token / 200 - $rem) < abs($nearest / 200 - $rem)) {
            $nearest = $token;
        }
    }
    $style = trim(preg_replace('/(?:^|;)\s*height\s*:[^;]*;?/', ';', $node->getAttribute('style')), " ;");
    $style === '' ? $node->removeAttribute('style') : $node->setAttribute('style', $style);
    cds_gcds_add_class($node, 'pt-' . $nearest);
}

function cds_gcds_convert_html(string $html): string
{
    if ($html === '' || !str_contains($html, '<')) {
        return $html;
    }
    $dom = cds_gcds_dom($html);
    if (!$dom) {
        return $html;
    }
    $root = $dom->getElementById('cds-gcds-fragment');
    foreach (iterator_to_array($root->childNodes) as $child) {
        if ($child instanceof DOMElement) {
            cds_gcds_convert_node($child);
        }
    }
    return cds_gcds_inner_html($root);
}

function cds_gcds_convert_table(DOMElement $figure): void
{
    $table = $figure->getElementsByTagName('table')->item(0);
    if (!$table) {
        return;
    }
    $rows = iterator_to_array($table->getElementsByTagName('tr'));
    $headers = $rows ? iterator_to_array($rows[0]->childNodes) : [];
    $headers = array_values(array_filter($headers, static fn($cell) => $cell instanceof DOMElement));
    $complex = !$headers || count($table->getElementsByTagName('tfoot')) > 0;
    foreach ($table->getElementsByTagName('*') as $element) {
        if (
            $element->hasAttribute('rowspan') || $element->hasAttribute('colspan')
            || in_array($element->tagName, ['a', 'img', 'gcds-link', 'strong', 'em', 'br'], true)
        ) {
            $complex = true;
        }
    }
    foreach ($headers as $header) {
        if ($header->tagName !== 'th') {
            $complex = true;
        }
    }
    foreach ($figure->getElementsByTagName('*') as $element) {
        if (in_array($element->tagName, ['th', 'td', 'caption', 'figcaption'], true)) {
            foreach ($element->childNodes as $child) {
                if ($child instanceof DOMElement) {
                    $complex = true;
                }
            }
        }
    }
    if ($complex) {
        cds_gcds_native_table($figure);
        return;
    }
    $columns = [];
    foreach ($headers as $index => $header) {
        $columns[] = ['field' => "column$index", 'header' => trim($header->textContent)];
    }
    $data = [];
    foreach (array_slice($rows, 1) as $row) {
        $cells = array_values(array_filter(iterator_to_array($row->childNodes), static fn($cell) => $cell instanceof DOMElement));
        if (count($cells) !== count($headers) || array_filter($cells, static fn($cell) => $cell->tagName !== 'td')) {
            cds_gcds_native_table($figure);
            return;
        }
        $values = [];
        foreach ($cells as $index => $cell) {
            $values["column$index"] = trim($cell->textContent);
        }
        $data[] = $values;
    }
    $caption = $figure->getElementsByTagName('figcaption')->item(0) ?: $table->getElementsByTagName('caption')->item(0);
    $replacement = $figure->ownerDocument->createElement('gcds-table');
    $replacement->setAttribute('columns', wp_json_encode($columns));
    $replacement->setAttribute('data', wp_json_encode($data));
    if (str_contains($figure->getAttribute('class'), 'is-style-filterable')) {
        $replacement->setAttribute('filter', 'true');
        $replacement->setAttribute('sort', 'true');
        $replacement->setAttribute('pagination', 'true');
    }
    $label = $figure->ownerDocument->createElement('span');
    $label->setAttribute('slot', 'caption');
    $label->appendChild($figure->ownerDocument->createTextNode($caption ? $caption->textContent : __('Table', 'cds-snc')));
    $replacement->appendChild($label);
    if ($figure->hasAttribute('id')) {
        $replacement->setAttribute('id', $figure->getAttribute('id'));
    }
    $figure->parentNode->replaceChild($replacement, $figure);
}

function cds_gcds_native_table(DOMElement $figure): void
{
    $filterable = str_contains($figure->getAttribute('class'), 'is-style-filterable');
    $wrapper = cds_gcds_replace($figure, 'gcds-container', ['class' => 'cds-gcds-native-table', 'layout' => 'full']);
    $table = $wrapper->getElementsByTagName('table')->item(0);
    if (!$table->getElementsByTagName('caption')->length) {
        $figureCaption = $wrapper->getElementsByTagName('figcaption')->item(0);
        $caption = $table->ownerDocument->createElement('caption');
        if ($figureCaption) {
            if ($figureCaption->hasAttribute('id')) {
                $caption->setAttribute('id', $figureCaption->getAttribute('id'));
            }
            while ($figureCaption->firstChild) {
                $caption->appendChild($figureCaption->firstChild);
            }
            $figureCaption->parentNode->removeChild($figureCaption);
        } else {
            $caption->appendChild($table->ownerDocument->createTextNode(__('Table', 'cds-snc')));
        }
        $table->insertBefore($caption, $table->firstChild);
    }
    if (!$filterable) {
        return;
    }
    $headerRows = $table->getElementsByTagName('thead')->item(0)?->getElementsByTagName('tr');
    $headers = $headerRows?->item(0);
    $supported = $headers && $headerRows->length === 1
        && $table->getElementsByTagName('tbody')->length === 1
        && !$table->getElementsByTagName('table')->length;
    foreach ($table->getElementsByTagName('tr') as $row) {
        $cells = array_filter(iterator_to_array($row->childNodes), static fn($cell) => $cell instanceof DOMElement);
        if (!$headers || count($cells) !== $headers->getElementsByTagName('th')->length) {
            $supported = false;
        }
        foreach ($cells as $cell) {
            if ($cell->hasAttribute('rowspan') || $cell->hasAttribute('colspan')) {
                $supported = false;
            }
        }
    }
    if (!$supported) {
        $message = __('Filtering and sorting require a single header row and body with no merged cells. All table content is shown.', 'cds-snc');
        error_log('cds-gcds: ' . $message);
        $controls = '<gcds-text size="small">' . esc_html($message) . '</gcds-text>';
    } else {
        $wrapper->setAttribute('data-cds-table', 'true');
        $id = wp_unique_id('table-filter-');
        $controls = '<form class="cds-gcds-table-controls" aria-label="' . esc_attr__('Table controls', 'cds-snc') . '">'
            . '<gcds-input input-id="' . $id . '" name="filter" type="search" label="' . esc_attr__('Filter table', 'cds-snc') . '"></gcds-input>'
            . '<gcds-select select-id="' . $id . '-size" name="size" label="' . esc_attr__('Rows per page', 'cds-snc') . '" value="10">'
            . '<option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="0">' . esc_html__('All rows', 'cds-snc') . '</option></gcds-select>'
            . '<gcds-button data-previous type="button" button-role="secondary" size="small">' . esc_html__('Previous page', 'cds-snc') . '</gcds-button>'
            . '<gcds-button data-next type="button" button-role="secondary" size="small">' . esc_html__('Next page', 'cds-snc') . '</gcds-button>'
            . '<gcds-text data-status role="status" aria-live="polite" data-label="' . esc_attr__('Rows', 'cds-snc') . '"></gcds-text></form>';
        foreach ($headers->getElementsByTagName('th') as $index => $header) {
            $button = $header->ownerDocument->createElement('gcds-button');
            $button->setAttribute('type', 'button');
            $button->setAttribute('button-role', 'secondary');
            $button->setAttribute('size', 'small');
            $button->setAttribute('data-sort-column', (string) $index);
            $button->appendChild($header->ownerDocument->createTextNode(sprintf(__('Sort by %s', 'cds-snc'), trim($header->textContent))));
            $header->appendChild($button);
        }
    }
    $dom = cds_gcds_dom($controls);
    foreach (iterator_to_array($dom->getElementById('cds-gcds-fragment')->childNodes) as $control) {
        $wrapper->insertBefore($wrapper->ownerDocument->importNode($control, true), $wrapper->firstChild);
    }
}

function cds_gcds_render_legacy_block(string $html, array $block): string
{
    $name = $block['blockName'] ?? '';
    if (str_starts_with($name, 'cds-gcds/')) {
        return $html;
    }
    $html = cds_gcds_convert_html($html);
    $layouts = ['core/columns', 'core/column', 'core/group', 'core/cover', 'core/media-text', 'core/gallery', 'core/latest-posts', 'core/buttons', 'core/table', 'cds/site-counter'];
    if (!in_array($name, $layouts, true) || $html === '') {
        return $html;
    }
    $dom = cds_gcds_dom($html);
    if (!$dom) {
        return $html;
    }
    $root = $dom->getElementById('cds-gcds-fragment');
    $node = null;
    foreach ($root->childNodes as $child) {
        if ($child instanceof DOMElement) {
            $node = $child;
            break;
        }
    }
    if (!$node) {
        return $html;
    }
    if ($name === 'core/table') {
        cds_gcds_convert_table($node);
    } elseif (in_array($name, ['core/columns', 'core/gallery', 'core/latest-posts', 'core/media-text'], true)) {
        $attrs = ['columns' => '1fr'];
        $count = count($block['innerBlocks'] ?? []);
        if ($name === 'core/latest-posts') {
            $attrs['tag'] = 'ul';
            $count = ($block['attrs']['postLayout'] ?? 'list') === 'grid'
                ? (int) ($block['attrs']['columns'] ?? 3) : 1;
        }
        if ($name === 'core/gallery') {
            $count = (int) ($block['attrs']['columns'] ?? 3);
        }
        if ($name === 'core/columns') {
            $widths = array_map(static function ($column) {
                $width = $column['attrs']['width'] ?? '';
                return preg_match('/^(\d+(?:\.\d+)?)%$/', $width, $match) ? $match[1] . 'fr' : '1fr';
            }, $block['innerBlocks'] ?? []);
            $attrs['columns-tablet'] = $widths ? implode(' ', $widths) : '1fr';
        } elseif ($name === 'core/media-text') {
            $width = max(1, min(99, (int) ($block['attrs']['mediaWidth'] ?? 50)));
            $attrs['columns-tablet'] = "$width" . 'fr ' . (100 - $width) . 'fr';
            if (($block['attrs']['mediaPosition'] ?? '') === 'right') {
                $attrs['columns-tablet'] = (100 - $width) . "fr {$width}fr";
                $media = $node->firstChild;
                while ($media && !($media instanceof DOMElement)) {
                    $media = $media->nextSibling;
                }
                if ($media) {
                    $node->appendChild($media);
                }
            }
        } else {
            $attrs['columns-tablet'] = 'repeat(' . max(1, $count) . ', minmax(0, 1fr))';
        }
        $grid = cds_gcds_replace($node, 'gcds-grid', $attrs);
        $grid->removeAttribute('style');
        $grid->setAttribute('class', trim(preg_replace('/\b(?:wp-block-(?:columns|gallery|latest-posts|media-text)|is-layout-(?:flex|grid))\b/', '', $grid->getAttribute('class'))));
    } elseif ($name === 'cds/site-counter') {
        cds_gcds_replace($node, 'gcds-text');
    } elseif ($name === 'core/buttons') {
        $node->setAttribute('class', 'd-flex flex-wrap gap-200');
    } elseif ($name === 'core/cover' || ($name === 'core/group' && ($block['attrs']['layout']['type'] ?? '') === 'flex')) {
        $container = $dom->createElement('gcds-container');
        $container->setAttribute('layout', 'full');
        $node->parentNode->replaceChild($container, $node);
        $container->appendChild($node);
    } else {
        cds_gcds_replace($node, 'gcds-container', ['layout' => 'full']);
    }
    return cds_gcds_inner_html($root);
}

add_filter('render_block', 'cds_gcds_render_legacy_block', 100, 2);
