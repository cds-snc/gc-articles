<?php

declare(strict_types=1);

namespace CDS\Modules\TwoFactor;

use Two_Factor_Core_Alias;

class TwoFactor
{
    public bool $twoFactorEnabled;

    public function __construct()
    {
        add_filter('two_factor_providers', [$this, 'configureProviders']);
        add_action('plugins_loaded', [$this, 'loadTwoFactorCore']);
        add_action('wp_dashboard_setup', [$this, 'dashboardWidget']);
    }

    public function loadTwoFactorCore(): void
    {
        if (class_exists('Two_Factor_Core')) {
            class_alias('Two_Factor_Core', 'Two_Factor_Core_Alias');
            $this->twoFactorEnabled = Two_Factor_Core_Alias::is_user_using_two_factor();
        }
    }

    public function configureProviders(): array
    {
        return [
            'Two_Factor_Email' => TWO_FACTOR_DIR . 'providers/class-two-factor-email.php',
            'Two_Factor_Totp' => TWO_FACTOR_DIR . 'providers/class-two-factor-totp.php',
            'Two_Factor_FIDO_U2F' => TWO_FACTOR_DIR . 'providers/class-two-factor-fido-u2f.php',
        ];
    }

    public function dashboardWidget(): void
    {
        if (!$this->twoFactorEnabled) {
            wp_add_dashboard_widget(
                'cds_2fa_widget',
                __('Two factor authentication', 'cds-snc'),
                [$this, 'twoFactorPanelHandler'],
            );
        }
    }

    public function twoFactorPanelHandler(): void
    {
        $profileUrl = admin_url('profile.php') . '#two-factor-options';

        $panel = '<div id="two-factor-panel">';
        $panel .= '<h4><span style="color:red" class="dashicons dashicons-warning"></span> ' . _('Warning', 'cds-snc') . '</h4>';
        $panel .= '<p>';
        $panel .= 'For security purposes, you should consider enabling two-factor authentication. ';
        $panel .= sprintf(wp_kses(__('This can be configured on your <a href="%s">User Profile</a>.', 'cds-snc'), ['a' => ['href' => []]]), esc_url($profileUrl));
        $panel .= '</p>';
        $panel .= '</div>';

        echo $panel;
    }
}
