<?php 
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;

$discount_type= ArrayHelper::map(\app\models\FeeDiscountTypes::find()->where(['fk_branch_id'=>Yii::$app->common->getBranch(),'is_active'=>1])->all(),'id','title'); 
$settings = Yii::$app->common->getBranchSettings();
 ?>
 <table class="table table-striped">
              <thead>
                <tr class="info">
                  <th>Fee Head</th>
                  <th>Ammount</th>
                  <th>Discount</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $sums=0;
                $i=1;
                $discount_sibling=0;
                $amount_head=0;
                foreach ($getFeeDetails as $key=> $getFeeDetails) { 

                //$diff = $key; 
                $getHead=\app\models\FeeHead::find()->where(['branch_id'=>yii::$app->common->getBranch(),'id'=>$getFeeDetails->fk_fee_head_id])->one();

                // skip promotion for naew admission
              
              if($getHead->promotion_head == 1){
                  continue;           
              }
                // skip promotion for naew admission end
                    
                  $sums=$sums+$getFeeDetails->amount;
                  $amount_head = $getFeeDetails->amount;
                    $settings = Yii::$app->common->getBranchSettings();
                  if(!empty($parent_cnic)){
                      /*if sibling is more than provided in settings*/
                      if(($cnic_count+1) >= $settings->sibling_no_childs  && $getFeeDetails->fk_fee_head_id == $getHead->id && $getHead->sibling_discount ==1 ){
                          if(!empty($settings->sibling_discount)){
                              $discount_sibling = $amount_head*$settings->sibling_discount/100;
                              $amount_head = $amount_head - $discount_sibling; 
                              $sums = $sums-round($discount_sibling,0);
                          }
                      } 
                  }


                  ?>
                   <tr>
                    <td>
                    <input type="hidden" name="FeePlan[fee_head_id][]" id="" value="<?= $getFeeDetails->fk_fee_head_id ?>">
                    <?= $getHead->title;?></td>
                    <td> 
                    <input type="hidden" name="transaction_head_amount[<?=$getFeeDetails->fk_fee_head_id?>]" id="head_hidden_discount_amount" value="<?=round($getFeeDetails->amount,0)?>"/>
                        <?='Rs. '.round($amount_head,0)?> 
                    </td>
                    <td>
                    <!-- <input type="text" class="headDiscountInput" name="FeePlan[discount][]"> -->
                    <?php 
                      if($getHead->discount_head_status ==1){
                            echo Html::a('<i class="fa fa-money fa-2" aria-hidden="true"></i>','javascript:void(0);',['data-head_id'=>$getFeeDetails->fk_fee_head_id,'data-head_name'=>$getHead->title,'data-head_amount'=>$amount_head,'data-toggle'=>"modal" ,'data-target'=>"#discount-details",'id'=>'discount-modal']);
                            
                        }
                            ?>
                            <div id="show_head_<?=$getFeeDetails->fk_fee_head_id?>" class="show-head">
                                <input type="hidden" name="FeePlan[dicount][]" id="head_hidden_discount_amount" value="0"/>
                                <!-- <input type="hidden" name="FeePlan[dicount][<?//=$getFeeDetails->fk_fee_head_id?>]" id="head_hidden_discount_amount" value="0"/> -->
                                <input type="hidden" name="head_hidden_discount_type[<?=$getFeeDetails->fk_fee_head_id?>]" id="head_hidden_discount_type" value="0"/>
                                <span></span>
                            </div>
                    </td>
                  </tr>
                  <?php $i++;}; ?> 
                  <tr class="warning">
                  <td></td>
                    <th>Total: <span  id="total-amount" data-total="<?=$sums?>"><?=round($sums,0);?></span></th>
                    <th><span>Discount: </span><span class="total-discount">Rs. 0</span></th>
                  </tr>
                  <tr>
                  <td></td>
                  <td></td>
                  <th>Net Amount: <span id="net-amount" style="color:#000;"  data-net="<?=round($sums,0)?>"><?=
                         'Rs. '. round($sums,0); ?></span> 
                  </th>
                  </tr>
                  <tr>
                  

                    <input type="hidden" id="input_total_hostel_fare" class="form-control" name="StudentDisount[input_total_hostel_fare]" value="0" >
                    <input type="hidden" id="input_total_transport_fare"  data-totaltrnsprt="0" class="form-control" name="StudentDisount[input_total_transport_fare]" value="0"  style="width: 100px;">
                    <input type="hidden" id="input_total_discount" class="form-control" name="StudentDisount[input_total_discount]" value="" >
                    <input type="hidden" id="input_total_amount_payable" class="form-control" name="StudentDisount[input_total_amount_payable]" value="<?=$sums;?>">
                </td>
                    
                    </td>
                  </tr>  
                </tbody>
              </table>

<?php
             /*generate pdf model containin deneral data.*/
Modal::begin([
    'header'=>'<h4><span id="discount"></span>Add Discount</h4>',
    'footer'=>'<button type="button" class="btn green-btn pull-left" id="close-disount-modal" data-dismiss="modal">Close</button><button class="btn green-btn pull-right" id="add-discount-head">Add Discount</button>',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => false],
    'id' => 'discount-details',
    'size'=>'modal-md',
    'class' => '',
]);
?>
<input type="hidden" name="head-modal-id" id="hidden-head-id"/>
<input type="hidden" name="head-modal-amount" id="hidden-head-amount"/>
<div class="row">
    <div class="col-md-3" id="head-name"></div>
    <div class="col-md-4">
        <div id="discount_radolist" aria-invalid="false">
            <label class="modal-radio">
                <input type="radio" name="amount_type" value="percent" tabindex="3" checked><i></i><span>%age</span>
            </label>
            <label class="modal-radio">
                <input type="radio" name="amount_type" value="amount" tabindex="3"><i></i><span>Amount</span>
            </label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <?= Html::dropDownList('discount_type', null, $discount_type,['prompt'=>'Select Discount Type','class'=>'form-control','id'=>'discount-type']) ?>
            <div class="help-block"></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <input type="number" class="form-control" name="amount" id="head-amount" placeholder="Enter Amount in Percentage"/>
            <div class="help-block"></div>
        </div>
    </div>
</div>

<?php
Modal::end();
?>