<div class="table-reponsive">
<table class="table">
	<thead>
		<tr>
		<th>Name</th>
	</tr>
	</thead>
	<tbody>
		<tr>
		<td>
		<?php 
		
		foreach ($getStudntname as $getStudntname) {
			?>
		

			<?= $getStudntname->user->first_name . ' ' . $getStudntname->user->last_name?>
			<br />
				

			

			<?php } ?>
			</td>

		</tr>
	</tbody>
</table>
</div>