<!DOCTYPE html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8" />

    <title></title>
    <meta content="width=device-width,initial-scale=1" name="viewport" />

    <link
      rel="preload"
      href="https://digital.canada.ca/fonts/SourceSansPro-Bold.ttf"
      as="font"
      type="font/ttf"
      crossorigin="anonymous"
    />
    <link
      rel="preload"
      href="https://digital.canada.ca/fonts/SourceSansPro-SemiBold.ttf"
      as="font"
      type="font/ttf"
      crossorigin="anonymous"
    />
    <link
      rel="preload"
      href="https://digital.canada.ca/fonts/SourceSansPro-Regular.ttf"
      as="font"
      type="font/ttf"
      crossorigin="anonymous"
    />

    <link
      rel="stylesheet"
      href="https://digital.canada.ca/lib-cds/gcweb-dist/GCWeb/css/theme.min.css"
    />

    <link
      rel="stylesheet"
      href="https://digital.canada.ca/sass/cds.css"
      media="screen"
    />
  </head>
  

  <body vocab="http://schema.org/" typeof="WebPage" class="page-blogpost">
    <nav aria-label="Skip Navigation Links">
      <ul id="wb-tphp">
        <li class="wb-slc">
          <a class="wb-sl" href="#wb-cont">Skip to main content</a>
        </li>
      </ul>
    </nav>

    <header role="banner">
      <div id="fip">
        <div class="container">
          <div class="row">
            <div class="col-xs-4">
              <a
                href="https://www.canada.ca/en.html"
                class="fip-logo gl-FIP"
                aria-label="Government of Canada"
              >
                <object
                  type="image/svg+xml"
                  tabindex="-1"
                  role="img"
                  aria-label="Symbol of the Government of Canada"
                  class="logo en"
                ></object>
              </a>
            </div>
            <div class="col-xs-8">
              <section id="wb-lng" class="visible-sm visible-md visible-lg">
                <h2>Language selection</h2>
                <div id="lang" class="lang">
                  <a
                    href="https://numerique.canada.ca/2022/07/05/informer-pour-prot%C3%A9ger-rappels-et-avis-de-s%C3%A9curit%C3%A9-au-canada/"
                    lang="fr"
                    >Français</a
                  >
                </div>
              </section>
            </div>
          </div>
        </div>
      </div>

      <nav
        role="navigation"
        id="wb-sm"
        data-trgt="mb-pnl"
        class="cds-menu"
        typeof="SiteNavigationElement"
        aria-label="Main Site Menu"
      >
        <div class="container topbar" id="site--topbar">
          <div class="row">
            <div class="col-xs-4 col-sm-3">
              <a
                href="https://digital.canada.ca/"
                class="cds-logo en"
                aria-label="Go to the homepage"
              ></a>

              <a
                href="https://digital.canada.ca"
                class="cds-logo-mobile en"
                aria-label="Go to the homepage"
              ></a>
            </div>

            <div class="nav-container" style="padding-bottom: 40px"></div>
          </div>
        </div>
      </nav>

      <?php
      global $post;
      $image = "";
      if ($post) {
          $image = get_the_post_thumbnail_url($post, 'full');
      }
        ?>

      <div class="page-banner" style="background-image: url('<?php echo $image; ?>');"></div>
    </header>
    
 <?php if (have_posts()) :
        while (have_posts()) :
            the_post(); ?>

 <main role="main" property="mainContentOfPage">
      <section id="wb-cont">
        <section class="blog-single">
          <div class="row">
            <div class="">
              <article
                class="post"
                itemscope
                itemtype="http://schema.org/BlogPosting"
              >
                <div class="post-header">
                  <div class="post-title-container">
                    <div class="container">
                      <span class="blogpost-pretitle">
                        <a href="/blog/">Read the blog /</a>
                      </span>
                      <h1><?php the_title();?></h1>
                    </div>
                  </div>
                  <div class="container">
                    <p class="post-meta">
                      <time
                        datetime="2022-07-05 14:30:00 &#43;0000 UTC"
                        itemprop="datePublished"
                      >
                        Jul 5, 2022
                      </time>
                      <span
                        class="author"
                        itemprop="author"
                        itemscope
                        itemtype="http://schema.org/Person"
                      >
                        <span itemprop="name">Author Name here</span>
                      </span>
                    </p>
                  </div>
                </div>
                <div class="container">
                  <div class="post-content" itemprop="articleBody">



    <div class="entry">
            <?php the_content(); ?>
    </div>

        <?php endwhile;
 else : ?>
    <!-- The very first "if" tested to see if there were any Posts to -->
    <!-- display.  This "else" part tells what do if there weren't any. -->
    <p><?php esc_html_e('Sorry, no posts matched your criteria.'); ?></p>


    <!-- REALLY stop The Loop. -->
 <?php endif; ?>


                  </div>
                </div>
              </article>
            </div>
          </div>
        </section>
      </section>
    </main>

    <nav class="social-links-footer" aria-label="Join the conversation">
      <span id="contact-us-links"></span>
      <div class="container">
        <div class="row equal">
          <div class="col-md-4 nopadding">
            <div class="border-green">
              <ul class="ul-margin">
                <li><a href="/a11y/">Accessibility</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-4 brown-padding">
            <div class="border-brown">
              <ul class="ul-margin">
                <li>
                  <a href="https://digital.canada.ca/meet-the-team/"
                    >Meet the team</a
                  >
                </li>
              </ul>
              <ul>
                <li><a href="/our-values/">Our values</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-4 nopadding">
            <div class="border-yellow">
              <ul class="ul-margin">
                <li><a href="mailto:cds-snc@tbs-sct.gc.ca">Contact us</a></li>
              </ul>
              <ul>
                <li>
                  <a
                    href="https://us15.campaign-archive.com/home/?u=729a207773f7324e217a1d945&amp;id=eb357181d2"
                    >Newsletter</a
                  >
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="row equal">
          <div class="col-md-4 nopadding">
            <div class="border-gray"></div>
          </div>
        </div>
      </div>
    </nav>

    <footer role="contentinfo">
      <div id="goc-footer" class="border-gray">
        <div class="container">
          <div class="row">
            <div class="col-xs-12 col-sm-4 col-sm-push-8 goc-footer--logo">
              <object
                type="image/svg+xml"
                tabindex="-1"
                role="img"
                data="https://digital.canada.ca/img/cds/goc--footer-logo.svg"
                aria-label="Symbol of the Government of Canada"
                class="logo"
              ></object>
            </div>
            <nav
              role="navigation"
              class="col-xs-12 col-sm-8 col-sm-pull-4 goc-footer--links"
              aria-label="Footer links"
            >
              <ul>
                <li><a href="#">Terms and Conditions</a></li>
                <li><a href="#">Privacy</a></li>
                <li><a href="#">Security Notice</a></li>
                <li><a href="#">Visit Canada.ca</a></li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </footer>

    <script src="https://digital.canada.ca/lib-cds/gcweb-dist/wet-boew/js/wet-boew.min.js"></script>
    <script src="https://digital.canada.ca/lib-cds/gcweb-dist/GCWeb/js/theme.min.js"></script>
    <script src="https://digital.canada.ca/js/cds-app.js"></script>
    <script src="https://digital.canada.ca/js/lazyload.js" async=""></script>
  </body>
</html>
