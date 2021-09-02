<?php

declare(strict_types=1);

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cds-default
 */

?>

<!-- Static footer starts -->
<footer id="wb-info">
    <div class="landscape">
        <nav class="container wb-navcurr">
            <h2 class="wb-inv">Au sujet du gouvernement</h2>
            <ul class="list-unstyled colcount-sm-2 colcount-md-3">
                <li><a href="http://www.phac-aspc.gc.ca/contac-fra.php">Contactez-nous</a></li>
                <li><a href="https://www.canada.ca/fr/gouvernement/min.html">Ministères et organismes</a></li>
                <li><a href="https://www.canada.ca/fr/gouvernement/fonctionpublique.html">Fonction publique et force militaire</a></li>
                <li><a href="https://www.canada.ca/fr/nouvelles.html">Nouvelles</a></li>
                <li><a href="https://www.canada.ca/fr/gouvernement/systeme/lois.html">Traités, lois et règlements</a></li>
                <li><a href="https://www.canada.ca/fr/transparence/rapports.html">Rapports à l&#39;échelle du gouvernement</a></li>
                <li><a href="https://pm.gc.ca/fr">Premier ministre</a></li>
                <li><a href="https://www.canada.ca/fr/gouvernement/systeme.html">À propos du gouvernement</a></li>
                <li><a href="http://ouvert.canada.ca/">Gouvernement ouvert</a></li>
            </ul>
        </nav>
    </div>
    <div class="brand">
        <div class="container">
            <div class="row">
                <nav class="col-md-10 ftr-urlt-lnk">
                    <h2 class="wb-inv">À propos de ce site</h2>
                    <ul>
                        <li><a href="https://www.canada.ca/fr/sociaux.html">Médias sociaux</a></li>
                        <li><a href="https://www.canada.ca/fr/mobile.html">Applications mobiles</a></li>
                        <li><a href="https://www.canada.ca/fr/gouvernement/a-propos.html">À propos de Canada.ca</a></li>
                        <li><a href="https://www.canada.ca/fr/transparence/avis.html">Avis</a></li>
                        <li><a href="https://www.canada.ca/fr/transparence/confidentialite.html">Confidentialité</a></li>
                    </ul>
                </nav>
                <div class="col-xs-6 visible-sm visible-xs tofpg">
                    <a href="#wb-cont">Haut de la page <span class="glyphicon glyphicon-chevron-up"></span></a>
                </div>
                <div class="col-xs-6 col-md-2 text-right">
                    <img src="https://www.canada.ca/etc/designs/canada/wet-boew/assets/wmms-blk.svg" alt="Symbole du gouvernement du Canada"/>
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
