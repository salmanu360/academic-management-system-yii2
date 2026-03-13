<?php 
  use yii\helpers\Url;
  use app\models\RefClass;
 
 ?>     
 <style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:1px solid black;
    padding:10px;
    font-size:1.5em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h3 style='text-align:center'>SLC Report of <?=$year ?></h3> 
                  <table class="table table-striped">
                          <thead>
                          <tr>
                            <th>SR</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Class</th>
                            <th>Group</th>
                            <th>Section</th>
                            <th>Remarks</th>
                            <th>Next School</th>
                            <th>Date</th>
                            
                          </tr>
                           </thead>
                           <tbody>
                            <?php
                            $count=0;
                           // echo '<pre>';print_r($year);die;
                             foreach ($leaveInfo as $leaveInfo) {
                              $count++;

                              ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $leaveInfo->stu->user->first_name .' '.$leaveInfo->stu->user->last_name ?></td>
                                <td><?= Yii::$app->common->getParentName($leaveInfo->stu_id);?></td>
                                
                                <td><?php echo $leaveInfo->class->title?></td>
                                <td><?php if($leaveInfo->group_id == ''){echo "N/A"; }else{echo $leaveInfo->group->title;}?></td>
                  <td><?php echo $leaveInfo->section->title?></td>
                  <td><?php echo $leaveInfo->remarks?></td>
                  <td><?php echo $leaveInfo->next_school?></td>
                  <td><?php echo date('d-m-Y',strtotime($leaveInfo->created_date))?></td>
                                
                            </tr>
                            <?php } ?>
                            </tbody>
                     </table>
                    