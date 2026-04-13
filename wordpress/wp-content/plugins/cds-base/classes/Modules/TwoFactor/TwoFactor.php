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
        add_action('wp_login', [$this, 'redirectIfNo2FA'], 10, 2);
        add_action('admin_init', [$this, 'enforce2FA']);
        add_action('admin_notices', [$this, 'twoFactorRequiredNotice']);
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
        ];
    }

    public function dashboardWidget(): void
    {

        if (class_exists('Two_Factor_Core') && !$this->twoFactorEnabled) {
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
        $panel .= '<h4><span style="color:red" class="dashicons dashicons-warning"></span> ' . __('Warning', 'cds-snc') . '</h4>';
        $panel .= '<p>';
        $panel .= 'For security purposes, you should consider enabling two-factor authentication. ';
        $panel .= sprintf(wp_kses(__('This can be configured on your <a href="%s">User Profile</a>.', 'cds-snc'), ['a' => ['href' => []]]), esc_url($profileUrl));
        $panel .= '</p>';
        $panel .= '</div>';

        echo $panel;
    }

    public function redirectIfNo2FA(string $username, \WP_User $user): void
    {
        if (!class_exists('Two_Factor_Core_Alias')) {
            return;
        }

        if (!Two_Factor_Core_Alias::is_user_using_two_factor($user)) {
            wp_safe_redirect(admin_url('profile.php?2fa_required=1') . '#two-factor-options');
            exit;
        }
    }

    public function enforce2FA(): void
    {
        if (wp_doing_ajax() || wp_doing_cron()) {
            return;
        }

        if (!class_exists('Two_Factor_Core_Alias')) {
            return;
        }

        $user = wp_get_current_user();
        if (!$user->exists()) {
            return;
        }

        if (Two_Factor_Core_Alias::is_user_using_two_factor($user)) {
            return;
        }

        // Allow profile.php, user is directed to configure 2FA.
        $page = $GLOBALS['pagenow'] ?? '';
        if ($page === 'profile.php') {
            return;
        }

        wp_safe_redirect(admin_url('profile.php?2fa_required=1') . '#two-factor-options');
        exit;
    }

    public function twoFactorRequiredNotice(): void
    {
        if (!isset($_GET['2fa_required'])) {
            return;
        }

        echo '<div class="notice notice-error"><p>';
        echo esc_html__('You must configure two-factor authentication before you can access GC Articles.', 'cds-snc');
        echo '</p></div>';
    }
}
