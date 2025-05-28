<?php
global $wpdb;

$users = get_users(array('fields' => 'ID'));

echo '<style>table, th, td {border: 1px solid;padding:0 3px;}</style>';
echo '<style>td {text-align:right;}</style>';
echo '<table border="1">';
	echo '<tr>';
	echo '<th>User</th>';
	echo '<th>Ecowood</th>';
	echo '<th>Ecowood Plus</th>';
	echo '<th>Biowood</th>';
	echo '<th>Biowood Plus</th>';
	echo '<th>Supreme</th>';
	echo '<th>Earth</th>';
	echo '<th>BySea</th>';
	echo '</tr>';
foreach($users as $user_id){
	echo '<tr>';
	echo '<td style="text-align:left;">' . get_user_meta($user_id,'nickname',true) . '</td>'; 
	echo '<td>' . get_user_meta($user_id,'Ecowood',true) . '</td>';
	echo '<td>' . get_user_meta($user_id,'EcowoodPlus',true) . '</td>';
	echo '<td>' . get_user_meta($user_id,'Biowood',true) . '</td>';
	echo '<td>' . get_user_meta($user_id,'BiowoodPlus',true) . '</td>';
	echo '<td>' . get_user_meta($user_id,'Supreme',true) . '</td>';
	echo '<td>' . get_user_meta($user_id,'Earth',true) . '</td>';
	echo '<td>' . get_user_meta($user_id,'train_price',true) . '</td>';
	echo '</tr>';
}
?>
