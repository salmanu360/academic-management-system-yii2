<?php use yii\helpers\Url;?>     
<a href="<?=Url::to(['reports/class-arrears-pdf/','id'=>$sectionid,'gid'=>$groupid,'cid'=>$classid]) ?>" name="Generate Report" class="btn btn-primary pull-right">Generate Report</a>
<table class="table table-bordered">
    <thead>
       <tr style="background: #3c8dbc">
        <th>Sr.</th>
        <th>Student</th>
        <th>Father Name</th>
        <th>Transport Arrears</th>
        <th colspan="2">Arrears</th>
        <th>Total</th>
        
    </tr>
</thead>
<tbody>
    <?php 
    $i=1;
    $totals=0;
    $transport=0;

    foreach ($studentArray as $key=> $studentDetails) {
        $TransportHostelArrears=\app\models\FeeSubmission::find()->where(['stu_id'=>$studentDetails['stu_id'],'branch_id'=>yii::$app->common->getBranch(),'fee_status'=>1])->andWhere(['>','transport_arrears',0])->one();    
        $feeArrears=\app\models\FeeArears::find()->where(['stu_id'=>$studentDetails['stu_id'],'branch_id'=>yii::$app->common->getBranch(),'status'=>1])->all();
        if(count($feeArrears) == 0 && count($TransportHostelArrears) == 0){
          continue;
      }
      
            $transport=$transport+$TransportHostelArrears['transport_arrears'];
            ?>
            <tr>
             <td><?= $i; ?></td>
             <td><?= yii::$app->common->getName($studentDetails['user_id'])  ?></td>
             <td><?= Yii::$app->common->getParentName($studentDetails['stu_id']);?></td>
             <td><?php echo $TransportHostelArrears['transport_arrears']; ?></td>
             <td colspan="2">
                <?php 
                $total=0;
                foreach ($feeArrears as $key => $getArrears) {
                    $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$getArrears['fee_head_id']])->one();
                    $total= $total+$getArrears['arears'];
                    $totals= $totals+$getArrears['arears'];
                    ?>
                    <?=strtoupper($getHead['title']) .' Rs. '.$getArrears['arears'] .' , '; ?>
                <?php  } ?>
            </td>
            <td><strong> Rs. <?php echo $total + $TransportHostelArrears['transport_arrears'] ?> </strong></td>
        </tr>
        <?php $i++; } ?>
        <tr>
         <th colspan="2">Grand Total : </th>
         <th colspan="5">Rs. <?php echo $totals + $transport?></th>
     </tr>
 </tbody>
</table>