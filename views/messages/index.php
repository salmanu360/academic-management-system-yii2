<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
$this->title = 'Messages';
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
 <div class="alert alert-success">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <?= Yii::$app->session->getFlash('success') ?>
</div>
<?php endif; ?>
<section class="content">
  <div class="row">
    <div class="col-md-2"><?php  echo $this->render('/messages/sidebar.php');?></div>
    <div class="col-md-2">
      <a href="javascript:void(0)" class="btn btn-primary btn-block margin-bottom" id="composeMessage" data-url="<?= Url::to(['messages/compose-message']) ?>">Compose</a>
      <div class="box box-solid">
        <div class="box-header with-border">
          <h3 class="box-title">Inbox</h3>
          <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body no-padding">
          <ul class="nav nav-pills nav-stacked">
            <?php foreach($getMessageUser as $employee){?>
              <li class="active"><a href="javascript:void(0)" id="getUserMesg" data-id="<?php echo $employee->user_id?>" data-sender="<?php echo $employee->sender_id?>" data-url="<?= Url::to(['messages/get-message']) ?>"><i class="fa fa-inbox"></i> <?= Yii::$app->common->getName($employee->sender_id);
              if($employee->read_status == 0){
               ?>
               <span class="label label-primary pull-right" style="display: none"><?= count($employee) ?></span></a></li>
             <?php }else{

             } }?>
           </ul>
         </div>
       </div>
     </div>
     <div class="col-md-8 displayMessage">
       <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Inbox</h3>
          <div class="box-tools pull-right">
            <div class="has-feedback">
              <input type="text" class="form-control input-sm" placeholder="Search Mail">
              <span class="glyphicon glyphicon-search form-control-feedback"></span>
            </div>
          </div>
        </div>
        <div class="box-body no-padding">
          <div class="table-responsive mailbox-messages">
            <table class="table table-hover table-striped">
              <tbody>
                <tr>
                  <td><div class="icheckbox_flat-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div></td>
                  <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                  <td class="mailbox-subject"><b>Select Message To read</b> 
                  </td>
                  <td class="mailbox-attachment"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div> 
  </div>
</section>