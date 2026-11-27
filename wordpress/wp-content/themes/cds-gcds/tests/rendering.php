<?php
declare(strict_types=1);

if (!function_exists('do_blocks') || get_stylesheet() !== 'cds-gcds') {
    throw new RuntimeException('Run with wp eval requiring this file after activating cds-gcds in a test environment.');
}

$checks = 0;
$assert = static function (bool $condition, string $message) use (&$checks): void {
    ++$checks;
    if (!$condition) {
        throw new RuntimeException($message);
    }
};

$paragraph = '<!-- wp:paragraph --><p id="example">L’information <a href="/example?x=1&amp;y=2" target="_blank">Example</a></p><!-- /wp:paragraph -->';
$rendered = do_blocks($paragraph);
$assert(str_contains($rendered, '<gcds-text id="example">'), 'Paragraph must become GCDS text and preserve its anchor.');
$assert(str_contains($rendered, 'L’information'), 'UTF-8 content must survive conversion.');
$assert(str_contains($rendered, 'href="/example?x=1&amp;y=2" target="_blank"'), 'Link URL, query string and target must survive conversion.');
$assert(cds_gcds_convert_html($rendered) === $rendered, 'Conversion must be idempotent.');
$assert(parse_blocks($paragraph)[0]['innerHTML'] === '<p id="example">L’information <a href="/example?x=1&amp;y=2" target="_blank">Example</a></p>', 'Rendering must not change saved block markup.');
$hidden = cds_gcds_convert_html('<p class="sr-only">Hidden paragraph</p><a class="wb-inv" href="/hidden">Hidden link</a>');
$assert(substr_count($hidden, '<gcds-sr-only') === 2 && str_contains($hidden, '<gcds-link'), 'Hidden text and hidden links must remain hidden while preserving their semantics.');
$assert(cds_gcds_convert_html($hidden) === $hidden, 'Hidden content conversion must remain idempotent.');

$accordion = '<!-- wp:cds-snc/accordion --><details><summary>More information</summary><!-- wp:paragraph --><p>Nested body</p><!-- /wp:paragraph --></details><!-- /wp:cds-snc/accordion -->';
$rendered = do_blocks($accordion);
$assert(str_contains($rendered, '<gcds-details details-title="More information">'), 'Accordion must retain its title and collapsed initial state.');
$assert(str_contains($rendered, '<gcds-text>Nested body</gcds-text>'), 'Nested accordion blocks must convert.');

$alert = '<!-- wp:cds-snc/alert {"alertType":"alert-warning"} --><section><details class="alert alert-warning" open><summary><h3>Warning</h3></summary><p>Body</p></details></section><!-- /wp:cds-snc/alert -->';
$rendered = do_blocks($alert);
$assert(!str_contains($rendered, 'gcds-details'), 'Alerts must not be wrapped in a collapsible details element.');
$assert(str_contains($rendered, 'notice-role="warning" notice-title="Warning" notice-title-tag="h3"'), 'Alert severity, wording and heading level must survive conversion.');
$assert(str_contains($rendered, '<gcds-text>Body</gcds-text>'), 'Alert body must convert.');
$assert(substr_count($rendered, '>Body<') === 1, 'Alert body must not be lost or duplicated.');

$button = '<!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="/start">Start</a></div><!-- /wp:button -->';
$assert(str_contains(do_blocks($button), 'button-role="secondary"'), 'Outlined buttons must become secondary GCDS buttons.');

$columns = '<!-- wp:columns --><div class="wp-block-columns"><!-- wp:column {"width":"25%"} --><div class="wp-block-column"><!-- wp:paragraph --><p>Left</p><!-- /wp:paragraph --></div><!-- /wp:column --><!-- wp:column {"width":"75%"} --><div class="wp-block-column"><!-- wp:paragraph --><p>Right</p><!-- /wp:paragraph --></div><!-- /wp:column --></div><!-- /wp:columns -->';
$assert(str_contains(do_blocks($columns), 'columns-tablet="25fr 75fr"'), 'Legacy column proportions must survive conversion.');
$dom = cds_gcds_dom(do_blocks($columns));
$assert(!str_contains($dom->getElementsByTagName('gcds-grid')->item(0)->getAttribute('class'), 'wp-block-columns'), 'WordPress flex layout classes must not override the GCDS grid host.');
$cover = cds_gcds_render_legacy_block('<div class="wp-block-cover"><img class="wp-block-cover__image-background" src="/image.png" alt=""><div><p>Cover text</p></div></div>', ['blockName' => 'core/cover']);
$assert(str_contains($cover, '<gcds-container layout="full"><div class="wp-block-cover">'), 'Cover media and overlay positioning must retain their native WordPress wrapper.');
$row = cds_gcds_render_legacy_block('<div class="wp-block-group is-layout-flex"><p>Row content</p></div>', ['blockName' => 'core/group', 'attrs' => ['layout' => ['type' => 'flex']]]);
$assert(str_contains($row, '<gcds-container layout="full"><div class="wp-block-group is-layout-flex">'), 'Row and stack groups must retain their flex behaviour around converted content.');

$table = '<!-- wp:table {"className":"is-style-filterable"} --><figure class="wp-block-table is-style-filterable"><table><thead><tr><th>Name</th><th>Value</th></tr></thead><tbody><tr><td>Example</td><td>2</td></tr></tbody></table><figcaption>Sample data</figcaption></figure><!-- /wp:table -->';
$rendered = do_blocks($table);
$dom = cds_gcds_dom($rendered);
$gcdsTable = $dom->getElementsByTagName('gcds-table')->item(0);
$assert($gcdsTable !== null, 'Simple tables must use GCDS table.');
$assert(json_decode($gcdsTable->getAttribute('data'), true) === [['column0' => 'Example', 'column1' => '2']], 'Table rows must preserve exact cell values.');
$assert($gcdsTable->getAttribute('filter') === 'true', 'Filterable tables must retain filtering.');
$assert(str_contains($rendered, 'slot="caption">Sample data'), 'Table captions must survive conversion.');

$richTable = str_replace('<td>Example</td>', '<td><a href="/example">Example</a></td>', $table);
$rendered = do_blocks($richTable);
$assert(str_contains($rendered, '<gcds-container') && str_contains($rendered, '<table'), 'Rich tables must retain native table semantics in a GCDS container.');
$assert(str_contains($rendered, '<gcds-link href="/example">Example</gcds-link>'), 'Rich table links must remain usable.');
$assert(str_contains($rendered, 'data-cds-table="true"') && str_contains($rendered, '<gcds-input'), 'Filterable rich tables must retain filtering controls without WET.');
$assert(str_contains($rendered, '<caption>Sample data</caption>'), 'Native table fallback must retain an accessible caption.');
$rendered = do_blocks(str_replace('<td>Example</td>', '<td colspan="2">Example</td>', $table));
$assert(!str_contains($rendered, 'data-cds-table') && str_contains($rendered, 'All table content is shown.'), 'Merged tables must keep all content and explicitly explain unavailable controls.');
$rendered = do_blocks(str_replace('<td>Example</td>', '<th scope="row">Example</th>', $table));
$assert(str_contains($rendered, '<th scope="row">Example</th>'), 'Table row headers must not become ordinary data cells.');
$rendered = do_blocks(str_replace('<td>Example</td>', '<td><span title="Explanation">Example</span></td>', $table));
$assert(str_contains($rendered, '<span title="Explanation">Example</span>'), 'Rich table cell attributes and structure must survive conversion.');

$rendered = cds_gcds_render_legacy_block('<ul class="wp-block-latest-posts is-grid"><li><a href="/example">Example</a></li></ul>', ['blockName' => 'core/latest-posts', 'attrs' => ['postLayout' => 'grid', 'columns' => 4]]);
$assert(str_contains($rendered, 'columns-tablet="repeat(4, minmax(0, 1fr))"'), 'Latest posts must honour the actual core grid layout attribute.');
$rendered = cds_gcds_render_legacy_block('<ul class="wp-block-latest-posts"><li>Example</li></ul>', ['blockName' => 'core/latest-posts', 'attrs' => ['postLayout' => 'list', 'columns' => 4]]);
$assert(str_contains($rendered, 'columns-tablet="repeat(1, minmax(0, 1fr))"'), 'Latest posts list mode must remain a single column.');
$gallery = cds_gcds_render_legacy_block('<figure class="wp-block-gallery"><figure><img src="/image.png" alt="Example"></figure></figure>', ['blockName' => 'core/gallery', 'attrs' => ['columns' => 8]]);
$assert(str_contains($gallery, 'columns-tablet="repeat(8, minmax(0, 1fr))"'), 'Gallery column settings above six must not be silently reduced.');

$rendered = do_blocks('<!-- wp:list --><ul class="wp-block-list"><li>One<ul><li>Nested</li></ul></li></ul><!-- /wp:list --><!-- wp:list {"ordered":true,"type":"lower-alpha"} --><ol type="a"><li>First</li></ol><!-- /wp:list -->');
$assert(str_contains($rendered, 'class="wp-block-list list-disc"') && str_contains($rendered, '<ul class="list-circle">'), 'Bulleted and nested lists must use GCDS list styles.');
$assert(str_contains($rendered, 'class="wp-block-list list-lower-alpha" type="a"'), 'Ordered list marker types must survive as GCDS list styles.');
$assert(cds_gcds_convert_html($rendered) === $rendered, 'List conversion must remain idempotent.');
$image = '<!-- wp:image --><figure class="wp-block-image"><img src="/image.png" alt="Example"><figcaption class="wp-element-caption">Photo <a href="/credit">credit</a></figcaption></figure><!-- /wp:image -->';
$rendered = do_blocks($image);
$assert(str_contains($rendered, 'class="img-responsive"') && str_contains($rendered, 'alt="Example"'), 'Images must keep their alternative text and use GCDS image sizing.');
$assert(str_contains($rendered, '<figcaption class="wp-element-caption"><gcds-text size="small" text-role="secondary" margin-top="100" margin-bottom="0">Photo <gcds-link href="/credit">credit</gcds-link></gcds-text></figcaption>'), 'Media captions must become GCDS text without losing links.');
$assert(cds_gcds_convert_html($rendered) === $rendered, 'Caption conversion must remain idempotent.');
$rendered = do_blocks('<!-- wp:video --><figure class="wp-block-video"><video controls src="/clip.mp4"></video><figcaption>Clip</figcaption></figure><!-- /wp:video -->');
$assert(str_contains($rendered, '<video controls src="/clip.mp4"></video>') && str_contains($rendered, 'text-role="secondary" margin-top="100" margin-bottom="0">Clip</gcds-text>'), 'Media players must keep native controls with GCDS captions.');
$rendered = do_blocks('<!-- wp:quote --><blockquote class="wp-block-quote"><!-- wp:paragraph --><p>Quoted</p><!-- /wp:paragraph --><cite>Author</cite></blockquote><!-- /wp:quote -->');
$assert(str_contains($rendered, '<gcds-text>Quoted</gcds-text>') && str_contains($rendered, '<gcds-text size="small" text-role="secondary" margin-top="100" margin-bottom="0"><cite>Author</cite></gcds-text>'), 'Quotes must use GCDS text and retain citation semantics.');
$assert(preg_match('/<blockquote class="wp-block-quote[^"]* ps-300 mb-400"/', $rendered) === 1, 'Quotes must use GCDS spacing shortcuts.');
$rendered = do_blocks('<!-- wp:code --><pre class="wp-block-code"><code>&lt;p&gt;Hi&lt;/p&gt;</code></pre><!-- /wp:code -->');
$assert(str_contains($rendered, '<pre class="wp-block-code font-family-monospace bg-light p-300 mb-400"><code>&lt;p&gt;Hi&lt;/p&gt;</code></pre>'), 'Code must keep escaped content and use GCDS monospace styles.');
$rendered = do_blocks('<!-- wp:spacer {"height":"48px"} --><div style="height:48px" aria-hidden="true" class="wp-block-spacer"></div><!-- /wp:spacer --><!-- wp:separator --><hr class="wp-block-separator has-alpha-channel-opacity"/><!-- /wp:separator -->');
$assert(str_contains($rendered, '<div aria-hidden="true" class="wp-block-spacer pt-600"></div>'), 'Spacer heights must map to the equivalent GCDS spacing token.');
$assert(str_contains($rendered, 'class="wp-block-separator has-alpha-channel-opacity my-400"'), 'Separators must use GCDS spacing.');
$assert(str_contains(cds_gcds_convert_html('<div style="height:200px" aria-hidden="true" class="wp-block-spacer"></div>'), 'style="height:200px"'), 'Spacers taller than the largest token must keep their exact height.');
$rendered = do_blocks('<!-- wp:file --><div class="wp-block-file"><a id="file-1" href="/doc.pdf">doc</a><a href="/doc.pdf" class="wp-block-file__button wp-element-button" download aria-describedby="file-1">Download</a></div><!-- /wp:file -->');
$assert(str_contains($rendered, '<gcds-link id="file-1" href="/doc.pdf">doc</gcds-link>') && preg_match('/<gcds-button[^>]*href="\/doc.pdf"[^>]*download[^>]*type="link"/', $rendered) === 1, 'File downloads must use GCDS link and button components.');
$rendered = do_blocks($table);
$assert(str_contains($rendered, 'slot="caption">Sample data') && !str_contains($rendered, 'margin-bottom="0"'), 'Table captions must not be wrapped as media captions.');

$grid = '<!-- wp:cds-gcds/grid --><!-- wp:cds-gcds/grid-col --><!-- wp:cds-gcds/text {"content":"Inside"} /--><!-- /wp:cds-gcds/grid-col --><!-- /wp:cds-gcds/grid -->';
$rendered = do_blocks($grid);
$assert(str_contains($rendered, '<gcds-grid'), 'New grid block must render.');
$dom = cds_gcds_dom($rendered);
$column = $dom->getElementsByTagName('gcds-grid-col')->item(0);
$assert($column && $column->parentNode->nodeName === 'gcds-grid', 'Grid columns must be direct children without Gutenberg wrappers.');
$assert(str_contains($rendered, '>Inside</gcds-text>'), 'Nested GCDS block content must survive dynamic rendering.');
$attributes = ['caption' => 'L’information', 'columns' => '[{"field":"x","header":"X"}]', 'data' => '[{"x":"Example"}]'];
$saved = get_comment_delimited_block_content('cds-gcds/table', $attributes, '');
$assert(parse_blocks(wp_kses_post($saved))[0]['attrs'] === $attributes, 'WordPress KSES must preserve new block JSON and UTF-8 attributes without custom-element HTML.');
$counter = cds_gcds_render_legacy_block('<div class="site-counter">42</div>', ['blockName' => 'cds/site-counter']);
$assert(str_contains($counter, '<gcds-text class="site-counter">42</gcds-text>'), 'Site counter conversion must preserve the plugin-provided count.');

foreach (cds_gcds_block_definitions() as $name => $definition) {
    $assert(WP_Block_Type_Registry::get_instance()->is_registered('cds-gcds/' . $name), "Block $name must be registered server-side.");
}

$rendered = cds_gcds_render_component('link', ['href' => 'javascript:alert(1)', 'content' => 'Unsafe'], '');
$assert(str_contains($rendered, 'notice-role="danger"'), 'Unsafe URLs must produce an explicit configuration error.');
$rendered = cds_gcds_render_component('pagination', ['label' => 'Pages', 'next-href' => 'javascript:alert(1)'], '');
$assert(str_contains($rendered, 'notice-role="danger"'), 'Unsafe optional URLs must also produce an explicit configuration error.');
$rendered = cds_gcds_render_component('table', ['caption' => 'Data', 'columns' => '[{"field":"x","header":"X"}]', 'data' => '{"x":1}'], '');
$assert(str_contains($rendered, 'notice-role="danger"'), 'Invalid table JSON must produce an explicit configuration error.');
$rendered = cds_gcds_render_component('link', ['href' => '/safe', 'content' => '<a href="/nested">Nested</a><script>alert(1)</script>'], '');
$assert(!str_contains($rendered, '<script') && substr_count($rendered, '<gcds-link') === 1, 'Link content must not create scripts or nested links.');

$originalQuery = $GLOBALS['wp_query'];
$GLOBALS['wp_query'] = clone $originalQuery;
$GLOBALS['wp_query']->max_num_pages = 3;
foreach ([1, 3] as $page) {
    set_query_var('paged', $page);
    ob_start();
    cds_the_posts_navigation();
    $navigation = ob_get_clean();
    $attribute = $page === 1 ? 'previous-href=""' : 'next-href=""';
    $assert(str_contains($navigation, $attribute), 'Archive pagination must not link beyond its first or last page.');
}
$GLOBALS['wp_query'] = $originalQuery;

$items = [
    (object) ['ID' => 100, 'object_id' => 0, 'menu_item_parent' => 0, 'url' => '/parent', 'title' => 'Parent', 'target' => '_blank'],
    (object) ['ID' => 101, 'object_id' => 0, 'menu_item_parent' => 100, 'url' => '/child', 'title' => 'Child', 'target' => '_self'],
];
$menu = cds_gcds_nav_items($items, 0, true);
$assert(str_contains($menu, '<gcds-link href="/parent" target="_blank">'), 'Custom menu targets must survive the GCDS navigation API limitation.');
$assert(str_contains($menu, '<gcds-details') && str_contains($menu, 'href="/child"'), 'Target-preserving navigation must retain collapsible submenus and their destinations.');
$dom = cds_gcds_dom(cds_gcds_nav_items($items));
$assert($dom->getElementsByTagName('gcds-nav-link')->item(0)->parentNode->nodeName === 'gcds-nav-group', 'Standard menu links must remain direct children of GCDS navigation groups.');

echo "Passed $checks GCDS rendering checks.\n";
