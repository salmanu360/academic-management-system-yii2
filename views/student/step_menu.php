<?php 
use yii\helpers\Url;
$action=Yii::$app->controller->action->id;
$id=Yii::$app->request->get('id');
 ?>
<ul class="nav nav-pills nav-justified" id="pills-tab" role="tablist">
  <li class="nav-item <?php if($action == 'edit'){echo 'active';}?>">
    <a id="pills-home-tab" class="nav-link" href="<?php echo Url::to(['edit','id'=>$id]) ?>">Personnel Details</a>  </li>
  <li class="nav-item <?php if($action == 'official'){echo 'active';}?>">
    <a id="pills-home-tab" class="nav-link" href="<?php echo Url::to(['official','id'=>$id]) ?>">Official Details</a>  </li>
  <li class="nav-item <?php if($action == 'parent'){echo 'active';}?>">
    <a id="pills-home-tab" class="nav-link" href="<?php echo Url::to(['parent','id'=>$id]) ?>">Parent Details</a>  </li>
    <li class="nav-item <?php if($action == 'education'){echo 'active';}?>">
    <a id="pills-home-tab" class="nav-link" href="<?php echo Url::to(['education','id'=>$id]) ?>">Education Details</a>  </li>
    <li class="nav-item <?php if($action == 'fee' || $action == 'fee-edit'){echo 'active';}?>">
    <a id="pills-home-tab" class="nav-link" href="<?php echo Url::to(['fee','id'=>$id]) ?>">Fee Details</a>  </li>
    <li class="nav-item <?php if($action == 'download'){echo 'active';}?>">
    <a id="pills-home-tab" class="nav-link" href="<?php echo Url::to(['download','id'=>$id]) ?>">Download Form</a>  </li>
</ul>