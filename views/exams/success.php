<?php
use yii\helpers\Url;
 if (Yii::$app->session->hasFlash('success')): ?>
           <div class="alert alert-success">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <?= Yii::$app->session->getFlash('success') ?>
           </div>
            <?php endif;?>
<a href="<?php echo Url::to(['sms-class'])?>" class="btn btn-warning">Go back</a>