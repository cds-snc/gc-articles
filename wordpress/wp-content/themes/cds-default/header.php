<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cds-default
 */

declare(strict_types=1);

?>
<!doctype html>
<html lang="<?php echo get_active_language(); ?>">
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet"
        href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
        integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"
        crossorigin="anonymous"
  ></link>
  <noscript>
    <link rel="stylesheet" href="https://www.canada.ca/etc/designs/canada/wet-boew/css/noscript.min.css" />
  </noscript>
  <?php wp_head(); ?>
  <link rel="stylesheet" href="https://www.canada.ca/etc/designs/canada/wet-boew/css/wet-boew.min.css" />
  <link rel="stylesheet" href="https://www.canada.ca/etc/designs/canada/wet-boew/css/theme.min.css" />

  <meta name="rest_url" content="<?php echo esc_url_raw(rest_url()) ?>">
  <meta name="rest_nonce" content="<?php echo wp_create_nonce("wp_rest") ?>">
</head>

<body <?php body_class(); ?> vocab="http://schema.org/" resource="#wb-webpage" typeof="WebPage">
<?php if (cds_is_maintenance_mode_admin_user()) {
    $tag = "<span class='maintenance-tag'>PRIVATE</span>";
    $maintenanceText = __("Your site is currently in maintenance mode. Only logged in users will be able to see this page.", "cds-snc");

    $siteSettingsLink = sprintf(
        __('To make your site live <a href="%s">update your site settings</a>.', 'cds-snc'),
        esc_url('options-general.php?page=collection-settings')
    );

    printf("<div class='container'><div class='row'><div class='maintenance-banner'>%s %s %s</div></div></div>", $tag, $maintenanceText, $siteSettingsLink);
}
?>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e(
    'Skip to content',
    'cds-snc',
); ?></a>
<header id="top">
  <div id="wb-bnr" class="container">
    <div class="row">
      <section id="wb-lng" class="col-xs-3 col-sm-12 pull-right text-right">
        <h2 class="wb-inv"><?php _e('Language selection'); ?></h2>
        <ul class="list-inline mrgn-bttm-0">
          <?php
            if (has_nav_menu('topbar')) {
                wp_nav_menu(array(
                    'container' => false,
                    'depth' => 1,
                    'items_wrap' => '%3$s',
                    'theme_location' => 'topbar'
                ));
            }
            ?>
          <?php if (is_main_site()) { ?>
            <li class="mrgn-rght-md">
                <?php if (is_user_logged_in()) { ?>
                <a href="<?php echo wp_logout_url(); ?>"><?php _e('Sign out', 'cds-snc')?></a>
                <?php } else { ?>
                <a href="<?php echo wp_login_url();  ?>"><?php _e('Sign in', 'cds-snc')?></a>
                <?php } ?>
            </li>
          <?php } ?>
          <li>
              <?php echo language_switcher(); ?>
          </li>
        </ul>
      </section>
        <?php $langText = get_language_text(); ?>
      <div class="brand col-xs-9 col-sm-5 col-md-4" property="publisher" resource="#wb-publisher"
           typeof="GovernmentOrganization">
        <a href="<?php echo get_fip_url(); ?>" property="url">
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
        <form id="site-search"
              action="<?php echo home_url(); ?>"
              method="GET"
              name="cse-search-box"
              role="search">
          <div class="form-group wb-srch-qry">
            <label for="wb-srch-q" class="wb-inv"><?php _e(
                'Search',
                'cds-snc',
            ); ?></label>
            <?php
            $placeholder = __('Search', 'cds-snc') . " " . get_bloginfo("name");
            ?>
            <input name="s" id="wb-srch-q" list="wb-srch-q-ac" class="wb-srch-q form-control" name="q" type="search"
                   value="<?php echo get_search_query(); ?>" size="34" maxlength="170" placeholder="<?php echo $placeholder; ?> ">
            <datalist id="wb-srch-q-ac">
            </datalist>
          </div>
          <div class="form-group submit">
            <button type="submit" id="wb-srch-sub" class="btn btn-primary btn-small" name="wb-srch-sub">
              <span class="glyphicon-search glyphicon"></span><span class="wb-inv"><?php _e(
                  'Search',
                  'cds-snc',
              ); ?></span></button>
          </div>
        </form>
      </section>

    </div>
  </div>

  <?php
    // option is set in "cds-base" plugin
    $showWetMenu = get_option('show_wet_menu');
    // show header menu if option is not set at all or if "custom"
    $ifShowWetMenu = (empty($showWetMenu) || $showWetMenu === 'custom') ? true : false;

    $headerMenu = get_top_nav();
    if ($ifShowWetMenu && $headerMenu && !cds_is_maintenance_mode()) :
        echo $headerMenu;
    else :
        ?>
  <nav class="gcweb-menu" typeof="SiteNavigationElement">
    <div class="container">
      <h2 class="wb-inv"><?php _e('Menu', 'cds-snc'); ?></h2>
      <button type="button" aria-haspopup="true" aria-expanded="false">
        <span class="wb-inv"><?php _e('Main', 'cds-snc'); ?> </span>
          <?php _e('Menu', 'cds-snc'); ?> 
          <span class="expicon glyphicon glyphicon-chevron-down"></span></button>
      <ul 
        role="menu" 
        aria-orientation="vertical"
        data-ajax-replace="https://www.canada.ca/content/dam/canada/sitemenu/sitemenu-v2-<?php echo get_active_language(); ?>.html">
      </ul>
    </div>
  </nav>
    <?php endif; ?>

  <?php echo cds_breadcrumb(); ?>
</header>

