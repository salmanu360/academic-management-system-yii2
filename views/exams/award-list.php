<?php
use yii\helpers\Html;
$this->title = 'Award List';
?> 
<section class="invoice" style="margin-top: -17px">
      <div class="row">
        
        <div class="exam-form filters_head"> 
        <?= $this->render('_award-form', [
            'model' => $model,
        ]) ?>
        </div>
      </div>
      </section>
       <div  id="subject-details">
            <div id="subject-inner"></div>
        </div> 