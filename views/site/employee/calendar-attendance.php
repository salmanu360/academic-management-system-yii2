<?php
use yii\helpers\Html;
use yii\helpers\Url;
$this->registerCssFile(Yii::getAlias('@web').'/css/fullcalendar/fullcalendar.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/fullcalendar/moment.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/fullcalendar/fullcalendar.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<div class="row">
          <div class="col-md-12">
            <div class="box box-success">
           <div id="getcalendarajax"></div>
           </div>
          </div>
      </div>
<input type="hidden" data-url="<?= Url::to(['employee/employee-calendar-event'])?>" id="caledarStudenturl" value="<?php echo $EmployeeInfo->emp_id ?>">
<?php   
$this->registerJs("$(document).ready(function() {
var url=$('#caledarStudenturl').data('url');
var id=$('#caledarStudenturl').val();
 $.ajax
    ({
        type: \"POST\",
        dataType:\"JSON\",
        url: url,
        data: {id:id},
        success: function(data)
        {
           $('#getcalendarajax').html(data.cal);
           //alert('success');
        }
    });
});
");
?>