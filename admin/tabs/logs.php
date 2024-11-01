<?php  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<h1><?php esc_html_e('Logs','syrus-ai') ?></h1>

<?php
    global $syrusAIPlugin;
    $general_settings = $syrusAIPlugin->get_general_settings();
    $logs = $syrusAIPlugin->get_logs();
?>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">Name</th>
            <th scope="col">Value</th>
            <th scope="col">Hour</th>
            <th scope="col">Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($logs as $log) { ?>
            <tr>
                <td><?php echo esc_html($log['id']) ?></td>
                <td><?php echo esc_html($log['name']) ?></td>
                <td><?php echo esc_html($log['value']) ?></td>
                <td><?php echo esc_html($log['hour']) ?></td>
                <td><?php echo esc_html($log['date']) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
