
Using the Notify Mailer



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