<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>

 <form action="">
  <div class="form-group">
    <label for="email">name:</label>
    <input type="text" class="form-control" id="email">
  </div>
  
  <div class="form-group">
        <?= Html::submitButton('sbmit', ['class' => 'btn btn-success']) ?>
    </div>
</form>
