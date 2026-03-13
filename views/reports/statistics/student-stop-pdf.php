<?php use yii\helpers\Url;
use app\models\StudentParentsInfo;
 ?> 
  <style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:8px;
    font-size:0.9em;
  }
  table{width: 100%}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style='text-align:center'>Stop Wise Students Report</h3>
        <table class="table table-bordered">
                        <thead>
                           <tr>
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
                                $group=$stopValue['group_id'];
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