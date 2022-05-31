<h1><?php

use CDS\Modules\Notify\Utils;

    echo $title ?></h1>

<?php
$serviceId = Utils::extractServiceIdFromApiKey(get_option('NOTIFY_API_KEY'));

$services[] = [
    'name' => __('Your Lists', 'cds-snc'),
    'service_id' => $serviceId,
    'sendingTemplate' => get_option('NOTIFY_GENERIC_TEMPLATE_ID', '')
];

$user = new \stdClass();
$user->hasEmail = current_user_can('list_manager_bulk_send');
$user->hasPhone = current_user_can('list_manager_bulk_send_sms');
?>
<!-- app -->
<div class="wrap">
    <?php
    echo "<!--";
    echo "manage_list_manager-" . current_user_can('manage_list_manager');
    echo current_user_can('list_manager_bulk_send');
    echo current_user_can('list_manager_bulk_send_sms');
    echo "-->";
    ?>
    <div id="list-manager-app" data-user='<?php echo json_encode($user); ?>' data-ids='<?php echo json_encode($services); ?>'>
    </div>
</div>
<script>
    window.location = "#/lists";
</script>
