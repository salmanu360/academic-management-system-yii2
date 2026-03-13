<?php
 use yii\helpers\Url;
 if (Yii::$app->session->hasFlash('success')): ?>
<div class="alert alert-success">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <?= Yii::$app->session->getFlash('success') ?>
</div>
<?php endif;?>
<a class="btn btn-danger pull-right" href="<?php echo Url::to(['index']) ?>">Back</a>