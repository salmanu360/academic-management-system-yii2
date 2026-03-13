<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use app\widgets\Alert;
use yii\widgets\Pjax;
use yii\widgets\LinkPager;
use app\models\StudentInfo;
use app\models\StudentParentsInfo;
?>
<style type="text/css">
  table{

 width:1800px;

}
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
<h3 style='text-align:center'>Students in Class(<?=$classname->title ?>)</h3>

<table class="table table-bordered">
    <thead>
      <tr>
      <th>Sr</th>
      <?php if($regclass == 1){?>
           <?php }else{?>
         <th>Reg. No.</th>
            <?php } ?>
            <th>Class Roll No</th>
        <?php if($thisinputname == 1){?>
           <?php }else{?>
            <th class="fullnameClassHeader">Full Name</th>
            <?php } ?>
       <?php if($parntclass == 1){}else{?>
        <th>Parent Name</th>
        <?php }?>
      <?php if($grpclass == 1){?>
          <?php }else{?>
            <th><?=Yii::t('app','Group')?></th>
            <?php } ?>
        <?php if($sectinclass == 1){?>
           <?php }else{?>
            <th><?=Yii::t('app','Section')?></th>
            <?php } ?>
    <?php if($classcntct == 1){?>
           <?php }else{?>
            <th><?=Yii::t('app','Parent Contact')?></th>
            <?php } ?>
         <?php if($dobclass == 1){?>
           <?php }else{?>
            <th><?=Yii::t('app','DOB')?></th>
            <?php } ?>
        <?php if($adrsclass == 1){?>
           <?php }else{?>
            <th><?=Yii::t('app','Address')?></th>
            <?php } ?>
      </tr>
    </thead>
    <tbody>
    <?php 
    $sr=0;
    foreach ($dataprovider as $data) {
        $sr++;
        ?>
      <tr>
      <td><?=$sr; ?></td>
      <?php if($regclass == 1){?>
            <?php }else{?>
            <td><?= $data->user->username; ?></td>
            <?php } ?>
            <td><?php echo (!empty($data->roll_no))? $data->roll_no :'N/A'; ?></td>
      <?php if($thisinputname == 1){?>
           <?php }else{?>
           <td class="fullnameClass">

        <?php echo Yii::$app->common->getName($data->user_id); ?>
            
        </td>
            <?php } ?>
       <?php if($parntclass == 1){?>
           <?php }else{?>
            <td>
          <?php echo Yii::$app->common->getParentName($data->stu_id); ?>         
       </td>
            <?php } ?>
           <td>
          <?php if($grpclass == 1){?>
           <?php }else{?>
           <?php if(!empty($data->group->title)){echo $data->group->title;}else{echo "N/A";} ?>
            <?php } ?> 
            </td> 
          <?php if($sectinclass == 1){?>
           <?php }else{?>
           <td>
          <?= $data->section->title; ?> 
            </td>
            <?php } ?>
        <?php if($classcntct == 1){?> 
        <?php }else{?>
           <td> 
            <?php 
        $name = StudentParentsInfo::find()->where(['stu_id'=>$data->stu_id])->one();
        echo $name->contact_no;
         ?>
         </td>
          <?php } ?>
<?php if($dobclass == 1){?>
           <?php }else{?>
            <td>
             
            <?= date('d M,Y',strtotime($data->dob)); ?>
            </td>
            <?php } ?>
        
        <?php if($adrsclass == 1){?>
           <?php }else{?>
            <td><?php if($data->location1){
                echo $data->location1;
            }else{
                echo 'N/A';
                } ?></td>
            <?php } ?>
      </tr>
      <?php } ?>
    </tbody>
  </table>
