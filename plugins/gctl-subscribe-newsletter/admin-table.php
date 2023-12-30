<?php
global $wpdb;

echo "<h2 class='h1 text-center'>Subscriber Email List</h2>";
echo "<br>";
$subscribersEmails = $wpdb->get_results("SELECT ID, email, subscribe_at FROM ".$wpdb->prefix."gctl_subscribe_newsletter", ARRAY_A);

if(count($subscribersEmails) > 0){
	?>
		<div class="container">
			<table id="example" class="display nowrap" style="width:100%">
		        <thead>
		            <tr>
		                <th>ID</th>
		                <th>Email</th>
		                <th>Date & Time</th>
		            </tr>
		        </thead>
		        <tbody>
		        	<?php
		        	foreach ($subscribersEmails as $subscribersEmail) {
		        		
		        	?>
		            <tr>
		                <td><?php echo $subscribersEmail['ID'] ?></td>
		                <td><?php echo $subscribersEmail['email'] ?></td>
		                <td><?php echo $subscribersEmail['subscribe_at'] ?></td>
		            </tr>
		        	<?php } ?>
		        </tbody>
		        <tfoot>
		            <tr>
		                <th>ID</th>
		                <th>Email</th>
		                <th>Date & Time</th>
		            </tr>
		        </tfoot>
		    </table>
		</div>
    <?php
}