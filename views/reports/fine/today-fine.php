<?php 
use yii\helpers\Url;
use kartik\date\DatePicker;
$this->title = 'Fine Reports';
?>
<div class="row">
	<div class="col-md-11">
		<a class="btn btn-block btn-social btn-facebook">
			<i class="fa fa-bar-chart-o"></i> Fine Reports
		</a>
	</div>
</div><br>
<div class="row">
	<div class="col-md-11">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#tab_1" data-toggle="tab">Today Fine</a></li>
				<li><a href="#tab_2" data-toggle="tab">Current Month Fine</a></li>
				<li><a href="#tab_3" data-toggle="tab">Date Fine</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1">
					<div class="table-responsive">
	<?php if(count($fine)>0){ ?>
<a href="<?= Url::to(['today-fine-pdf']) ?>" class="btn btn-primary btn-sm pull-right">
              <i class="fa fa-download"> Generate Report</i></a>
<table class="table table-stripped">
	<thead>
		<tr>
			<th>Sr.</th>
			<th>Reg. No.</th>
			<th>Student</th>
			<th>Father</th>
			<th>Class</th>
			<th>Fine Type</th>
			<th>Fine</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$total=0;
		$i=0;foreach ($fine as $key => $value) {
			$total=$total+$value->payment_received;
			$i++;?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo Yii::$app->common->getUserName($value->fk_stu_id) ?></td>
			<td><?php echo Yii::$app->common->getName($value->fk_stu_id) ?></td>
			<td><?php echo Yii::$app->common->getParentName($value->fkStudent->stu_id) ?></td>
			<td><?php echo Yii::$app->common->getCGSName($value->fkStudent->class_id,$value->fkStudent->group_id,$value->fkStudent->section_id) ?></td>
			<td><?php echo $value->fineType->title ?></td>
			<td>Rs. <?php echo $value->payment_received ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th colspan="3"></th>
			<th colspan="2">Total</th>
			<th colspan="2">Rs. <?php echo $total ?></th>
		</tr>
	</tbody>
</table>
<?php }else{ 
echo '<div class="alert alert-danger">No Record Found..!</div>';
}?>
</div>
				</div>

				<!-- start of tab2 and end of tab 1 -->
<div class="tab-pane" id="tab_2">
<?php if(count($currentMonth)>0){ ?>
<a href="<?= Url::to(['currentmonth-fine-pdf']) ?>" class="btn btn-primary btn-sm pull-right">
<i class="fa fa-download"> Generate Report</i></a>
<table class="table table-stripped">
	<thead>
		<tr>
			<th>Sr.</th>
			<th>Reg. No.</th>
			<th>Student</th>
			<th>Father</th>
			<th>Class</th>
			<th>Fine Type</th>
			<th>Fine</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$total=0;
		$i=0;foreach ($currentMonth as $key => $monthCurrent) {
			$total=$total+$monthCurrent->payment_received;
			$i++;?>
		<tr>
			<td><?php echo $i; ?></td>
			<td><?php echo Yii::$app->common->getUserName($monthCurrent->fk_stu_id) ?></td>
			<td><?php echo Yii::$app->common->getName($monthCurrent->fk_stu_id) ?></td>
			<td><?php echo Yii::$app->common->getParentName($monthCurrent->fkStudent->stu_id) ?></td>
			<td><?php echo Yii::$app->common->getCGSName($monthCurrent->fkStudent->class_id,$monthCurrent->fkStudent->group_id,$monthCurrent->fkStudent->section_id) ?></td>
			<td><?php echo $monthCurrent->fineType->title ?></td>
			<td>Rs. <?php echo $monthCurrent->payment_received ?></td>
		</tr>
		<?php } ?>
		<tr>
			<th colspan="3"></th>
			<th colspan="2">Total</th>
			<th colspan="2">Rs. <?php echo $total ?></th>
		</tr>
	</tbody>
</table>
<?php }else{ 
echo '<div class="alert alert-danger">No Record Found..!</div>';
}?>
				</div>
				<!-- start of tab3 and end of tab 2 -->
				<div class="tab-pane" id="tab_3">
				<div class="row">
               <div class="col-md-3">
                 <?php 
                    echo '<label>Start Date:</label>';
                    echo DatePicker::widget([
                    'name' => 'startdate', 
                    'value' => date('01-m-Y'),
                    'options' => ['placeholder' => ' ','id'=>'startdateExpense'],
                    'pluginOptions' => [
                        'format' => 'dd-m-yyyy',
                        'todayHighlight' => true,
                        'autoclose'=>true,
                    ]
                  ]);?>
               </div>
               <div class="col-md-3">
                 <?php echo '<label>End Date:</label>';
                        echo DatePicker::widget([
                        'name' => 'enddate', 
                        'value' => date('d-m-Y'),
                        'options' => ['placeholder' => ' ','id'=>'enddateExpense'],
                        'pluginOptions' => [
                            'format' => 'dd-m-yyyy',
                            'todayHighlight' => true,
                            'autoclose'=>true,
                        ]
                      ]); ?>
               </div>
               <div class="col-md-1">
               <div style="height: 23px"></div>
                 <input type="submit" id="dateExpenseReport" name="submits" class="btn btn-primary" data-url=<?php echo Url::to(['fine-date-report']) ?>  />
               </div>
             </div>
             <br />
             <div class="row">
             <div class="col-md-12" id="showDateExpense"></div>
               
             </div>
				</div>
			</div>
		</div>
	</div>
</div>
