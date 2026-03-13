<?php use yii\helpers\Url;?>  
<style type="text/css">
  *{ margin-left:0; padding:0;}
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
<h3 style='text-align:center'> Total Fee Recieve From Class <?=yii::$app->common->getCGSName($classid,($groupid)?$groupid:null,$sectionid)?>
</h3>                       
                        <table class="table table-bordered">
                        <thead>
                           <tr style="background: #3c8dbc">
                            <th>SR</th>
                            <th>Student</th>
                            <th>Parent</th>
                            <th>Fee Head</th>
                            <th>Amount</th>
                            <th>Transport</th>
                            </tr>
                         </thead>
                            <tbody>
                            <?php 
                            $i=0;
                            $total=0;
                            $transportCount=0;
                            //echo '<pre>';print_r($studentArray);die;
                            foreach ($studentArray as $key=> $getFeeDetails) {
                            $i++;
                            $total=$total+$getFeeDetails['head_recv_amount'];
                            $transportCount=$transportCount+$getFeeDetails['transport_amount'];
                            $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$getFeeDetails['fee_head_id']])->one();
                           //$transport=$transport+$getFeeDetails->transport_amount;
                              ?>
                              <tr>
                                 <td><?= $i; ?></td>
                                 <td><?= yii::$app->common->getName($getFeeDetails['user_id'])  ?></td>
                                 <td><?= Yii::$app->common->getParentName($getFeeDetails['stu_id']);?></td>
                                 <td><?=strtoupper($getHead->title) ?> </td> 
                                 <td>Rs <?=$getFeeDetails['head_recv_amount'] ?> </td>
                                 <td>Rs <?=$getFeeDetails['transport_amount'] ?> </td>
                              </tr>
                            <?php } ?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>Rs <?=$total ?></th>
                                <td>Rs <?=$transportCount ?></th>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <th>Total Fee: Rs <?=$total+$transportCount ?></th>
                                <td></td>
                            </tr>
                            </tbody>
                     </table>