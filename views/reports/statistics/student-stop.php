<?php use yii\helpers\Url;
use app\models\StudentParentsInfo;
 ?>
  <style>
 .modal-lg{
    widht:88%;
 }
 </style>
 <div class="table-responsive"> 
<a href="<?= Url::to(['reports/getstudent-stopwise-pdf/','id'=>$stop_id])  ?>" name="Generate Report" class="btn btn-primary pull-right">Generate Report</a>

                  <table class="table table-bordered">
                        <thead>
                           <tr style="background: #3c8dbc">
                            <th>SR</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Parent Contact</th>
                            <th>Class</th>
                            <th>Zone</th>
                            <th>Route</th>
                            <th>Stop</th>
                            <th>Fee</th>
                            <th>Discount</th>
                            <th>Total</th>
                            </tr>
                        </thead>
                            <tbody>
                            <?php $count=0;foreach ($stopStudent as $stopValue) { $count++;?>
                            <tr>
                                <td><?= $count; ?></td>
                                <td><?= $stopValue['student_name'];?></td>
                                <td><?= $stopValue['parent_name'];?></td>
                                <td><?= $stopValue['father_contact'];?></td>
                                <td><?php 
                                $class=$stopValue['class_id'];
                                $group=($stopValue['group_id']?$stopValue['group_id']:'null');
                                $section=$stopValue['section_id'];
                                echo Yii::$app->common->getCGSName($class,$group,$section)?></td>
                                 <td><?= $stopValue['zone_name'];?></td>
                                 <td><?= $stopValue['route_name'];?></td>
                                 <td><?= $stopValue['stop_name'];?></td>
                                 <td><?= $stopValue['fare'];?></td>
                                 <td><?= ($stopValue['discount_amount']?$stopValue['discount_amount']:'0');?></td>
                                 <td><?= $stopValue['fare'] - $stopValue['discount_amount'];?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                     </table>
                 </div>