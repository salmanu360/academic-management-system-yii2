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
<h3 style='text-align:center'>Student against Registration Number</h3>
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
            <th class="fullnameClassHeader"><?=Yii::t('app','Full Name')?></th>
            <?php } ?>
        

       <?php if($parntclass == 1){}else{?>
        
        <th><?=Yii::t('app','Parent Name')?></th>
        <?php }?>


        

        <?php
         if($inputclass == 1){?>
        
    
           <?php }else{?>
            <th><?=Yii::t('app','Class')?></th>
            <?php } ?>

       
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
    //echo '<pre>';print_r($dataproviders);die;
    foreach ($dataproviders as $data) {
        $sr++;
        ?>
      <tr>
      <td><?=$sr; ?></td>

      <?php if($regclass == 1){?>
         
    
            <?php }else{?>
            <td><?= $data->username; ?></td>
            <?php } ?>
            <td><?php  $student = StudentInfo::find()->where(['user_id'=>$data->id])->one();
            if(!empty($student->roll_no)){
              echo $student->roll_no;
            }else{
              echo 'N/A';
            }
             ?></td>
        

      <?php if($thisinputname == 1){?>
        
    
           <?php }else{?>
           <td class="fullnameClass">

        <?php echo Yii::$app->common->getName($data->id); ?>
            
        </td>
            <?php } ?>


        

       <?php if($parntclass == 1){?>
        
    
           <?php }else{?>
            <td>
        <?php
          $student = StudentInfo::find()->where(['user_id'=>$data->id])->one();
                     echo Yii::$app->common->getParentName($student['stu_id']); ?>
                    
       </td>
    
            <?php } ?>


        

          

          <?php if($inputclass == 1){?>
        
    
           <?php }else{?>
           
           <td><?php
          $cls = StudentInfo::find()->where(['user_id'=>$data->id])->one();
          if(empty($cls)){
            echo "N/A";
          }else{
            echo $cls->class->title;
          }
          ?></td>
            <?php } ?>
  


        


           <?php if($grpclass == 1){?>
        
    
           <?php }else{?>
           <td><?php
         $grp = StudentInfo::find()->where(['user_id'=>$data->id])->one();
         if(empty($grp)){
          echo "N/A";
          }else{
            echo $grp->group['title'];
          }


          ?></td>
            <?php } ?>


            <?php if($sectinclass == 1){?>
        
    
           <?php }else{?>
           
           <td><?php 
             $sctn = StudentInfo::find()->where(['user_id'=>$data->id])->one();
             if(empty($sctn)){

              }else{
                echo $sctn->section->title;
              }

             ?>
               
             </td>
            <?php } ?>
        
        

  <?php if($classcntct == 1){?>
        
    
           <?php }else{?>
           <td><?php
          // echo $data['id'];
        $student = StudentInfo::find()->where(['user_id'=>$data->id])->one();
        if(count($student)>0){
        $name = StudentParentsInfo::find()->where(['stu_id'=>$student->stu_id])->one();

        }
        if(empty($name->contact_no)){
          echo "N/A";
        }else{
         echo $name->contact_no; 
        }  ?></td>
            <?php } ?>

        


            <?php if($dobclass == 1){?>
        
    
           <?php }else{?>
            <td><?php
        $dob = StudentInfo::find()->where(['user_id'=>$data->id])->one();
        if(empty($dob)){

              }else{
                echo date('d M,Y',strtotime($dob->dob));
              }

          ?></td>
            <?php } ?>

        

        

        <?php if($adrsclass == 1){?>
        
    
           <?php }else{?>
            <td><?php

            $locatn1 = StudentInfo::find()->where(['user_id'=>$data->id])->one();
            if(empty($locatn1)){
              echo "N/A";
              }else{
              echo $locatn1->location1;
              }
           
      
                ?></td>
            <?php } ?>


      </tr>
      <?php } ?>
      
    </tbody>
  </table>
