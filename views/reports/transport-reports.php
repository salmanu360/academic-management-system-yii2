 <?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
use app\models\StudentInfo;
use kartik\date\DatePicker;
use app\models\RefClass;
use app\models\RefSection;
use app\models\RefGroup;
use app\models\StudentLeaveInfo;
use yii\widgets\ActiveForm;
use kartik\depdrop\DepDrop; 
use kartik\select2\Select2;
$this->title = 'Transport Reports';?>
  <div class="row">
        <div class="col-md-11">
        <a class="btn btn-block btn-social btn-facebook">
                <i class="fa fa-bar-chart-o"></i> Transport Reports
              </a>
        </div>
        </div>
<br>
<div class="row">
        <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Students Avail Tranport Facility</a></li>
              <li><a href="#tab_2" data-toggle="tab">All Transport Status</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
              <div class="row">
                <div class="col-md-10 col-sm-10">
                <?php 
                $transportStudentsCount=yii::$app->db->createCommand("select si.stu_id,concat (u.first_name,' ',u.middle_name,' ',u.last_name) as `student_name`,z.title as `zone_name`,r.title as `route_name`, s.title as `stop_name`,s.fare as `fare`,st.class_id as `class`,st.stu_id as `student_id`,rc.title as `class_name`,u.username as `username` from transport_allocation si
                    inner join user u on u.id=si.stu_id
                    inner join student_info st on st.user_id=si.stu_id
                    inner join ref_class rc on rc.class_id=st.class_id
                    inner join stop s on s.id=si.fk_stop_id
                    inner join route r on r.id=s.fk_route_id
                    inner join zone z on z.id=r.fk_zone_id where st.is_active=1 and si.branch_id='".yii::$app->common->getBranch()."'
                    ")->queryAll(); 
                ?>
                <table class="table table-striped">
                <thead>
                <tr>
                <th>Total Students: <?php echo count($transportStudentsCount); ?></th>
                </tr>
                </thead>
                </table>
              </div>
              <div class="col-md-2">
                <input type="submit" name="Generate Report" id="overallTransport" class="btn btn-primary" value="Generate Report" data-url=<?php echo Url::to(['reports/over-all-transport-pdf']) ?> />
              </div>
               </div>
               <div class="row">
                 <?php 
                 $transportStudents=yii::$app->db->createCommand("select si.stu_id,concat (u.first_name,' ',u.middle_name,' ',u.last_name) as `student_name`,z.title as `zone_name`,r.title as `route_name`, s.title as `stop_name`,s.fare as `fare`,st.class_id as `class`,st.stu_id as `student_id`,rc.title as `class_name`,u.username as `username` from transport_allocation si
                    inner join user u on u.id=si.stu_id
                    inner join student_info st on st.user_id=si.stu_id
                    inner join ref_class rc on rc.class_id=st.class_id
                    inner join stop s on s.id=si.fk_stop_id
                    inner join route r on r.id=s.fk_route_id
                    inner join zone z on z.id=r.fk_zone_id where st.is_active=1 and si.branch_id='".yii::$app->common->getBranch()."' ORDER BY u.username ASC
                    ")->queryAll(); 
                    ?>
                    <div class="col-sm-12 col-md-12">
                    <div class="table-responsive">
                      <table class="table table-striped">
                      <thead>
                      <tr>
                      <th >SR.</th>
                      <th>Reg No.</th>
                      <th>Student</th>
                      <th>Parent</th>
                      <th>Class</th>
                      <th>Zone</th>
                      <th>Route </th>
                      <th>Stop</th>
                      <th style="width: 77px;">Fare</th>
                      </tr>
                      </thead>
                      <tbody>
                      <?php 
                      $countStu=0;
                      $totalTransport=0;
                      foreach ($transportStudents as $transports) {
                        $countStu++;
                        $totalTransport=$totalTransport+$transports['fare'];
                       ?>
                      <tr>
                      <td><?= $countStu; ?></td>
                      <td><?= $transports['username'] ?></td>
                      <td><?= Yii::$app->common->getName($transports['stu_id']); ?></td>
                     <td> <?= Yii::$app->common->getParentName($transports['student_id']); ?></td>
                      <td> <?= $transports['class_name'] ?></td>
                      <td><?= $transports['zone_name'] ?></td>
                      <td><?= $transports['route_name'] ?></td>
                      <td><?= $transports['stop_name'] ?></td>
                      <td>Rs. <?= $transports['fare'] ?></td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td colspan="3"></td>
                        <th colspan="3">Total</th>
                        <th colspan="3">Rs. <?php echo $totalTransport ?></th>
                      </tr>
                      </tbody>
                      </table>
                    </div>
                    </div>
               </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
                <div class="showalltransport"></div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_3">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                It has survived not only five centuries, but also the leap into electronic typesetting,
                remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                like Aldus PageMaker including versions of Lorem Ipsum.
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
        <!-- /.col -->
      </div>
<input type="hidden" id="zone" data-url=<?php echo Url::to(['reports/get-zone-generic']) ?>>
<?php 
$script= <<< JS
$(document).ready(function() {
var url=$('#zone').data('url');
//alert(url);
$.ajax
        ({
            type: "POST",
            dataType:"JSON",
            //data: dataString,
            url: url,
            cache: false,
            success: function(html)
            {
              //console.log(html.zonegenric);
                $(".showalltransport").html(html.zonegenric);
            } 
        });
});

JS;
$this->registerJs($script);
?>