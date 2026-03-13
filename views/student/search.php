<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\helpers\Html;
$this->title='Search Student';
$class_array=\app\models\RefClass::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'status'=>'active'])->all();
?>
<?php if (Yii::$app->session->hasFlash('success')): ?>
 <div class="alert alert-success">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
  <?= Yii::$app->session->getFlash('success') ?>
</div>
<?php endif; ?>
<div class="box box-warning">
  <div class="box-body">
    <?php $form = ActiveForm::begin(); ?> 
    <div class="row">
      <div class="col-md-2">
        <label for="">Active</label>
        <input type="radio" value=1 name='activeInactive' checked="checked">
        <label for="">In Active</label>
        <input type="radio" value=0 name='activeInactive'>
      </div>
      <div class="col-md-2">
        <?php echo $form->field($model, 'emergency_contact_no')->dropDownList(['first' => 'First name', 'last' => 'Last name','reg' => 'Reg. No.','cnic' => 'CNIC','contact' => 'Contact No.','parentName' => 'Parent Full name','address'=>'Address'],['prompt'=>'Search By','required'=>'required'])->label(false);?>
      </div>
      <div class="col-md-3">
        <?=  $form->field($model, 'contact_no')->textInput(['placeholder'=>'Search'])->label(false);  ?>
      </div>

      <div class="col-md-2">
        <?= Html::submitButton($model->isNewRecord ? 'Search' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
      </div>
      <?php if(Yii::$app->request->post('StudentInfo')){?>
        <div class="col-md-2 pull-right">
          <a href="<?=Url::to(['student/search-pdf','search'=>$data,'activeInactive'=>$status])?>" class="btn btn-primary"><i class="fa fa-download"></i> Generate Report</a>
        </div>
      <?php }?>

    </div>
    <?php ActiveForm::end(); ?>
  </div>
</div>

<?php if(Yii::$app->request->post('StudentInfo')){?>
  <div class="box box-warning">
    <div class="box-body">
      <?php  if(count($userDetails)>0){ ?>
        <div class="table-responsive">
          <table class="table table-stripped">
            <thead>
              <tr class="info">
                <td>Sr.</td>
                <td>Reg. NO.</td>
                <td>Roll #</td>
                <td>Name</td>
                <td>Father</td>
                <td>Parent Contact</td>
                <td>Parent CNIC</td>
                <td>Class</td>
                <td>Session</td>
                <td>DOB</td>
                <td>Address</td>
                <td width="150px">Action</td>
              </tr>
            </thead>
            <tbody>
              <?php
              $i=0; 
              foreach ($userDetails as $key => $userDetailsvalue) {
                $sessionDetails=\app\models\RefSession::find()->where(['session_id'=>$userDetailsvalue['session_id']])->one();
                $i++;
                if($userDetailsvalue['is_active'] == $status){
                  ?>
                  <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $userDetailsvalue['username'] ?></td>
                    <td><?php echo $userDetailsvalue['roll_no'] ?></td>
                    <td><?php echo Yii::$app->common->getName($userDetailsvalue['user_id']) ?></td>
                    <td><?php echo Yii::$app->common->getParentName($userDetailsvalue['stu_id']) ?></td>
                    <td><?php echo $userDetailsvalue['contact_no'] ?></td>
                    <td><?php echo $userDetailsvalue['cnic'] ?></td>
                    <td><?php echo Yii::$app->common->getCGSName($userDetailsvalue['class_id'],$userDetailsvalue['group_id'],$userDetailsvalue['section_id']) ?></td>
                    <td><?php echo $sessionDetails['title'] ?></td>
                    <td><?php echo $userDetailsvalue['dob'] ?></td>
                    <td><?php echo $userDetailsvalue['location1'] ?></td>
                    <td>
                      <a class="btn-success btn-xs" href="<?php echo Url::to(['student/profile','id'=>$userDetailsvalue['stu_id']])?>" target="_blank" data-pjax="0"><i class="fa fa-fw fa-eye" title="Profile"></i></a>
                      <a class="btn-info btn-xs" href="<?php echo Url::to(['student/edit','id'=>base64_encode($userDetailsvalue['user_id'])])?>" target="_blank" data-pjax="0"><i class="fa fa-fw fa-edit" title="Update"></i></a> 
                      <a class="btn-primary btn-xs" id="stu" data-toggle="modal" data-target="#myModal" data-stu_id="<?= $userDetailsvalue['stu_id']; ?>" style="cursor: pointer;" title="Send SMS"><i class="fa fa-envelope-o"></i></a>
                      <i class="fa fa-lock leavingBtn btn-danger btn-xs" data-placement="bottom" title="Leaving Institution" data-toggle="modal" data-target="#myModalClass" data-stuid="<?= $userDetailsvalue['stu_id']; ?>" id="getStuId" style="cursor: pointer;"></i>
                      <?php if($userDetailsvalue['is_active'] == 0){ ?>
                        <a href="<?php echo Url::to(['student/activestu','id'=>$userDetailsvalue['stu_id']])?>" style="cursor: pointer;"><span class="glyphicon glyphicon-ok" title="Activate"></span></a>
                      <?php } ?>
                      <i class="fa fa-key btn btn-warning btn-xs" data-toggle="modal" data-target="#changePassword" id="changePass" data-id="<?= $userDetailsvalue['user_id']; ?>"  title="Change Password"></i>

                      <a class="btn btn-danger btn-xs" href="<?php echo Url::to(['student/delete','id'=>$userDetailsvalue['stu_id']])?>" style="cursor: pointer;" onclick="return confirm('Are you sure you want to permanent delete this student..? Note..! This student will be permanently deleted from the system and will never be undo.')"><span class="glyphicon glyphicon-trash" title="Delete Permanently"></span></a>
                    </td>
                    <!-- leaving institute -->
                    <td>
                      <?php $StudentLeaveInfo = \app\models\StudentLeaveInfo::find()->where(['stu_id'=>$userDetailsvalue['stu_id']])->one();?>
                    <div id="myModalClass" class="modal fade" role="dialog">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header alert-success">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Student Leaving Institute </h4>
                          </div>
                          <div class="modal-body">
                            <p>
                              <input type="hidden" name="" id="getStudid">
                              <input type="hidden" id="pass_stu_id" name="pass_stu_id" value="<?php echo $userDetailsvalue['stu_id']?>">
                              <label for="">Enroll class</label>
                              <select class="form-control" name="enroll_class" id="enroll_class" required>
                                <option value="">Select</option>
                                <?php foreach($class_array as $class_array_value){?>
                                  <option value="<?php echo $class_array_value->class_id?>"><?php echo $class_array_value->title?></option>  
                                <?php }?>
                              </select>
                              <span id="classError" style="color: red"></span>
                              <br>
                              <label id="labelRemoves" style="color: red"></label>
                              <span class="info">
                                <label for="">Remarks</label>
                                <textarea name="" class="form-control remarks" id="" cols="30" rows="5"></textarea>
                                <span id="remarksError" style="color: red"></span>
                                <br />
                                <label for="">Next School</label>
                                <input type="text" class="form-control nextSchool">
                                <span id="nextError" style="color: red"></span>
                                <br />
                                <label for="">Reason for leaving</label>
                                <input type="text" class="form-control reason">
                                <span id="reasonError" style="color: red"></span> <br />
                                <?php
                                echo '<label>Date:</label>';
                                echo DatePicker::widget([
                                  'name' => 'overallstart', 
                                  'value' => date('01-m-Y'),
                                  'options' => ['placeholder' => ' ','id'=>'startDate'],
                                  'pluginOptions' => [
                                    'format' => 'dd-m-yyyy',
                                    'todayHighlight' => true,
                                    'autoclose'=>true,
                                  ]]);
                                  ?>               
                                </span>
                              </p>
                            </div>
                            <br /> <br />  <br /> 
                            <div class="modal-footer bg-primary">
                              <button type="button" class="btn btn-success saveLeaving" data-url="<?php echo Url::to(['student/leave-info'])?>">Submit</button>
                              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                    <!-- leaving institute end-->
                  </tr>
                <?php }else{echo '<div> No details found</div>';} }}else{
                  echo '<div class="alert alert-danger">No details found..!</div>';
                }?>
              </tbody>
            </table>    
          </div>
        </div>
      </div>
    <?php }?>
    <!-- sms model -->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Send SMS</h4>
            <input type="hidden" name="getstudent_id" id="stu_id" value=""/>
          </div>
          <div class="modal-body">
            <textarea class="form-control" name="text" id="textareasms"></textarea>
            <div id="sucmsg" style="color: green;"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" id="sendSms" data-url="<?php echo Url::to(['student/send-sms-parent'])?>">Send</button>
            <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <!-- sms model ends -->
    
      <!-- modal for change password -->
      <div id="changePassword" class="modal fade" role="dialog">
        <div class="modal-dialog">
          <?php $form = ActiveForm::begin(['action' => Url::to(['change'])]); ?>
          <div class="modal-content">
            <div class="modal-header alert-danger">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Change Password</h4>
            </div>
            <div class="modal-body">
              <div class="single-input w-100">
                <label>New Password</label>
                <input type="hidden" name="userId" value="" id="userId">
                <?= $form->field($model, 'password')->passwordInput(['required'=>'required'])->label(false) ?>
                <div class="currentN" style="color: red"></div>
              </div>
              <div class="single-input w-100">
                <?= $form->field($model, 'confirm_password')->passwordInput() ?>
              </div>
            </div>
            <div class="modal-footer">
             <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
             <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
           </div>
         </div>
         <?php ActiveForm::end(); ?>
       </div>
     </div>
     <?php
     $script = <<< JS
     $(document).on('click','.saveLeaving',function(e){
       e.preventDefault()
       e.stopImmediatePropagation();
       var stu_id=$('#getStudid').val();
       var pass_stu_id=$('#pass_stu_id').val();
       var update_stu_id=$('#update_stu_id').val();
       var enroll_class=$('#enroll_class').val();
       var date=$('#startDate').val();
       var removeVal=$('.leavingInstitute:checked').val();
       var remarks=$('.remarks').val();
       var nextSchool=$('.nextSchool').val();
       var reason=$('.reason').val();
       var getUrl=$(this).data('url');
       if(enroll_class ==''){
        $('#classError').text('Please fill Enroll class');
        return false;
       }else{
        $('#classError').text('');
       }
       if(remarks == ''){
        $('#remarksError').text('Please fill remarks');
        return false;
        }else{
         $('#remarksError').text('');
         } if(nextSchool == ''){
          $('#nextError').text('Please fill Next School');
          return false;
          }else{
           $('#nextError').text('');
           } if(reason == ''){
            $('#reasonError').text('Please fill Reason');
            return false;
            }else{
             $('#reasonError').text('');
           } 
           $.ajax({
            type: "POST",
            data: {update_stu_id:update_stu_id,enroll_class:enroll_class,pass_stu_id:pass_stu_id,stu_id:stu_id,remarks:remarks,nextSchool:nextSchool,reason:reason,date:date},
            url: getUrl,
            cache: false,
            success: function(result){
             $('#myModalClass').modal('hide');
                //$("#studentSearch").trigger('click');
             window.location.href = '';
           }
           });

           });
           $(document).on('click','#getStuId',function(e){
            var stuId=$(this).data('stuid');
            $('#getStudid').val(stuId)

            });
JS;
           $this->registerJs($script);
           ?>