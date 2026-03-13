<?php 
use yii\helpers\Url;
$studentInfo = \app\models\StudentInfo::find()->where(['user_id'=>Yii::$app->user->identity->id])->one();
    $file_name = Yii::$app->user->identity->Image;
    $file_path = Yii::getAlias('@webroot').'/uploads/';

    if(!empty($file_name) && file_exists($file_path.$file_name)) {
        $web_path = Yii::getAlias('@web').'/uploads/';
        $imageName = Yii::$app->user->identity->Image;

    }else{
        $web_path = Yii::getAlias('@web').'/img/';
        if($studentInfo) {
            if ($studentInfo->gender_type == 1) {
                $imageName = 'male.jpg';
            } else {
                $imageName = 'female.png';

            }
        }else{
            $imageName = 'male.jpg';
        }
    }
 ?>
<div class="box box-warning direct-chat direct-chat-warning">
                <div class="box-header with-border">
                  <h3 class="box-title">Read Messages</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages" style="height: 400px">
                    <!-- Message. Default to the left -->
                     <?php foreach ($getMessage as $getMessage) {?>
                      <input type="hidden" value="<?= $getMessage->sender_id ?>" id="senderIdpass">
                      <input type="hidden" value="<?= $getMessage->subject ?>" id="getSubject">
                          <?php if($getMessage->sender_recvr == 0){?>
                    <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left"><?php 
                       
                            echo Yii::$app->common->getName($getMessage->sender_id);
                        
                         
                         ?></span>
                        <span class="direct-chat-timestamp pull-right"><?= date('d M Y',strtotime($getMessage->send_date)) ?></span>
                      </div>
                      <!-- /.direct-chat-info -->
                    <?php 
                        $directory = Yii::getAlias('@web').'/uploads/';
                        //echo $directory;die;
                        $getImage=\app\models\User::find()->where(['id'=>$getMessage->sender_id])->one();
                        //echo $getImage->Image;die;
                        if(!empty($getImage->Image)){
                       ?>
                          <img class="direct-chat-img" src="<?php echo $directory.''.$getImage->Image; ?>" alt="User Image">
                      <?php }else{?>
                      <img class="direct-chat-img" src="<?php echo yii::$app->request->baseUrl;?>/uploads/male.jpg" alt="User Image">
                       <?php } ?>
                     
                      <!-- /.direct-chat-img -->
                 
                              <div class="direct-chat-text">
                              <?= $getMessage->message; ?>
                              </div>                             
                      
                      
                      <!-- /.direct-chat-text -->
                    </div>
                     <?php }else{ ?>
                      <div class="direct-chat-msg right">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-right"><?= Yii::$app->common->getName($getMessage->user_id); ?></span>
                        <span class="direct-chat-timestamp pull-left"><?= date('d M Y',strtotime($getMessage->send_date)) ?></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <?php 
                        $directory = Yii::getAlias('@web').'/uploads/';
                        $getImage=\app\models\User::find()->where(['id'=>$getMessage->user_id])->one();
                        if(!empty($getImage->Image)){
                       ?>
                          <img class="direct-chat-img" src="<?php echo $directory.''.$getImage->Image; ?>" alt="User Image">
                      <?php }else{?>
                      <img class="direct-chat-img" src="<?php echo yii::$app->request->baseUrl;?>/uploads/male.jpg" alt="User Image">
                       <?php } ?>
                       <!-- /.direct-chat-img -->

                      <div class="direct-chat-text">
                        <?= $getMessage->message; ?>
                        
                      </div>
                     
                      <!-- /.direct-chat-text -->
                    </div>

                    <!-- /.direct-chat-msg -->
                    <!-- Message to the right -->
                   
                    <?php  } }?>
                    <div class="direct-chat-msg right chatreply" style="display: none">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-right"><?= Yii::$app->common->getName($getMessage->user_id); ?></span>
                        <span class="direct-chat-timestamp pull-left"><?= date('d M Y',strtotime($getMessage->send_date)) ?></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <div class="direct-chat-text" id="replyMesg">
                        
                      </div>
                     
                      <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
                        </a>
                      </li>
                      <!-- End Contact Item -->
                    </ul>
                    <!-- /.contatcts-list -->
                  </div>
                  <!-- /.direct-chat-pane -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="input-group">
                      <input type="text" name="message" placeholder="Type Message ..." class="form-control" id="replyMessage" />
                      <!-- <input type="text" name="message" placeholder="Type Message ..." class="form-control" id="replyMessage" /> -->

                          <span class="input-group-btn">
                            <button type="button" class="btn btn-warning btn-flat" id='replyusers' data-url="<?= Url::to(['messages/reply']) ?>" data-id="<?php echo $getMessage->id ?>">Send</button>
                          </span>
                    </div>
                </div>
                <!-- /.box-footer-->
