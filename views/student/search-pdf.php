<style type="text/css">
  *{ margin:0; padding:0;}
  th, tr, td  {
    border:0.3px solid black;
    padding:5px;
    font-size:0.9em;
  }

  tr:nth-child(even){background-color: #f2f2f2}
  #first{ min-width: 15px; width: 15px; }
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
  </h2>
</div>
<h4 align="center">Detail against <?php echo $data?></h4>
<table class="table table-stripped">
    <thead>
        <tr class="info">
            <td>Sr.</td>
            <td>Reg. NO.</td>
            <td>Roll #</td>
            <td>Name</td>
            <td>Father</td>
            <td>Parent Contact</td>
            <td>Parent CNIC</td>
            <td>Class</td>
            <td>Session</td>
            <td>DOB</td>
            <td>Address</td>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=0; 
        foreach ($userDetails as $key => $userDetailsvalue) {
        $sessionDetails=\app\models\RefSession::find()->where(['session_id'=>$userDetailsvalue['session_id']])->one();
            $i++;
        if($userDetailsvalue['is_active'] == $status){
            ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $userDetailsvalue['username'] ?></td>
            <td><?php echo $userDetailsvalue['roll_no'] ?></td>
            <td><?php echo Yii::$app->common->getName($userDetailsvalue['user_id']) ?></td>
            <td><?php echo Yii::$app->common->getParentName($userDetailsvalue['stu_id']) ?></td>
            <td><?php echo $userDetailsvalue['contact_no'] ?></td>
            <td><?php echo $userDetailsvalue['cnic'] ?></td>
            <td><?php echo Yii::$app->common->getCGSName($userDetailsvalue['class_id'],$userDetailsvalue['group_id'],$userDetailsvalue['section_id']) ?></td>
            <td><?php echo $sessionDetails['title'] ?></td>
            <td><?php echo $userDetailsvalue['dob'] ?></td>
            <td><?php echo $userDetailsvalue['location1'] ?></td>
            
        </tr>
        <?php }else{echo '<div> No details found</div>';} } ?>
    </tbody>
</table> 