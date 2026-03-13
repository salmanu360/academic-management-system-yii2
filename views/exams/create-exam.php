<?php 
use yii\helpers\Html; 
$this->title = 'Create Exam';?> 
<section class="invoice" style="margin-top: -20px">
      <div class="row">
        <div class="col-xs-12">
        
<div class="filter_wrap content_col tabs grey-form">  
    <div class="form-center shade fee-gen">
        <div class="exam-form filters_head"> 
        <?= $this->render('_exam-form', [
            'model' => $model,
        ]) ?>
        </div>
        </div>
</div>
</div>
</section>
        <div  id="subject-details">
            <div id="subject-inner" class="create-exams"></div>
        </div> 
    
