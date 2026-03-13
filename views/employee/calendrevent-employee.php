<?php 
use app\models\Noticeboard;
use yii\helpers\Url;
use app\models\EmployeeAttendance;
$this->registerCssFile(Yii::getAlias('@web').'/css/fullcalendar/fullcalendar.min.css',['depends' => [yii\web\JqueryAsset::className()]]);
 ?>
 <div class="box box-solid bg-green-gradient">
            <div class="box-header">
              <i class="fa fa-calendar"></i>

              <h3 class="box-title">ATTENDANCE CALENDAR</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
               
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


<script type="text/javascript">
$(document).ready(function() {

    $('#calendar').fullCalendar({
      
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listMonth'
      },
    editable: false,
    firstDay: 1,
    height: 530,
    droppable: false,
    events: [
      <?php $attendance =EmployeeAttendance::find()->where(['fk_empl_id'=>$id])->all();
               foreach ($attendance as $attend) {?>
                
                {
                 title: '<?= $attend->leave_type;?>',
                 start: '<?= date("Y-m-d",strtotime($attend->date));?>',
                 <?php if($attend->leave_type == 'absent'){ ?>
                 color: ' red'
                 <?php }elseif ($attend->leave_type == 'leave') {?>
                   color: ' #f39c12'
                 <?php }elseif ($attend->leave_type == 'shortleave') {?>
                   color: '#00c0ef'
                 <?php } elseif ($attend->leave_type == 'present') {?>
                   color:'#00a65a'
                <?php } elseif ($attend->leave_type == 'late') {?>
                 color:'#d28747'
                <?php }?>
                 },
      <?php }?>
        
      ]
    });
    
  });
</script>