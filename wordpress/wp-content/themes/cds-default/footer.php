<?php

declare(strict_types=1);

$lang = get_active_language();
$footerText = require_once('footer_text_' . $lang . '.php');
$footerMenu = '';

?>

<div style="display:none;" id="version" style="margin-top:30px;"><?php echo _S_VERSION; ?></div>
<footer id="wb-info">
    <div class="landscape">
        <nav class="container wb-navcurr">
            <h2 class="wb-inv"><?php echo $footerText['aboutGovernmentTitle']; ?></h2>
            <ul class="list-unstyled colcount-sm-2 colcount-md-3">
                <?php echo get_footer_links($footerText['aboutGovernment']); ?>
            </ul>
        </nav>
    </div>
    <div class="brand">
        <div class="container">
            <div class="row">
                <nav class="col-md-9 col-lg-10 ftr-urlt-lnk">
                    <h2 class="wb-inv"><?php echo $footerText['aboutThisSiteTitle']; ?></h2>
                    <ul>
                    <?php
                    $locations = get_nav_menu_locations();

                    if (isset($locations['footer'])) {
                        // Get menu assigned to 'footer' location, or false
                        $footerMenu = wp_get_nav_menu_object($locations['footer']);
                    }

                    $links = $footerMenu ? wp_get_nav_menu_items($footerMenu->name) : $footerText['aboutThisSite'];

                    echo get_footer_links($links);
                    ?>
                    </ul>
                </nav>
                <div class="col-xs-6 visible-sm visible-xs tofpg">
                    <a href="#top">
                        <?php echo $footerText['topOfPage']; ?>
                        <span class="glyphicon glyphicon-chevron-up"></span>
                    </a>
                </div>
                <div class="col-xs-6 col-md-3 col-lg-2 text-right">
                    <img 
                        src="https://wet-boew.github.io/themes-dist/GCWeb/GCWeb/assets/wmms-blk.svg"
                        alt="<?php echo $footerText['wordmark']; ?>" />
                </div>
            </div>
        </div>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.js"></script>
<script src="https://www.canada.ca/etc/designs/canada/wet-boew/js/wet-boew.min.js"></script>
<script src="https://www.canada.ca/etc/designs/canada/wet-boew/js/theme.min.js"></script>

<?php wp_footer(); ?>

</body>
</html>
