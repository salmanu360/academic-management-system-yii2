<?php use yii\helpers\Url;?>                        
                        <a href="<?=Url::to(['reports/class-fee-pdf/','id'=>$sectionid,'gid'=>$groupid,'cid'=>$classid]) ?>" name="Generate Report" class="btn btn-primary pull-right">Generate Report</a>
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
                                <th>Total</th>
                                <td></td>
                                <td></td>
                                <th>Rs <?=$total ?></th>
                                <th>Rs <?=$transportCount ?></th>
                            </tr>
                            <tr>
                                <td></td>
                                <th>Grand Total</th>
                                <td></td>
                                <td></td>
                                <th> Rs <?=$total+$transportCount ?></th>
                            </tr>
                            </tbody>
                     </table>