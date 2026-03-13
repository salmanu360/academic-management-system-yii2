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
    <div id="getcalendarNoticeboard"></div>
  </div>
</div>
</div>
 <input type="hidden" data-url="<?= Url::to(['site/calendar-event'])?>" id="caledarurl">
 <?php   
$this->registerJs("$(document).ready(function() {

var url=$('#caledarurl').data('url');
 $.ajax
    ({
        type: \"POST\",
        dataType:\"JSON\",
        url: url,
        //data: string,
        success: function(data)
        {
           $('#getcalendarNoticeboard').html(data.cal);
           //alert('success');
        }
    });
    });

");
?>