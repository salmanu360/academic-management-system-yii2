<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title='Failed SMS';
?>
<div class="row">
  <div class="col-md-12">
    <strong style="color:red">Today Ledger</strong>
    <a style="margin-left: 5px;" class="btn btn-danger pull-right" href="<?php echo Url::to(['sms-log']) ?>">Back</a>
  </div></div>
<div class="panel panel-body">
        <div class="table-responsive">
          <?php $form = ActiveForm::begin(['action'=>'today-fail-sms-send']); ?>
          <table class="table table-striped">
            <thead>
              <tr style="background-color: #5fde69c7">
                <td>Sr.</td>
                <td>Phone</td>
                <td>SMS</td>
                <td>Status</td>
              </tr>
            </thead>
            <tbody>
              <?php 
              $i=0;
              foreach ($smsLog as $key => $smsLogvalue) {
              $studentDetails=Yii::$app->common->getStudent($smsLogvalue->fk_user_id);
              $i++; ?>
              <tr>
                <td><?php echo $i ?></td>
                <td><?php echo $smsLogvalue->receiver_no ?> </td>
                  <input type="hidden" name="id[]" value="<?php echo $smsLogvalue->id?>">
                <td><?php echo $smsLogvalue->SMS_body ?>
                </td>
                <td><?php echo $smsLogvalue->status ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <div class="form-group">
          <input type="submit" class="btn btn-success" value="Send Failed SMS">
      </div>
          <?php ActiveForm::end(); ?>
        </div>
        </div>