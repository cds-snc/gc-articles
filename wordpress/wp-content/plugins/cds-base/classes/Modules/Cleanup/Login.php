<?php

namespace CDS\Modules\Cleanup;

class Login
{
    public function __construct()
    {
        add_action('login_enqueue_scripts', [$this, 'loginLogo']);
        add_filter('login_headerurl', [$this, 'loginLogoUrl']);
        add_filter('login_headertext', [$this, 'customizeLoginHeaderText']);
        add_action('login_head', [$this, 'favicon']);
        add_action('admin_head', [$this, 'favicon']);
        add_filter('login_title', [$this, 'customLoginTitle']);
        add_action('wp_login_failed', [$this, 'loginFailed']);
        add_filter('login_redirect', [$this, 'loginRedirect'], 10, 3);
    }

    public function loginLogo(): void
    {
        ?>
      <style>
          .login *, .login form label, .login form .button.button-large {
              font-size: 16px;
          }

          body.login div#login h1 a {
              background-image: url(<?php echo cds_plugin_images_url('site-login-logo.svg'); ?>);
              width: 300px;
              height: 59px;
              background-size: contain;
              margin-bottom: 20px;
          }

          .wp-core-ui .button-primary, .wp-core-ui .button-primary:focus {
              --bg-opacity: 1;
              background-color: #284162 !important;
              background-color: rgba(40, 65, 98, var(--bg-opacity)) !important;
          }

          body {
              --bg-opacity: 1;
              background-color: #eee;
              background-color: rgba(238, 238, 238, var(--bg-opacity));
          }
      </style>
    <?php }

    public function loginLogoUrl(): string
    {
        return home_url();
    }

    public function customizeLoginHeaderText(): string
    {
        return esc_html__('Canadian Digital Service', 'cds-snc');
    }

    public function favicon(): void
    {
        $favicon_url = cds_plugin_images_url('favicon.ico');
        echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
    }

    public function loginRedirect(): string
    {
        return admin_url() . 'index.php';
    }

    public function loginFailed($username): void
    {
        error_log("LOGIN FAILED: user $username: authentication failure for \"" . admin_url() . "\"");
    }

    public function customLoginTitle($login_title)
    {
        $siteName = __("GC Articles", "cds-snc");
        return str_replace(array('&lsaquo;', 'WordPress'), array( '', $siteName), $login_title);
    }
}
