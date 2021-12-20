<?php

namespace CDS\Modules\Cleanup;

class Login
{
    public string $lang;

    public function __construct()
    {
        $this->lang = defined('ICL_LANGUAGE_CODE')
            ? (ICL_LANGUAGE_CODE === 'fr' ? 'fr' : 'en')
            : '';

        add_action('login_enqueue_scripts', [$this, 'loginLogo']);
        add_filter('login_headerurl', [$this, 'loginLogoUrl']);
        add_filter('login_headertext', [$this, 'customizeLoginHeaderText']);
        add_action('login_head', [$this, 'favicon']);
        add_action('admin_head', [$this, 'favicon']);
        add_filter('login_title', [$this, 'customLoginTitle']);
        add_action('wp_login_failed', [$this, 'loginFailed']);
        add_filter('login_redirect', [$this, 'loginRedirect'], 10, 3);
        add_filter('login_message', [$this, 'addLangLink']);

        add_action('admin_page_access_denied', [$this, 'redirectToNewestBlog'], 98);
    }

    public function redirectToNewestBlog(): void
    {
        $blogs = get_blogs_of_user(get_current_user_id());

        // nowhere to redirect if user has no blogs, so return
        if (empty($blogs)) {
            return;
        }

        // if user is currently on a blog they are allowed to be on, then this is a permissions error, so return
        if (in_array(get_current_blog_id(), array_keys($blogs))) {
            return;
        }

        // else, send to newest-created blog, since we have no other way of prioritizing.
        $newestBlog = array_pop($blogs);
        $adminUrl = esc_url(get_admin_url($newestBlog->userblog_id));
        wp_redirect($adminUrl, 302, $x_redirect_by = 'CDS\Modules\Cleanup\Login');

        // call this, or else we get a splash page called by '_access_denied_splash' in core WP
        die();
    }

    public function loginLogo(): void
    {
        $logoPath = $this->lang === 'fr' ? 'site-login-logo-fr.svg' : 'site-login-logo.svg';
        ?>
      <style>
          .login *, .login form label, .login form .button.button-large {
              font-size: 16px;
          }

          body.login div#login h1 a {
              background-image: url(<?php echo cds_plugin_images_url($logoPath); ?>);
              width: 300px;
              height: 59px;
              background-size: contain;
              margin-bottom: 20px;
          }

          .login .switch-lang {
              padding: 0 24px 0;
              margin-bottom: 24px;
          }

          .login .switch-lang a {
              color: #50575e;
          }

          .login .switch-lang a:hover {
              color: #135e96;
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

    public function addLangLink($message): string
    {
        if (!empty($this->lang)) {
            $frPrefix = '/fr';
            $loginPath = parse_url(wp_login_url(), PHP_URL_PATH);
            $switchLangPath = str_starts_with($loginPath, $frPrefix) ?
                str_replace($frPrefix, '', $loginPath) :
                $frPrefix . $loginPath;

            $switchLangText = $this->lang === 'fr' ? 'English' : 'Français';
            $switchLangAttr = $this->lang === 'fr' ? 'en' : 'fr';

            $switchLangLink = sprintf(
                '<div class="switch-lang"><a lang="%s" href="%s">%s</a></div>',
                $switchLangAttr,
                esc_url_raw($switchLangPath),
                $switchLangText
            );

            return $switchLangLink . $message;
        }
        return '';
    }

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
