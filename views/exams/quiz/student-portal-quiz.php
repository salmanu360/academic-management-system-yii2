<div class="box">
<?php if(count($quizResults)>0){ ?>
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