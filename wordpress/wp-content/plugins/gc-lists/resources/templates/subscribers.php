<?php
/**
 * Base template for the lists UI
 *
 * @var string $title
 * @var array $services
 * @var object $user
 */
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
    <div id="list-manager-app" data-user='<?php echo json_encode($user); ?>' data-ids='<?php echo json_encode($services); ?>' data-base-url='<?php echo get_site_url(); ?>'>
    </div>
</div>

<script>
    let hash = window.location.hash;
    if(!hash.startsWith('#/lists')) {
        window.location = "#/lists";
    }
</script>
