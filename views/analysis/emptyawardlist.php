<style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:0.3px solid black;
    padding:5px;
    font-size:0.7em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
  #first{ min-width: 15px; width: 15px; }
</style>
  <h2 style="text-align: center;padding-top: -50px; font-weight:700; color:#000000; text-transform:capitalize;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
<h3 style='text-align:center;color: black;padding-top: -10px'>Award List of Class <?php echo Yii::$app->common->getCGSName($class_val,$groupval,$sectionval) ?></h3>
<div style="margin-left:10px;padding-top: -20px">
   <p><span>Total Marks: __________________ </span>&nbsp;&nbsp;&nbsp;&nbsp;<span> </span>&nbsp;&nbsp;&nbsp;&nbsp;<span>Passing Marks: ___________________ </span> &nbsp;&nbsp;&nbsp;<span>Date: _______________ </span></p>
    <p style="padding-top: -10px"><span>Subject: ______________________ </span>&nbsp;&nbsp;&nbsp;&nbsp;<span> </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Teacher: __________________________ </span> &nbsp;&nbsp;&nbsp;<span> </span></p>
    
</div>
<table class="table table-striped" width="100%" style="padding-top: -10px">
              <thead>
                <tr class="info">
                  <th>SR.</th>
                  <th>Roll No.</th>
                  <th>Reg. No.</th>
                  <th>Name</th>
                  <th>Father Name</th>    
                  <th>Marks</th>   
                  <th>Remarks</th> 
                </tr>
              </thead>
              <tbody>
                <?php
                $i=0; 
                foreach ($studentDetails as $studentValue) {
                  $i++;
                  ?>
                   <tr>
                    <td><?=$i ?></td>
                   <td id="first"><?= $studentValue->roll_no ?></td>
                   <td><?php echo Yii::$app->common->getUserName($studentValue->user_id)?></td>
                   <td><?php echo Yii::$app->common->getName($studentValue->user_id)?></td>
                   <td><?php echo Yii::$app->common->getParentName($studentValue->stu_id)?></td>
                   <td></td>
                   <td></td>
                  </tr>
                  <?php } ?> 
                </tbody>
              </table>