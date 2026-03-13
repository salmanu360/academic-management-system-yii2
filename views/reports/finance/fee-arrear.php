<?php use yii\helpers\Url;
use app\models\StudentParentsInfo;
 ?> 
 <a href="<?= Url::to(['reports/student-arrear-pdf/','id'=>$stu_id])?>" name="Generate Report" class="btn btn-primary pull-right">Generate Report</a>
 <h5><?= yii::$app->common->getName($stu_id)  ?> <?= ($studentTable->gender_type== '0')? 'D/O' : 'S/O' ?> <?= Yii::$app->common->getParentName($studentTable->stu_id);?></h5>
                  <table class="table table-bordered">
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
                                <th> Rs <?=$total ?> <?= (empty($transport_hstl_arrears->transport_arrears)?'':'. Transport Arrears: Rs. '.$transport_hstl_arrears->transport_arrears) ?></th>
                            </tr>
                            <tr>
                                <td></td>
                                <th>Grand Total</th>
                                <th> Rs <?=$total+$transport_hstl_arrears->transport_arrears ?></th>
                            </tr>
                            </tbody>
                     </table>