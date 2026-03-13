<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title='SMS Log';
/* @var $this yii\web\View */
/* @var $model app\models\RefReligion */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
  <div class="col-md-12">
    <a class="btn btn-block btn-social btn-facebook">
      <i class="fa fa-bar-chart-o"></i> SMS Log Reports 
    </a>
  </div>
</div>
<br>
<div class="row">
  <div class="col-md-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_1" data-toggle="tab">Last 30 days sms</a></li>
        <li><a href="<?php echo Url::to(['failed-sms']) ?>">Today Failed SMS Report / Try Again</a></li>
      </ul>
      <div class="tab-content">
        
        <div class="tab-pane active" id="tab_1">
          <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr style="background-color: #5fde69c7">
                <td>Sr.</td>
                <!-- <td>Name</td>
                <td>Parent</td> -->
                <td>Phone</td>
                <td>SMS</td>
                <td>Date</td>
                <td>Status</td>
              </tr>
            </thead>
            <tbody>
              <?php 
              $i=0;
              foreach ($lastThiryDaysSms as $key => $getSms) {
              $studentDetails=Yii::$app->common->getStudent($getSms->fk_user_id);
              $i++; ?>
              <tr>
                <td><?php echo $i ?></td>
                <!-- <td><?//= Yii::$app->common->getName($studentDetails->user_id) ?></td>
                <td><?//= Yii::$app->common->getParentName($studentDetails->stu_id) ?></td> -->
                <td><?php echo $getSms->receiver_no ?></td>
                <td><?php echo stripslashes($getSms->SMS_body) ?></td>
                <td><?php echo date('d M Y H:i:s',strtotime($getSms->sent_date_time)) ?></td>
                <td><?php echo $getSms->status ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
          <?php echo \yii\widgets\LinkPager::widget(['pagination' => $pages]); ?>
        </div>
      </div>
    </div>
  </div>
</div>