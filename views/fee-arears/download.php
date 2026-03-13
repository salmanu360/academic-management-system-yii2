<?php ini_set('max_execution_time', 300); ?>
 <style type="text/css">
            *{ margin:0; padding:0;}
            th, tr, td  {
border:1px solid #469DC8;
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

        <h3 style='text-align:center'>Fee Arrears Listing</h3>

<div style="width: 100%; float:right background:none; font-size:13px;">
                <table class="table table-striped" style="background:none;" cellpadding="8">
                    <thead>
                    <tr> 
                        <th style="background:none;">S#&nbsp;&nbsp;</th>
                        <th style="background:none;">&nbsp;&nbsp;Reg No.</th>
                        <th style="background:none;">&nbsp;&nbsp;Student</th>
                        <th style="background:none;">&nbsp;&nbsp;Parent</th>
                        <th style="background:none;">&nbsp;&nbsp;Class</th>
                        <th style="background:none;">Section</th>
                        <th style="background:none;">Fee Head</th>
                        <th style="background:none;">Arrears</th>
                        <th style="background:none;">Date</th>
                    </tr>
                    </thead> 
                    <tbody>
                    <?php
                    
                    $i=0;
                    $total=0;
                    foreach ($arrears as $arrears){ 
            $student_id = \app\models\StudentInfo::find()->where(['stu_id'=>$arrears->stu_id])->one();
            $user_id = \app\models\User::find()->where(['id'=>$student_id->user_id])->one();
            $fee_heads = \app\models\FeeHead::find()->select(['title'])->where(['id'=>$arrears->fee_head_id])->one();
                    $i++;
                    $total=$total+$arrears->arears;
                    ?>
                                <tr style="background:none;">
                                <td style="background:none;"><?=$i?>&nbsp;&nbsp;</td>
                                <td style="background:none;">&nbsp;&nbsp;<?=(!empty($arrears->stu_id))?$user_id->username:'N/A'?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=(!empty($arrears->stu_id))?ucfirst(\Yii::$app->common->getName($user_id->id)):'N/A';?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=(!empty($arrears->stu_id))?ucfirst(\Yii::$app->common->getParentName($arrears->stu_id)):'N/A';?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=(!empty($arrears->stu_id))?$student_id->class->title:'N/A';?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=(!empty($arrears->stu_id))?$student_id->section->title:'N/A';?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=(!empty($fee_heads->title))?ucfirst($fee_heads->title):'N/A';?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=$arrears->arears?></td>
                                <td style="background:none;">&nbsp;&nbsp;<?=date('M Y',strtotime($arrears->date))?></td>
                                
                                </tr>
                        <?php }?>
                        <tr>
                            <td></td>        
                            <th>Total</th>        
                            <td></td>        
                            <td></td>        
                            <td></td>        
                            <td></td>       
                            <td></td>       
                            <td colspan="2">Rs : <?= $total; ?></td> 
                        </tr>
                    </tbody>
                </table> 
            </div>