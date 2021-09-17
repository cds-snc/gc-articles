<?php

namespace CDS\Modules\Cleanup;

class Login
{
    public function __construct()
    {
        add_action('login_enqueue_scripts', [$this, 'cds_login_logo']);
        add_filter('login_headerurl', [$this, 'cds_login_logo_url']);
        add_filter('login_headertext', [$this, 'cds_customize_login_headertext']);
        add_action('login_head', [$this, 'cds_favicon']);
        add_action('admin_head', [$this, 'cds_favicon']);

        add_filter('login_redirect', [$this, 'cdsLoginRedirect'], 10, 3);
    }

    public function cds_login_logo(): void
    { ?>
      <style type="text/css">
          body.login div#login h1 a {
              background-image: url(<?php echo cds_plugin_images_url('site-login-logo.svg'); ?>);
              width: 300px;
              height: 59px;
              background-size: contain;
              margin-bottom: 10px;
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

    public function cds_login_logo_url()
    {
        return home_url();
    }

    public function cds_customize_login_headertext($headertext)
    {
        return esc_html__('Canadian Digital Service', 'cds');
    }

    public function cds_favicon(): void
    {
        $favicon_url = cds_plugin_images_url('favicon.ico');
        echo '<link rel="shortcut icon" href="'.$favicon_url.'" />';
    }

    function cdsLoginRedirect($redirect_to, $request, $user)
    {
        $redirect_to = admin_url()."admin.php?page=cds_notify_send";

        return $redirect_to;
    }
}
