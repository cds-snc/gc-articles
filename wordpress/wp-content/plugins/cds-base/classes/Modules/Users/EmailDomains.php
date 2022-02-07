<?php

declare(strict_types=1);

namespace CDS\Modules\Users;

use WP_Error;

class EmailDomains
{
    public const ALLOWED_EMAIL_DOMAINS = ['cds-snc.ca', 'gc.ca', 'canada.ca'];


    public function __construct()
    {
    }

    public static function register()
    {
        $instance = new self();

        $instance->addFilters();
    }

    public function addFilters()
    {

        $pageName = basename($_SERVER['PHP_SELF']);

        if ( 'profile.php' === $pageName){
           add_filter('is_email', [$this, "isEmail"], 10, 3);
        }

        // add_filter('is_email_address_unsafe', [$this, "isUnsafeEmail"], 10, 2);
    }

    public static function isAllowedDomain($user_email): bool
    {
        if (
            isset($user_email) &&
            strpos($user_email, '@') > 0 && // "@" can't be first character
            is_email($user_email)
        ) {
            return EmailDomains::filterDomain($user_email);
        }

        return false;
    }

    public static function validateEmailDomain($result)
    {

        $message =
            __(
                'You can’t use this email domain for registration.',
                'cds-snc',
            );

        if (!self::isAllowedDomain($result['user_email'])) {
            $result['errors']->add('user_email', $message);
        }

        return $result;
    }

    public static function isEmail($is_email = false, $email):bool{

        if(!$is_email){
            return false;
        }

        $isAllowed = self::isAllowedDomain($email);


       if(!$isAllowed){
            /*
           $errors = new WP_Error();

           $message =
               __(
                   'You can’t use this email domain for registration.',
                   'cds-snc',
               );

           $errors->add( 'user_email', $message );
            */
       }

       return $isAllowed;

    }

    public static function isUnsafeEmail($is_email_address_unsafe, $email): bool{

        if($is_email_address_unsafe === true){
            return $is_email_address_unsafe;
        }

        if(!self::filterDomain($email)){
            // this is "unsafe
            return true;
        }

        return false;
    }

    // fixes https://github.com/cds-snc/gc-articles-issues/issues/208
    public static function filterDomain($email) : bool
    {
        try {

            $allowed_email_domains = apply_filters(
                'cds_allowed_email_domains',
                self::ALLOWED_EMAIL_DOMAINS,
            );


            $isAllowedDomain  = false;

            [, $domain] = explode('@', trim($email));

            foreach ($allowed_email_domains as $allowed_domain) {
                if (str_ends_with($domain, $allowed_domain)) {
                    $isAllowedDomain = true;
                }
            }

            return  $isAllowedDomain;

        }catch(\Exception $e){
            echo $e->getMessage();
            return false;
        }

    }
}
