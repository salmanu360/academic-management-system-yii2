<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->registerCssFile(Yii::getAlias('@web').'/js/scale_files/fullcalendar.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::getAlias('@web').'/js/scale_files/fullcalendar.print.min.css',['media'=>'print','depends' => [yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::getAlias('@web').'/js/scale_files/scheduler.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/scale_files/moment.min.js.download',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/scale_files/jquery.min.js.download',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/scale_files/fullcalendar.min.js.download',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/scale_files/scheduler.min.js.download',['depends' => [yii\web\JqueryAsset::className()]]);
?>
<style>
.fc-license-message{display: none;}

  body {
    margin: 0;
    padding: 0;
    font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
    font-size: 14px;
  }

  #calendar {
    /*max-width: 100%;
    margin: 50px auto;*/
    background-color: white;
  }
/*table td th{
  padding: 17px;
  line-height: 5px;
        }*/
        
</style>
<?php
$date=date('Y-m-d');
$this->registerJs("
    $('#calendar').fullCalendar({
      now: '$date',
      editable: true,
      aspectRatio: 1.8,
      scrollTime: '00:00',
      header: {
        left: 'today prev,next',
        center: 'title',
        right: 'timelineDay,timelineTenDay,timelineMonth'
      },
      defaultView: 'timelineMonth',
      views: {
        timelineDay: {
          buttonText: ':15 slots',
          slotDuration: '00:15'
        },
        timelineTenDay: {
          type: 'timeline',
          duration: { days: 10 }
        }
      },
      navLinks: true,
      resourceAreaWidth: '15%',
      resourceLabelText: 'Students',
      resources:$stuList,
      events: $stu_attendance,
    });",\yii\web\View::POS_READY);
?>


<body class="">

  <div id="calendar" class="fc fc-unthemed fc-ltr">


  </div>



</body>