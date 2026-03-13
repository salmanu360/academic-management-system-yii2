<?php 
use app\models\Noticeboard;
use yii\helpers\Url;
 ?>
 <div class="box box-solid bg-green-gradient">
            <div class="box-header">
              <i class="fa fa-calendar"></i>

              <h3 class="box-title">NoticeBoard</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <!-- button with a dropdown -->
                <div class="btn-group">
                  <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-bars"></i></button>
                    <?php if(Yii::$app->user->identity->fk_role_id == 1){ ?>
                  <ul class="dropdown-menu pull-right" role="menu">
                    <li><a href="<?= Url::to(['/noticeboard']); ?>">Add NoticeBoard</a></li>
                  </ul>
                  <?php } ?>
                </div>
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
  <script>
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
    eventClick: function(event){
         $('#modalTitle').html(event.title);
         $('#modalBody').html(event.description);
         $('#fullCalModal').modal();
     },
      events: [
      <?php $notices=Noticeboard::find()->all(); 
      foreach($notices as $row):

      ?>              
        {
          title: '<?= $row->title;?>',
          start: '<?= $row->date;?>',
          end: '<?php
      //$date = strtotime("+1 day");
       echo $row->end_date .'T24:00:00';

          ?>',
          color: '#257e4a'
        },
        <?php endforeach ?>
        
      ]
    });
    
  });

</script>

<div id="fullCalModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span> <span class="sr-only">close</span></button>
                <h4 id="modalTitle" class="modal-title"></h4>
            </div>
            <div id="modalBody" class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!-- <button class="btn btn-primary">Remove</button> -->
            </div>
        </div>
    </div>
</div> 