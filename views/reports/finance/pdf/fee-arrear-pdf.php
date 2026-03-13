<?php use yii\helpers\Url;
use app\models\StudentParentsInfo;
?> 
<style type="text/css">
*{ margin-left:0; padding:0;}
th, tr, td  {
    border:1px solid black;
    padding:7px;
    font-size:0.8em;
}
</style>
<div style="width: 100%; text-align: center; background-color: #337ab7; color: #000; font-size:14px;">
  <h2 style="font-size:16px; font-weight:700; color:#000000; text-transform:capitalize;margin: 0;padding: 15px 0 8px 0;">
    <?=Yii::$app->common->getBranchDetail()->address?>
</h2>
</div>
<h3 style='text-align:center'>Fee Arrears of  <?= yii::$app->common->getName($stu_id)  ?> <?= ($studentTable->gender_type== '0')? 'D/O' : 'S/O' ?> <?= Yii::$app->common->getParentName($studentTable->stu_id);?>
</h3>
<table class="table table-bordered" width="100%">
                        <thead>
                           <tr style="background: #3c8dbc">
                            <th>SR</th>
                            <th>Fee Head</th>
                            <th>Arrears Amount</th>
                            <th>Date</th>
                            </tr>
                         </thead>
                            <tbody>
                            <?php 
                            $i=1;
                            $total=0;
                            $trasport_total=0;
                            $transport_hstl_arrears = \app\models\FeeSubmission::find()->where(['stu_id'=>$studentTable->stu_id,'fee_status'=>1])->one();
                            foreach ($arrearsDetails as $key=> $arrearsDetailsValue) {
                              $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$arrearsDetailsValue->fee_head_id])->one();
                                $total=$total+$arrearsDetailsValue->arears;?>
                              <tr>
                                 <td><?= $i; ?></td>
                                 <td><?=strtoupper($getHead->title) ?> </td> 
                                 <td>Rs <?=$arrearsDetailsValue->arears ?> </td>
                                 <td><?= date('d M Y',strtotime($arrearsDetailsValue->date)) ?> </td>
                              </tr>
                            <?php $i++;} ?>
                            <tr>
                                <td></td>
                                <th>Total</th>
                                <th colspan="2"> Rs <?=$total ?> <?= (empty($transport_hstl_arrears->transport_arrears)?'':'. Transport Arrears: Rs. '.$transport_hstl_arrears->transport_arrears) ?></th>
                            </tr>
                            <tr>
                                <td></td>
                                <th>Grand Total</th>
                                <th colspan="2"> Rs <?=$total+$transport_hstl_arrears->transport_arrears ?></th>
                            </tr>
                            </tbody>
                     </table>