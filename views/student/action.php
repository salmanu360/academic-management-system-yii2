<?php
use yii\helpers\Url;
$id=base64_decode(Yii::$app->request->get('id'));
  ?>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <ul class="nav navbar-nav">
      <li <?php if($id == 'promote'){ echo 'class=active';}?>  ><a href="<?php echo Url::to(['promote-students','id'=>base64_encode('promote')]);?>">Promote</a></li>
      <li <?php if($id == 'demote'){ echo 'class=active';}?>><a href="<?php echo Url::to(['demote','id'=>base64_encode('demote')]);?>">Demote</a></li>
        <li <?php if($id == 'change'){ echo 'class=active';}?>><a href="<?php echo Url::to(['shuffle-students','id'=>base64_encode('change')]);?>">Change Section</a></li>
    </ul>
  </div>
</nav>