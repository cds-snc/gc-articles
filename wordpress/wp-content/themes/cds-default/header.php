<?php

declare(strict_types=1);

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cds-default
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.canada.ca/etc/designs/canada/wet-boew/css/wet-boew.min.css"/>
    <link rel="stylesheet" href="https://www.canada.ca/etc/designs/canada/wet-boew/css/theme.min.css"/>
    <link rel="stylesheet"
          href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"
          crossorigin="anonymous"
    ></link>
    <?php $cds_description = get_bloginfo('description', 'display'); ?>
    <meta name="description" content="<?php echo $cds_description; ?>">
    <link href="https://www.canada.ca/etc/designs/canada/wet-boew/assets/favicon.ico" rel="icon" type="image/x-icon">
    <noscript>
        <link rel="stylesheet" href="https://www.canada.ca/etc/designs/canada/wet-boew/css/noscript.min.css"/>
    </noscript>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> vocab="http://schema.org/" resource="#wb-webpage" typeof="WebPage">
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'cds-snc'); ?></a>
<header>
    <div id="wb-bnr" class="container">
        <div class="row">
            <section id="wb-lng" class="col-xs-3 col-sm-12 pull-right text-right">
                <h2 class="wb-inv"><?php _e('Language selection'); ?></h2>
                <ul class="list-inline mrgn-bttm-0">
                    <li>
                        <?php echo language_switcher(); ?>
                    </li>
                </ul>
            </section>
            <?php
            $langText = get_language_text()
            ?>
            <div class="brand col-xs-9 col-sm-5 col-md-4" property="publisher" resource="#wb-publisher"
                 typeof="GovernmentOrganization">
                <a href="https://www.canada.ca/<?php echo get_active_language(); ?>.html" property="url">
                    <?php if (get_active_language() === 'fr') { ?>
                        <img src="https://canada.ca/etc/designs/canada/wet-boew/assets/sig-blk-fr.svg"
                             alt="Gouvernement du Canada" property="logo">
                    <?php } else { ?>
                        <img src="https://canada.ca/etc/designs/canada/wet-boew/assets/sig-blk-en.svg" alt=""
                             property="logo">
                    <?php } ?>
                    <span class="wb-inv" property="name"> Government of Canada / <span
                                lang="fr">Gouvernement du Canada</span></span>
                </a>
                <meta property="areaServed" typeof="Country" content="Canada">
                <link property="logo" href="https://canada.ca/etc/designs/canada/wet-boew/assets/wmms-blk.svg">
            </div>
            <section id="wb-srch" class="col-lg-offset-4 col-md-offset-4 col-sm-offset-2 col-xs-12 col-sm-5 col-md-4">
                <h2><?php _e('Search', 'cds-snc'); ?></h2>
                <form action="/<?php echo $langText['abbr']; ?>/sr/srb.html" method="post" name="cse-search-box"
                      role="search">
                    <div class="form-group wb-srch-qry">
                        <label for="wb-srch-q" class="wb-inv"><?php _e('Search Canada.ca', 'cds-snc') ?></label>
                        <input id="wb-srch-q" list="wb-srch-q-ac" class="wb-srch-q form-control" name="q" type="search"
                               value="" size="34" maxlength="170" placeholder="<?php _e('Search Canada.ca', 'cds-snc') ?>">
                        <datalist id="wb-srch-q-ac">
                        </datalist>
                    </div>
                    <div class="form-group submit">
                        <button type="submit" id="wb-srch-sub" class="btn btn-primary btn-small" name="wb-srch-sub">
                            <span class="glyphicon-search glyphicon"></span><span class="wb-inv"><?php _e(
                'Search',
                'cds-snc'
            ); ?></span></button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <nav class="gcweb-menu" typeof="SiteNavigationElement">
        <div class="container">
            <h2 class="wb-inv"><?php _e('Menu', 'cds-snc'); ?></h2>
            <button type="button" aria-haspopup="true" aria-expanded="false"><span
                        class="wb-inv"><?php _e('Main', 'cds-snc'); ?> </span><?php _e('Menu', 'cds-snc'); ?> <span
                        class="expicon glyphicon glyphicon-chevron-down"></span></button>
            <ul role="menu" aria-orientation="vertical">
                <?php
                // pulls in menu items from Canada.ca endpoint
                echo file_get_contents('https://www.canada.ca/content/dam/canada/sitemenu/sitemenu-v2-' . get_active_language() . '.html');
                ?>
            </ul>
        </div>
    </nav>
    <?php echo cds_breadcrumb(); ?>
</header>