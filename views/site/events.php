<?php 
use app\models\Events;
use yii\helpers\Url;
$this->registerCssFile(Yii::getAlias('@web').'/css/fullcalendar/fullcalendar.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/fullcalendar/moment.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/fullcalendar/fullcalendar.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
 ?>
 <div class="education-main-content"> 
 	 <div class="education-main-section">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
 <div class="box box-solid bg-green-gradient">
            <div class="box-header">
              <i class="fa fa-calendar"></i>

              <h3 class="box-title">Events</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <!-- button with a dropdown -->
                
                <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
                </button>
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <!--The calendar -->
              <div id="calendar" style="width: 100%;color: #3c8dbc;background: white;border: 1px solid;"></div>
            </div>
            
  
</div>
</div>
</div>
</div>
</div>
</div>
<?php $script= <<< JS
$(document).ready(function(){
        var calendar = $("#calendar").fullCalendar({  
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listWeek'
            },
            navLinks: true, 
            editable: true,
            eventLimit: true, 
            events: [
                {
                    title  : 'event1',
                    start  : '2018-07-01'
                },
                {
                    title  : 'event2',
                    start  : '2018-07-03',
                    end    : '2018-07-05'
                },
                {
                    title  : 'event3',
                    start  : '2018-07-09T12:30:00',
                    allDay : false // will make the time show
                }
            ],  // request to load current events
           
        });
        
     });

JS;
$this->registerJs($script);?>

