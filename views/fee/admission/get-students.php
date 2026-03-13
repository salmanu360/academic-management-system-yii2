<section class="content">
	<table>
	<thead>
		<tr>
			<td>Student</td>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($query as $key => $value) {
			/*$getFeeDetails = \app\models\FeeGroup::find()
        ->where([
         'fk_branch_id'  =>Yii::$app->common->getBranch(),
         'fk_class_id'   => $class_id,
         'fk_fee_head_id'=>$fee_head->id
         ])->all();*/
			?>
		<tr>
			<td><?php echo $fee_head->title .'-'.$value->stu_id ?></td>
		</tr>
	<?php } ?>
	</tbody>
</table>
</section>