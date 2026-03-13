 <?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;?>
 <?php $form = ActiveForm::begin(['action'=>'roll-no-pdf']); ?> 
 <div class="row">
 	<div class="col-sm-12 col-md-12">
 <section class="invoice">  
 <div class="table-responsive">
<table class="table table-striped">
	<thead>
	<tr style="background: #bcbeca">
	<td>SR.</td>
	<td>Reg. NO.</td>
	<td>Roll No.</td>
	<td>Name</td>
	<td>Father Name</td>
	<td>Class</td>
	<td>Print</td>
	</tr>
	</thead>
<?php 
$i=1;
foreach ($getStudents as $key => $studentsValue) {?>
  <tbody>
<tr>
	<td><?=$i ?></td>
	<td><?=$studentsValue->user_id ?>
		<?= $form->field($model, 'stu_id[' . $key . ']')->textInput(['value' => $studentsValue->stu_id])->label(false); ?>
	</td>
	<td><?=$studentsValue->roll_no ?></td>
	<td><?=$studentsValue->user_id ?></td>
	<td><?=$studentsValue->user_id ?></td>
	<td><?=$studentsValue->class_id ?></td>
	<td>
		<!-- <input type="checkbox" value='1' checked="checked"> -->
	<?= $form->field($model, 'is_active[' . $studentsValue['stu_id'] . ']')->checkbox()->label(false); ?>
	</td>
</tr>
</tbody>
<?php 
$i++;
}?>
</table>
<div class="form-group">
	<button class='btn btn-success'>Print Slips</button>
                <!-- <?//= Html::button('Print Slip', ['class' => 'btn btn-success']) ?> -->
            </div>
</section>
</div>
 	</div>
 </div>
  			
 <?php ActiveForm::end(); ?>