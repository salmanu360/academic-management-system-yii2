<?php
use yii\helpers\Url;
use app\models\SmsLog;
use app\widgets\Alert;
use yii\helpers\Html;
use app\models\Profession;
$this->title = 'Student Profile';
$this->registerCssFile(Yii::getAlias('@web').'/css/site.css',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::getAlias('@web').'/css/fullcalendar/fullcalendar.min.css',['depends' => [yii\web\JqueryAsset::className()]]);

//$this->params['breadcrumbs'][] = $this->title;
if(Yii::$app->request->get('form_id')) {
    $this->registerJs("$('#generate-challan-view')[0].click();",\Yii\web\View::POS_READY);
}

                if(Yii::$app->request->get('form_id')) {
                    ?>
                    <?= Html::a('generate fee challan.',['student/download-form','id' => Yii::$app->request->get('id')],['style'=>'visibility:hidden;','id'=>'generate-challan-view'])?>
                    <?php
                }   
                ?>  
                <?= Alert::widget()?>
                <?php
                            $parentInfo = $studentInfo->studentParentsInfos;
                            $file_name = $studentInfo->user->Image;
                            $file_path = Yii::getAlias('@webroot').'/uploads/';

                            if(!empty($file_name) && file_exists($file_path.$file_name)) {
                                $web_path = Yii::getAlias('@web').'/uploads/';
                                $imageName = $studentInfo->user->Image;

                            }else{
                                $web_path = Yii::getAlias('@web').'/img/';
                                if($studentInfo->gender_type == 1){
                                    $imageName = 'male.jpg';
                                }else{
                                    $imageName = 'female.png';

                                }
                            }
                            if($studentInfo->gender_type == 1){
                                $s_d= 'S/O';
                                $gender_name = 'Male';
                            }else{
                                $s_d= 'D/O';
                                $gender_name = 'Female';
                                $imageName = 'female.png';
                            }
                            ?>
    <br/>


<!-- start///////////////////////////////////////////////////////////////////////////// -->

<div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <?php if(!empty($file_name) && file_exists($file_path.$file_name)) {
                                $web_path = Yii::getAlias('@web').'/uploads/';
                                $imageName = $studentInfo->user->Image; ?>
               <img class="profile-user-img img-responsive img-circle" src="<?= $web_path.$imageName?>" alt="<?=Yii::$app->common->getName($studentInfo->user->id);?>">
               <?php 
             }else{?>
  <img class="profile-user-img img-responsive img-circle" src="<?= Url::to('@web/img')?>/<?=($studentInfo->gender_type == 1)? 'male.jpg' :'female.png' ?>" alt="<?=Yii::$app->common->getName($studentInfo->user->id);?>">
             <?php }
                ?>

              <h3 class="profile-username text-center">

              <?php
              if($studentInfo->gender_type == 1){ 
              echo 'Mr.' .Yii::$app->common->getName($studentInfo->user->id);}else{
              echo 'Miss.' .Yii::$app->common->getName($studentInfo->user->id);
                }?>
                
              </h3>

              <p class="text-muted text-center"><?=$s_d?> <?=($studentInfo->studentParentsInfos)?Yii::$app->common->getParentName($studentInfo->stu_id):''?></p>

              <ul class="list-group list-group-unbordered">
              <li class="list-group-item" style="height: 64px">
                  <b>Registration No.</b> <a class="pull-right"><?=$studentInfo->user->username?></a>
                </li>
               <li class="list-group-item">
                  <b>Roll No.</b> <a class="pull-right"><?=$studentInfo->roll_no?></a>
                </li>
                <li class="list-group-item">
                  <b>Session</b> <a class="pull-right"><?=$studentInfo->session->title?></a>
                </li>

                <li class="list-group-item">
                  <b>Admission Date</b> <a class="pull-right"><?= date('d, M Y',strtotime($studentInfo->registration_date))?></a>
                </li>
               
                <li class="list-group-item">
                  <b><?=Yii::t('app','Date of Birth')?></b> <a class="pull-right"></span><?= date('d, M Y',strtotime($studentInfo->dob))?></a>
                </li>
              </ul>
              <?php if(Yii::$app->user->identity->fk_role_id == 1){ ?>
              <?php echo Html::a('Update', ['update', 'id' => $_GET["id"]], ['class' => 'btn btn-primary btn-block']); ?>
              <?php } ?>
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">About Me</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-book margin-r-5"></i> Education</strong>

              <p class="text-muted">
               <ul class="list-group list-group-unbordered">
               <li class="list-group-item">
                  <b><?=Yii::t('app','Class')?></b> <a class="pull-right"><?=$studentInfo->class->title?></a>
                </li>

                <li class="list-group-item">
                  <b><?=Yii::t('app','Group')?></b> <a class="pull-right"><?=($studentInfo->group_id)?$studentInfo->group->title:'N/A'?></a>
                </li>
               
                <li class="list-group-item">
                  <b><?=Yii::t('app','Section')?></b> <a class="pull-right"></span><?=$studentInfo->section->title?></a>
                </li>
              </ul>
              </p>

              <hr>
              </div>
            <!-- /.box-body -->
              </div>
          <!-- /.box -->
              </div>
        <!-- /.col -->


        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">Student Information</a></li>
              <li><a href="#timeline" data-toggle="tab">Fee Information</a></li>
              <li><a href="#examinfo" data-toggle="tab">Exam Information</a></li>
              <li><a href="#quizinfo" data-toggle="tab">Quiz Info</a></li>
              <li><a href="#attendanceinfo" data-toggle="tab">Attendance Information</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                <!-- Post -->
                <div class="post">
                  <div class="user-block">
                    <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image"> -->
                        <span class="username">
                          <a href="#">Parents Details</a>
                        </span>
                    <!-- <span class="description">Shared publicly - 7:30 PM today</span> -->
                  </div>
                  <!-- /.user-block -->
                  <p>
                    <table class="table">
                      <thead>
                        <tr class="info">
                          <th>Father Name:
                          <?=($studentInfo->studentParentsInfos)?Yii::$app->common->getParentName($studentInfo->stu_id):''?>
                          </th>
                          <th>CNIC: 
                          <?=($studentInfo->studentParentsInfos)?$studentInfo->studentParentsInfos->cnic:'N/A'?>
                          </th>
                          <th>Contact #:
                          <?=($studentInfo->studentParentsInfos->contact_no)?$studentInfo->studentParentsInfos->contact_no:'N/A'?>
                          </th>

                          
                        </tr>
                        <tr>
                        <th>Emergency Contact #:
                          <?=($studentInfo->studentParentsInfos->contact_no2)?$studentInfo->studentParentsInfos->contact_no2:'N/A'?>
                         </th>
                         <th>Office Contact #:
                          <?=($studentInfo->studentParentsInfos->office_no)?$studentInfo->studentParentsInfos->office_no:'N/A'?>
                         </th>
                          <th>Email:
                          <?=($studentInfo->studentParentsInfos->email)?$studentInfo->studentParentsInfos->email:'N/A'?>
                          </th>
                        </tr>
                        <tr>
                          <th>Profession:
                          <?php if($studentInfo->studentParentsInfos){
                                  $professn = $studentInfo->studentParentsInfos->profession;
                                  if($professn){
                                      $getProfession=Profession::find()->where(['id'=>$professn])->one();
                                      echo $getProfession->title;
                                  }else{
                                      echo 'N/A';
                                  }
                                  }else{
                                      echo 'N/A';
                                  }
                                  ?>
                          </th>
                          <th>Organisation:
                          <?=($studentInfo->studentParentsInfos->organisation)?$studentInfo->studentParentsInfos->organisation:'N/A'?>
                          </th> 
                          <th>Designation:
                          <?=($studentInfo->studentParentsInfos->designation)?$studentInfo->studentParentsInfos->designation:'N/A'?>
                          </th>
                        </tr>
                        <tr class="info">
                          <th>Mother Name:
                           <?=($studentInfo->studentParentsInfos->mother_name)?$studentInfo->studentParentsInfos->mother_name:'N/A'?>
                          </th>
                          <th>Mother Contact #:
                          <?=($studentInfo->studentParentsInfos->mother_contactno)?$studentInfo->studentParentsInfos->mother_contactno:'N/A'?>
                          </th> 
                          <th>Mother Office #:
                          <?=($studentInfo->studentParentsInfos->mother_officeno)?$studentInfo->studentParentsInfos->mother_officeno:'N/A'?>
                          </th>
                        </tr>

                        <tr>
                          <th>Mother Profession:
                          <?php if($studentInfo->studentParentsInfos){
                                  $professn = $studentInfo->studentParentsInfos->mother_profession;
                                  if($professn){
                                      $getProfession=Profession::find()->where(['id'=>$professn])->one();
                                      echo $getProfession->title;
                                  }else{
                                      echo 'N/A';
                                  }
                                  }else{
                                      echo 'N/A';
                                  }
                                  ?>
                          </th>
                          <th>Organisation:
                          <?=($studentInfo->studentParentsInfos->mother_organization)?$studentInfo->studentParentsInfos->mother_organization:'N/A'?>
                          </th> 
                          <th>Designation:
                          <?=($studentInfo->studentParentsInfos->mother_designation)?$studentInfo->studentParentsInfos->mother_designation:'N/A'?>
                          </th>
                        </tr>

                        <!-- guardian details -->
                        <tr class="info">
                          <th>Guardian Name:
                          <?=($studentInfo->studentParentsInfos->guardian_name)?$studentInfo->studentParentsInfos->guardian_name:'N/A'?>
                          </th>
                          <th>Guardian Relation:
                          <?=($studentInfo->studentParentsInfos->relation)?$studentInfo->studentParentsInfos->relation:'N/A'?>
                          </th>
                          <th>Guardian CNIC:
                          <?=($studentInfo->studentParentsInfos->guardian_cnic)?$studentInfo->studentParentsInfos->guardian_cnic:'N/A'?>
                          </th>
                        </tr>
                        <tr>
                          <th>Guardian Contact #:
                             <?=($studentInfo->studentParentsInfos->guardian_contact_no)?$studentInfo->studentParentsInfos->guardian_contact_no:'N/A'?>
                          </th>
                        </tr>
                        <!-- end of guardian details -->
                      </thead>
                    </table>
                  </p>
                  <!-- <ul class="list-inline">
                    <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                    <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                    </li>
                    <li class="pull-right">
                      <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments
                        (5)</a></li>
                  </ul>
                  
                  <input class="form-control input-sm" type="text" placeholder="Type a comment"> -->
                </div>
                <!-- /.post -->

                <!-- Post -->
                <div class="post clearfix">
                  <div class="user-block">
                     <span class="username">
                          <a href="#">Address Information</a>
                        </span>
                    
                  </div>
                  <!-- /.user-block -->
                  <p>
                    <table class="table table-stripped">
                    <thead>
                      <tr>
                        <th>
                          Present Address:
                          <?php echo ($studentInfo->location1)? strtoupper($studentInfo->location1):'N/A' ;
                          echo ' , ';
                             if(count($studentInfo->country_id) > 0){
                                  echo $studentInfo->district->District_Name.' , '.$studentInfo->city->city_name .' , '. $studentInfo->province->province_name .' , '. $studentInfo->country->country_name;}?>
                        </th>

                      </tr>
                      <tr>
                        <th>
                           Permanent Address:
                           <?php echo ($studentInfo->location2)?$studentInfo->location2:'N/A';
                                                if(!empty($studentInfo->location2)){
                                                    if($studentInfo->fk_ref_city_id2){
                                                        echo $studentInfo->fkRefCityId2->city_name;
                                                    }
                                                    if($studentInfo->fk_ref_province_id2){
                                                        echo  ' , '. $studentInfo->fkRefProvinceId2->province_name;
                                                        
                                                    }
                                                    if($studentInfo->fk_ref_country_id2){
                                                       echo ' , '. $studentInfo->fkRefCountryId2->country_name;
                                                    }
                                                }

                                                ?>
                        </th>
                      </tr>
                    </thead>
                      
                    </table>
                  </p>
                </div>
                <!-- /.post -->

                <!-- Post -->
                <div class="post">
                  <div class="user-block">
                       <span class="username">
                          <a href="">Transport Details</a>
                        </span>
                  </div>
                  <!-- /.user-block -->
                  <table class="table-stripped">
                  <thead>
                    <tr>
                      <th>
                    <?php 
                      $student_details = Yii::$app->common->getStudent($studentInfo->stu_id);
                      $transport= \app\models\TransportAllocation::find()->where(['stu_id'=>$student_details->user_id,'status'=>1])->one();
                      if(count($transport)>0){ 
                      echo '<strong>Zone:</strong>  '. $transport->zone->title.', <strong>Route:</strong> '.$transport->fkRoute->title.', <strong>Stop:</strong> '.$transport->stop->title;
                       }else{
                           echo "<span style='color:red'>Transport Details Not Found</span>";
                       }
                    ?> 
                      </th>
                    </tr>
                  </thead>
               
                </table>
                   
                  <!-- /.row -->
                   </div>

                   <div class="post">
                  <div class="user-block">
                       <span class="username">
                          <a href="">Hostel Details</a>
                        </span>
                  </div>
                  <!-- /.user-block -->
                  <table class="table-stripped table-hovered">
                  <thead>
                    <tr>
                      <th>
                    <?php
                                $hostel_detail  = \app\models\HostelDetail::find()->where(['fk_student_id'=>$studentInfo->stu_id])->one();
                                if(count($hostel_detail)>0){
                                $hostel_name    = $hostel_detail->fkHostel->Name;
                                $floor_name     = $hostel_detail->fkFloor->title;
                                $room           = $hostel_detail->fkRoom->title;
                                $bed            = $hostel_detail->fkBed->title;
                                echo '<strong>Name:</strong> '.$hostel_name.", <strong>Floor Name:</strong> ".$floor_name.",  <strong>Room:</strong>  ".$room.", <strong>Bed:</strong> ".$bed;
                            }
                            else{
                                echo '<span style="color:red">Hostel Details Not Found</span>';
                            }
                            ?>
                      </th>
                    </tr>
                  </thead>
               
                </table>
                   
                  <!-- /.row -->
                   </div>
                   <hr />
                    <!-- timeline -->
                  <div class="post">
                  <div class="user-block">
                       <span class="username">
                          <a href="javascript:void(0)">All Classes TimeLine</a>
                        </span>
                        <div class="student-timeline">
                            <?php
                            $total_items= count($total_time_line)-1;
                            $counter = $total_items;
                            $group_sect='';
                            ?>
                           <?php
                            foreach ($total_time_line as $key=>$time_line_item){
                                $items= $total_time_line[$counter];
                                if($total_items==$counter){
                                    $end_date= $start_date;
                                }
                                else if($counter == 0){
                                    $end_date = date('Y-m-d',strtotime($studentInfo->registration_date));
                                }else{
                                    $end_promo_date = $total_time_line[$counter-1];
                                    $end_date = date('Y-m-d',strtotime($end_promo_date['promoted_date']));
                                }
                                $s_date = date('Y-m-d',strtotime($items['promoted_date']));
                                $e_date = $end_date;
                                ?>

                                <a id="get-timeline" class="<?=($total_items==$counter)?'active':''?>" href="javascript:void(0);" data-sdate="<?=$s_date?>" data-edate="<?=$e_date?>" data-std="<?=$studentInfo->stu_id?>" data-class_id="<?=$items['old_class']?>" data-group_id="<?=$items['old_group']?>" data-section_id="<?=$items['old_section']?>" data-url="<?=Url::to('get-profile-stats')?>">
                                    <div class="st_class">
                                        <p><?=str_replace(' ','<br/>',$items['class_name'])?></p>
                                        <span><?=date('Y',strtotime($items['promoted_date']))?></span>
                                        <!--<span><?php
                                        /*if($items['group_name'] !=''){
                                            $group_sect = $items['group_name']." | ";
                                        }
                                        if($items['section_name'] !=''){
                                            $group_sect = $items['section_name'];
                                        }

                                        echo $group_sect;*/
                                        ?> </span>-->
                                    </div>
                                </a>
                                <?php
                                $end_date = $items['promoted_date'];
                                $counter--;
                            }

                            ?>

                        </div>
                  </div>
                  </div>
                  <!-- end of timeline -->

                   
                <!-- /.post -->
              </div>
              <!-- /.tab-pane -->
             

              <!-- start of fee tab -->
              <div class="tab-pane" id="timeline">
                <div class="post">
                  <div class="user-block">
                    <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image"> -->
                        <span class="username">
                          <a href="#">Fee Details of <?=Yii::$app->common->getName($studentInfo->user->id);?> <?=$s_d?> <?=($studentInfo->studentParentsInfos)?Yii::$app->common->getParentName($studentInfo->stu_id):''?></a>
                        </span>

                        
                        <!-- fee details -->
                         <table class="table table-bordered">
                        <thead>
                           <tr class="success">
                            <th>SR</th>
                            <th>Fee Head</th>
                            <th>Amount</th>
                            <th>Head Discount</th>
                            <th>Sibling Discount</th>
                            </tr>
                        </thead>
                            <tbody>
                            <?php 
                            $i=0;
                            $total=0;
                            $discountHead=0;
                            $total_sibling_discount = 0;
                            $transport=0;
                            $transport=\app\models\TransportAllocation::find()->where(['stu_id'=>$studentInfo->user_id,'status'=>1])->one();
                             $settings = Yii::$app->common->getBranchSettings();
                            //echo '<pre>';print_r($getFeeDetails);die();
                            foreach ($getFee as $key=> $feeavail) {
                            $i++;
                            $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$feeavail->fk_fee_head_id])->one();
                            /*if new then admission,if old then promotion*/
                            $year=date('Y');  
                            $promotedData=\app\models\StuRegLogAssociation::find()->where(['fk_stu_id'=>$studentInfo->stu_id])->andWhere(['YEAR(promoted_date)'=>$year])->count();
                            /*promotion is active*/
                            if($getHead->promotion_head == 1){
                                if($promotedData==0){  
                                  continue;
                                }                
                              }
                            /*promotion is active ends*/
                            /*temporory avoid admssion fee*/
                            if($getHead->one_time_payment ==1 && $getHead->title =='Admission Fee' && $promotedData >0 ){ // will show promotion fee and will hide admission fee
                              continue;
                              }
                            /*temporory avoid admssion fee*/
                            /*if new then admission,if old then promotion ends*/
                            $total=$total+$feeavail->amount;
                            $getDiscountPercent=\app\models\FeePlan::find()->where(['fee_head_id'=>$feeavail->fk_fee_head_id,'stu_id'=>$studentInfo->stu_id,'status'=>1])->one();
                            $discountHead=$discountHead+$getDiscountPercent['discount'];
                              ?>
                              <tr>
                                 <td><?= $i; ?></td>
                                 <td><?=strtoupper($getHead->title) ?> </td> 
                                 <td>Rs. <?=$feeavail->amount ?> </td>
                                 <td>Rs. <?=(!empty($getDiscountPercent['discount']))?$getDiscountPercent['discount']:0?></td>
                                 <td>
                                     <?php
                                     if($studentInfo->avail_sibling_discount ==1){
                                         if(($cnic_count) >= $settings->sibling_no_childs  && $feeavail->fk_fee_head_id == $getHead->id && $getHead->sibling_discount ==1 ){
                                                if(!empty($settings->sibling_discount)){
                                                    echo 'Rs. '.$discount_sibling = $feeavail->amount*$settings->sibling_discount/100;
                                                }
                                         $total_sibling_discount = $total_sibling_discount+$discount_sibling;
                                            }
                                     }
                                     ?></td>
                              </tr>
                            <?php } ?>
                            <tr>
                            <td></td>
                                <th> Transport Fare
                                </th>
                                <td> <?php
                                    if(count($transport)>0){
                                        echo 'Rs. ';
                                        $stop = \app\models\Stop::find()->where(['id'=>$transport->fk_stop_id])->One();
                                        if(count($stop)>0){
                                            echo $transport_amount = $stop->fare -$transport->discount_amount;
                                        }
                                    } ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <th>Total</th>
                                <th>
                                <?php
                                if(count($transport)>0){
                             echo 'Rs. ';
                              $stop = \app\models\Stop::find()->where(['id'=>$transport->fk_stop_id])->One();
                              if(count($stop)>0){
                                 $transport_amount = $stop->fare;
                                 echo $total+$transport_amount-$transport->discount_amount;
                              }
                          }else{
                            echo 'Rs. '. $total;
                          }?>
                                </th>
                                <th>Rs. <?=$discountHead ?></th>
                                <th>Rs. <?=$total_sibling_discount ?></th>
                            </tr>
                            <tr>
                                <td></td>
                                <th>Fee Taken</th>
                                <th>Rs.
                                <?php
                                if(count($transport)>0){
                                echo $total-$discountHead+$transport_amount-$transport->discount_amount;
                                }else if(($cnic_count) >= $settings->sibling_no_childs && $studentInfo->avail_sibling_discount==1){
                                  //sibling
                                    if(!empty($settings->sibling_discount)){
                                          $discount_sibling = $feeavail->amount*$settings->sibling_discount/100;
                                        echo $total-$discountHead-$total_sibling_discount;
                                       }
                                }else{
                                  //end of sibling
                                  echo $total-$discountHead;
                                }
                                         ?>
                                </th>
                            </tr>
                            </tbody>
                     </table>
                        <!-- end of fee details -->
                  </div>
                  </div>


                  <!-- fee graph -->
                  <div class="post">
                  <div class="user-block">
                    <!-- <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image"> -->
                    <div class="row">
                          <div class="col-md-9">
                            
                            <div id="container" style="height: 400px"></div>
                     
                          </div>
                        </div>
                        <span class="username">
                         
                        </span>
                        <br />
                                <div class="widget" style="display: none">
                                    <div id="container_fee_chart" style="margin: 0 auto"></div>
                                </div>
                           
                        </div>
                        </div>
                  <!-- end of fee graph -->
                
              </div>
              <!-- / end of fee tab-pane -->
        
        <!-- exam here -->

                        <!-- start of Exam tab -->
              <div class="tab-pane" id="examinfo">
                <div class="post">
                  <div class="user-block">
                    <span class="username">
                          <a href="#">Exam Details of <?=Yii::$app->common->getName($studentInfo->user->id);?> <?=$s_d?> <?=($studentInfo->studentParentsInfos)?Yii::$app->common->getParentName($studentInfo->stu_id):''?></a>
                        </span>
                        <br />
                        <?php
                        if(count($exam_array)>0) { ?>
                            <div class="st_widget shade st_results">
                                <div class="tab-content">
                                <table class="table">
                                    <ul class="nav nav-tabs exams-list">
                                       <!--  <li class="res_title">Results</li> -->
                                        <?php
                                        foreach($exam_array as $key=>$exams) { 
                                            ?>
                                            <li>
                                                <a href="#exam-<?=$key?>"
                                                   id="std-profile-exam"
                                                   data-examid="<?=$key?>"
                                                   data-stdid="<?=$studentInfo->stu_id?>"
                                                   data-classid="<?=$studentInfo->class_id?>"
                                                   data-groupid="<?=($studentInfo->group_id)?$studentInfo->group_id:null?>"
                                                   data-sectionid="<?=$studentInfo->section_id ?>"
                                                   data-url="<?=Url::to('profile-exam1')?>"
                                                   
                                                   data-examdivid="exam-<?=$key?>"><?=$exams?></a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                    <?php
                                    foreach($exam_array as $key=>$exams) {
                                      
                                        ?>
                                        <div id="exam-<?=$key?>" class="tab-pane fade">
                                            <?=$key?>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <div class="col-sm-3 res_chart" id="pigraph-cointainer" style="display: none;">
                                        <div id="exam-result-container">

                                        </div>
                                        <!--<img src="<?/*=\yii\helpers\Url::to('@web/img/result-chat.svg')*/?>" alt="MIS">-->
                                    </div>
                                    </table>
                                </div>
                            </div>
                            <?php
                        }else{
                            echo "<div class='danger' style='color:red'>Exam Details Not Found</div>";
                        }
                        ?>

                        </div>
                        </div>
                        </div>
                    <!-- end of exam tab & start of quiz-->
                    <div class="tab-pane" id="quizinfo">
                      <a style="margin-top: -10px" href="<?= Url::to(['reports/stu-quiz','stu_id'=>base64_encode($_GET['id'])]) ?>" class="btn btn-primary btn-sm pull-right">Generate Report</a><br>
                      <!--<h5 style="color:red">  Last 30 days Quiz Results </h5>-->
                      <?php 
                      $settings = Yii::$app->common->getBranchSettings();
                      $sessionStartDate=$settings->current_session_start;
                      $sessionEndDate=$settings->current_session_end;
                      /*uncomment if want 30 days
                      $currentDate=date('Y-m-d');
                      $lastThiryDate= date('Y-m-d',strtotime('-29 day'));
                      */
                      $quizResults = \app\models\ExamQuizType::find()
                    ->select(['exam_quiz_type.*','eq.*'])
                    ->innerJoin('exam_quiz eq','eq.test_id=exam_quiz_type.id')
                    ->where([
                        'eq.fk_branch_id'=>Yii::$app->common->getBranch(),
                        'eq.stu_id'=>$_GET['id'],
                    ])->andWhere(['between', 'exam_quiz_type.quiz_date', $sessionStartDate, $sessionEndDate])
                    ->asArray()->all();
                    if(count($quizResults)>0){
                     ?>
                    
                    <div class="table-responsive">
                      <table class="table table-striped">
                        <thead>
                      <tr style="background: #45983b;color:white">
                        <td>Sr.</td>
                        <td>Subject</td>
                        <td>Teacher</td>
                        <td>Total Marks</td>
                        <td>Passing Marks</td>
                        <td>Obtained Marks</td>
                        <td>Remarks</td>
                        <td>Date</td>
                      </tr>
                      </thead>
                      <tbody>
                        <?php
                        $i=1; 
                        foreach ($quizResults as $key => $quizResultsvalue) {
                          $subject_details=\app\models\Subjects::find()->where(['id'=>$quizResultsvalue['subject_id']])->one();
                          $employee = \app\models\EmployeeInfo::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'emp_id'=>$quizResultsvalue['teacher_id']])->one();
                          ?>
                          <tr>
                            <td><?=$i ?></td>
                            <td><?= strtoupper($subject_details['title'])  ?></td>
                            <td><?= Yii::$app->common->getName($employee['user_id'])  ?></td>
                            <td><?= $quizResultsvalue['total_marks']  ?></td>
                            <td><?= $quizResultsvalue['passing_marks']  ?></td>
                            <td><?php if($quizResultsvalue['obtained_marks'] < $quizResultsvalue['passing_marks']){echo '<span style="color:red;border:1px solid red">'.$quizResultsvalue['obtained_marks'].'</span>';}else{echo $quizResultsvalue['obtained_marks'];}  ?></td>
                            <td><?= $quizResultsvalue['remarks']  ?></td>
                            <td><?= date('d M Y',strtotime($quizResultsvalue['quiz_date']))  ?></td>
                          </tr>
                        <?php $i++; } ?>
                      </tbody>
                    </table>
                    </div>
                    <?php }else{ ?>
                    <div class="alert alert-warning">No Quiz Details Found..!</div>
                    <?php } ?>
                    </div>
                    <!-- end of quiz tab -->

                    <!-- start of attendance info -->
                <div class="tab-pane" id="attendanceinfo">
                <div class="post">
                <div class="user-block">
                    <span class="username">
                          <a href="">OverAll Attendance Details of <?=Yii::$app->common->getName($studentInfo->user->id);?> <?=$s_d?> <?=($studentInfo->studentParentsInfos)?Yii::$app->common->getParentName($studentInfo->stu_id):''?></a>
                    </span>
                    <br />

                    <!-- attendance graph -->
                                    <div class="widget">
                                    <?=(count($attendance_array)>0)?'<div id="container_attendance" style="margin: 0 auto"></div>':'No Attendance Details Found'?>
                                    </div> 
                    <!-- end of attendance graph -->

                    <!-- start of currrent month attendance -->
                    <br />
                    <span class="username">
                          <a href="">Current Month Attendance Status</a>
                        </span>
                        <br />
                   
              <div class="table-responsive">
                <table class="table no-margin">
                      
              <thead>
                <th>Present</th>
                <th>Absent</th>
                <th>Leave</th>
                <th>Short Leave</th>
                <th>Late Comming</th>
              </thead>
                     <tbody>
                      <tr>    
                      <th style="background: #00a65a;color: white;">
                      <?php 
                       $Present=yii::$app->db->createCommand("SELECT * FROM student_attendance WHERE MONTH(date) = MONTH(CURRENT_DATE()) and leave_type='present' and fk_stu_id=".$_GET['id'])->queryAll();  
                      
                       echo count($Present);?>
                 
                      </th>
                      <th style="background: red;color: white;">
                      <?php 
                       $absent=yii::$app->db->createCommand("SELECT * FROM student_attendance WHERE MONTH(date) = MONTH(CURRENT_DATE()) and leave_type='absent' and fk_stu_id=".$_GET['id'])->queryAll();  
                      
                       echo count($absent);?>
                      </th>
                      <th style="background: #f39c12;color: white">
                      <?php 
                       $leave=yii::$app->db->createCommand("SELECT * FROM student_attendance WHERE MONTH(date) = MONTH(CURRENT_DATE()) and leave_type='leave' and fk_stu_id=".$_GET['id'])->queryAll();  
                      echo count($leave);?>
                       </th>
                       <th style="background: #00c0ef;color: white">
                      <?php 
                       $shortleave=yii::$app->db->createCommand("SELECT * FROM student_attendance WHERE MONTH(date) = MONTH(CURRENT_DATE()) and leave_type='shortleave' and fk_stu_id=".$_GET['id'])->queryAll();  
                      
                       echo count($shortleave);?>
                     </th>
                      <th style="background: #d28747;color: white">
                      <?php 
                       $latecommer=yii::$app->db->createCommand("SELECT * FROM student_attendance WHERE MONTH(date) = MONTH(CURRENT_DATE()) and leave_type='latecomer' and fk_stu_id=".$_GET['id'])->queryAll();  
                      
                       echo count($latecommer);?>
                 
                      </th>
                      </tr>
                  
                  </tbody>
                </table>
              </div>
             <br />
                    <!-- end of currrent month attendance -->

                    <!-- attendance calendar -->
                     <div class="box box-success">
                     <div id="getcalendarajax"></div>
                     </div>
                    <!-- end of attendance calendar -->
                </div>
                </div>
                </div>
                    <!-- end of attendance info -->
              
              
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    <!-- /.content -->
<!-- end///////////////////////////////////////////////////////////////////////////// -->
                   

<?php
/*student attedance array*/
$attenance_data=[];
foreach ($attendance_array as $key=>$attendance_details){
    $attenance_data['leave_type'][]= $attendance_details['leave_type'];
    $attenance_data['total'][]= $attendance_details['total'];

}

$this->registerJS("$('ul.exams-list li a').last()[0].click();", \yii\web\View::POS_LOAD);
$this->registerJS(" 
    var attendance_details = ".json_encode($attenance_data,JSON_NUMERIC_CHECK).";
    var currentDate        = ".date('Y').";
    var FeePiData          =".json_encode($pi_array_fee,JSON_NUMERIC_CHECK).";
    ", \yii\web\View::POS_BEGIN);
$this->registerJsFile(Yii::getAlias('@web').'/js/highcharts.js',['depends' => [yii\web\JqueryAsset::className()],null]);
$this->registerJsFile(Yii::getAlias('@web').'/js/highcharts-std-profile.js',['depends' => [yii\web\JqueryAsset::className()],null]);
// calendar scripts
$this->registerJsFile(Yii::getAlias('@web').'/js/fullcalendar/moment.min.js',['depends' => [yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::getAlias('@web').'/js/fullcalendar/fullcalendar.min.js',['depends' => [yii\web\JqueryAsset::className()]]);

?>

<input type="hidden" data-url="<?= Url::to(['student/student-calendar-event'])?>" id="caledarStudenturl" value="<?php echo $_GET['id'] ?>">
 <?php
$headAmnt= $stuFeeRcv['total_amount_receive']+$fee_arrears_rcv;
$transportAmnt= $stuFeeRcv['transport_amount_rcv'];
$hostelAmnt= $stuFeeRcv['hostel_amount_rcv'];
$stuTransprtArrears=$stuTransprtHstlArrears['transport_arrears'];
$stuHostelArrears=(count($stuTransprtHstlArrears['hostel_arrears'])>0)?$stuTransprtHstlArrears['hostel_arrears']:'0';
$stuarrears= (!empty($stuarrears->arears))?$stuarrears->arears:'0';
/*if(empty($headAmnt) and empty($transportAmnt) and empty($hostelAmnt) and empty($stuTransprtArrears) and empty($stuHostelArrears) and empty($stuarrears)){*/

 $script= <<< JS
 $(document).ready(function() {
  var url=$('#caledarStudenturl').data('url');
var id=$('#caledarStudenturl').val();
 $.ajax
    ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {id:id},
        success: function(data)
        {
           $('#getcalendarajax').html(data.cal);
           //alert('success');
        }
    });
});

Highcharts.chart('container', {
    chart: {
        type: 'pie',
        options3d: {
            enabled: true,
            alpha: 45
        }
    },
    title: {
        text: 'Fee Graph Details'
    },
    
    plotOptions: {
        pie: {
            innerSize: 100,
            depth: 45
        }
    },
    series: [{
        name: 'Total',
        data: [
            ['Head Amount Rcv', $headAmnt],
            ['Transport Amount Rcv', $transportAmnt],
            ['Hostel Amount Rcv', $hostelAmnt],
            ['Transport Arrears',$stuTransprtArrears],
            ['Hostel Arrears',$stuHostelArrears],
            ['Total Arrears',$stuarrears],
        ]
    }]
});
JS;
$this->registerJs($script);


/* $script= <<< JS

JS;
$this->registerJs($script);*/
  ?>