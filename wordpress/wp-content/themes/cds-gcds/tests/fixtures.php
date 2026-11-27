<?php
declare(strict_types=1);

if (!defined('WP_CLI') || !WP_CLI || wp_parse_url(home_url(), PHP_URL_HOST) !== 'localhost'
    || wp_parse_url(home_url(), PHP_URL_PORT) !== 8889 || get_stylesheet() !== 'cds-gcds') {
    throw new RuntimeException('These fixtures may only be installed in a local cds-gcds test site.');
}

wp_set_current_user(1);
$block = static function (string $name, array $attributes = [], ?string $body = null): string {
    return get_comment_delimited_block_content('cds-gcds/' . $name, $attributes, $body ?? '');
};

$english = '';
$french = '';
foreach (cds_gcds_block_definitions() as $name => $definition) {
    if (!empty($definition['parent'])) {
        continue;
    }
    $attributes = [];
    foreach ($definition['fields'] as $key => $field) {
        $attributes[$key] = $field['default'];
        if (!empty($field['required'])) {
            $attributes[$key] = str_contains($key, 'href') ? home_url('/cds-gcds-test-en/') : "Sample $name";
        }
    }
    $attributes['content'] = "Sample $name";
    if ($name === 'heading') {
        $attributes['anchor'] = 'sample-section';
    }
    if ($name === 'pagination') {
        $attributes['previous-href'] = home_url('/cds-gcds-test-en/');
        $attributes['next-href'] = home_url('/cds-gcds-test-fr/');
    }
    $body = null;
    if (!empty($definition['inner'])) {
        $body = $block('text', ['content' => "Nested $name content"]);
    }
    if ($name === 'grid') {
        $body = $block('grid-col', [], $block('text', ['content' => 'First column']))
            . $block('grid-col', [], $block('text', ['content' => 'Second column']));
    } elseif ($name === 'side-nav') {
        $body = $block('nav-link', ['href' => '#sample-section', 'content' => 'Section'])
            . $block('nav-group', ['menu-label' => 'Related pages', 'open-trigger' => 'Related pages'], $block('nav-link', ['href' => home_url('/cds-gcds-test-fr/'), 'content' => 'French page']));
    }
    $english .= $block($name, $attributes, $body);
    $attributes['content'] = "Exemple $name";
    $french .= $block($name, $attributes, $body);
}

$legacy = '<!-- wp:heading --><h2>Legacy blocks</h2><!-- /wp:heading -->
<!-- wp:cds-snc/accordion --><details class="wp-block-cds-snc-accordion"><summary>Legacy accordion</summary><!-- wp:paragraph --><p>Accordion content</p><!-- /wp:paragraph --></details><!-- /wp:cds-snc/accordion -->
<!-- wp:cds-snc/expander --><details class="wp-block-cds-snc-expander"><summary><h3>Legacy expander</h3></summary><div class="wp-block-cds-snc-expander">Expander content</div></details><!-- /wp:cds-snc/expander -->
<!-- wp:cds-snc/alert {"alertType":"alert-warning"} --><section class="wp-block-cds-snc-alert"><details class="alert alert-warning" open><summary class="h3"><h3>Legacy warning</h3></summary><p class="wp-block-cds-snc-alert">Alert content</p></details></section><!-- /wp:cds-snc/alert -->
<!-- wp:table {"className":"is-style-filterable"} --><figure class="wp-block-table is-style-filterable"><table><thead><tr><th>Name</th><th>Value</th></tr></thead><tbody><tr><td>Alpha</td><td>1</td></tr><tr><td>Beta</td><td>2</td></tr></tbody></table><figcaption>Legacy table</figcaption></figure><!-- /wp:table -->
<!-- wp:heading --><h2>Latest posts</h2><!-- /wp:heading -->
<!-- wp:latest-posts {"postsToShow":2,"displayPostContent":true,"displayAuthor":true,"displayPostDate":true} /-->';
$rows = '';
foreach (range(1, 12) as $number) {
    $value = [1 => '1.5', 2 => '1.25'][$number] ?? (string) $number;
    $rows .= '<tr><td><a href="#sample-section">Entry ' . $number . '</a></td><td>' . $value . '</td></tr>';
}
$legacy .= '<!-- wp:heading --><h2>Rich table</h2><!-- /wp:heading -->'
    . '<!-- wp:table {"className":"is-style-filterable"} --><figure class="wp-block-table is-style-filterable"><table><thead><tr><th>Name</th><th>Value</th></tr></thead><tbody>' . $rows . '</tbody></table><figcaption>Rich table</figcaption></figure><!-- /wp:table -->';
$legacy .= '<!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:paragraph --><p>Left layout</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:paragraph --><p>Right layout</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns -->';
$legacy .= '<!-- wp:heading --><h2>Media and text blocks</h2><!-- /wp:heading -->
<!-- wp:list --><ul class="wp-block-list"><!-- wp:list-item --><li>List item<ul class="wp-block-list"><!-- wp:list-item --><li>Nested item</li><!-- /wp:list-item --></ul></li><!-- /wp:list-item --></ul><!-- /wp:list -->
<!-- wp:list {"ordered":true} --><ol class="wp-block-list"><!-- wp:list-item --><li>Ordered item</li><!-- /wp:list-item --></ol><!-- /wp:list -->
<!-- wp:image --><figure class="wp-block-image"><img src="' . includes_url('images/w-logo-blue.png') . '" alt="WordPress logo" /><figcaption class="wp-element-caption">Image caption</figcaption></figure><!-- /wp:image -->
<!-- wp:quote --><blockquote class="wp-block-quote"><!-- wp:paragraph --><p>Quoted text</p><!-- /wp:paragraph --><cite>Citation</cite></blockquote><!-- /wp:quote -->
<!-- wp:code --><pre class="wp-block-code"><code>echo "code";</code></pre><!-- /wp:code -->
<!-- wp:spacer {"height":"48px"} --><div style="height:48px" aria-hidden="true" class="wp-block-spacer"></div><!-- /wp:spacer -->
<!-- wp:separator --><hr class="wp-block-separator has-alpha-channel-opacity" /><!-- /wp:separator -->
<!-- wp:file --><div class="wp-block-file"><a id="fixture-file" href="' . includes_url('images/w-logo-blue.png') . '">Logo file</a><a href="' . includes_url('images/w-logo-blue.png') . '" class="wp-block-file__button wp-element-button" download aria-describedby="fixture-file">Download</a></div><!-- /wp:file -->';

$ids = [];
foreach (['en' => $english, 'fr' => $french] as $language => $content) {
    $slug = "cds-gcds-test-$language";
    $existing = get_page_by_path($slug);
    $id = wp_insert_post(wp_slash([
        'ID' => $existing ? $existing->ID : 0,
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_name' => $slug,
        'post_title' => $language === 'en' ? 'GCDS migration test' : 'Test de migration SGDC',
        'post_content' => $content . $legacy,
    ]), true);
    if (is_wp_error($id)) {
        throw new RuntimeException($id->get_error_message());
    }
    if (get_post($id)->post_content !== $content . $legacy) {
        throw new RuntimeException('Fixture content changed during saving.');
    }
    $ids[$language] = $id;
    update_post_meta($id, 'locale', $language === 'fr' ? 'fr_CA' : 'en_US');
}
foreach ($ids as $language => $id) {
    $other = $language === 'en' ? 'fr' : 'en';
    update_post_meta($id, 'locale_switch_link', wp_json_encode([
        'active' => false,
        'translated_name' => $other === 'fr' ? 'French' : 'English',
        'url' => get_permalink($ids[$other]),
    ]));
    echo get_permalink($id) . "\n";
}
