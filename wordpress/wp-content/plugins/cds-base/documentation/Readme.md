## Retired public forms

The legacy contact and site-request forms have been removed, including their
mail helper and JavaScript. The `contact-form` and `request-site-form` shortcodes
and the `cds/contact` and `cds/request` blocks are no longer registered.
Remove any remaining uses from stored page content.

The public POST routes `/wp-json/contact/v1/process` and
`/wp-json/request/v1/process` are no longer registered and should return
WordPress's `rest_no_route` (404) response after deployment. Verify both routes
on the deployed site. The active Notify integration and `cds/site-counter`
block are unchanged.

## Using the Notify Mailer



```php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__ . '/email/mailer.php';
$notifyMailer = new \NotifyMailer\CDS\NotifyMailer();

$emailTo = "";
$templateId = "";
$notifyMailer->sendMail($emailTo, $templateId);
```