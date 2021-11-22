<?php

declare(strict_types=1);

namespace CDS\Modules\Cleanup;

use CDS\Utils;

class Wpml
{

    public static function setup()
    {
        add_action('admin_footer', function () {
            if (!isset($_GET["taxonomy"])) {
                return;
            }
            echo '<script>jQuery("#icl_subsubsub").clone().removeAttr("id").prependTo(".search-form");</script>';
            echo '<style>#icl_subsubsub{display:none !important;}</style>';
        });
        /**
         * We need to set this option to an empty array. This is supposed to be an array of directories that WPML will
         * scan looking for language switchers, but it has problems with s3: prefixed URLs. We don't use these
         * language switchers anyway, so just kill it.
         */
        add_action('setup_theme', function () {
            Utils::addOrUpdateOption('wpml_language_switcher_template_objects', []);
        }, 10, 1);




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
