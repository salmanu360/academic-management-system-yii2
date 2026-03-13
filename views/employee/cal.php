<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\date\DatePicker;
$this->title = 'Attendance';
?>
<section class="invoice">
  <div class="row">
  <div class="col-md-3">
   <?php 
   echo '<label>Select Month:</label>';   
   echo DatePicker::widget([
    'name' => 'startdate', 
    'value' => date('Y-m'),
    'options' => ['placeholder' => ' ','id'=>'startdateReceivablee'],
    'pluginOptions' => [
      'autoclose' => true,
      'startView'=>'year',
      'minViewMode'=>'months',
      'format' => 'yyyy-mm',
      'startDate' => '-1m',
    ]
  ]);?>
</div>
<div class="col-md-1">
  <div style="height: 23px"></div>
  <input type="submit" id="dateReceivableReport" name="submits" class="btn btn-primary" data-url=<?php echo Url::to(['employee/date-attendance']) ?>  />
</div>
</div>
</section>
<div class="row">
  <div class="col-md-12" id="showDateReceivable"></div>
</div>