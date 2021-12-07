<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

class Wpml
{

    public static function setup()
    {
        add_action('wpml_override_is_translator', '__return_true');

        add_action('admin_footer', function () {

            // update category url in side nav to default to "all"
            echo "<script>
                    jQuery(jQuery('a[href*=\"edit-tags.php?taxonomy=category\"]'))
                    .attr('href', 'edit-tags.php?taxonomy=category&lang=all' );
                    
                    jQuery(jQuery('#menu-pages a')[0])
                    .attr('href', 'edit.php?post_type=page&lang=all' );

                    jQuery(jQuery('#menu-posts a')[0])
                    .attr('href', 'edit.php?post_type=post&all_posts=1&lang=all' );

                    jQuery(document).ready(function( $ ) {
                        // noting need to wrap this in 'ready' for the element to exist
                        var el = $('a[href*=\"menus-sync.php\"]');
                        if(el.length >= 1){
                            $(el[0]).hide();
                        }
                    });
                    </script>";

            if (!isset($_GET["taxonomy"])) {
                return;
            }

            echo '<script>jQuery("#icl_subsubsub").clone().removeAttr("id").prependTo(".search-form");</script>';
            echo '<style>#icl_subsubsub{display:none !important;}</style>';
        }, 200, 10);

        /**
         * Disable WPML Translation Editor
         */
        if ($settings = get_option('icl_sitepress_settings')) {
            if (!array_key_exists('post_translation_editor_native', $settings['translation-management'] ?? [])) {
                $settings['translation-management']['post_translation_editor_native'] = true;
                update_option('icl_sitepress_settings', $settings);
            }
            if (array_key_exists('post_translation_editor_native_for_post_type', $settings['translation-management'] ?? [])) {
                unset($settings['translation-management']['post_translation_editor_native_for_post_type']);
                update_option('icl_sitepress_settings', $settings);
            }
        }
    }
}
