<?php 
use yii\helpers\Url;
 ?>
<ul class="nav nav-tabs">
<li class="active"><a href="#tab_6" data-toggle="tab">Fee Details</a></li>
<li><a href="#tab_16" data-toggle="tab">Previous Slip</a></li>
<!-- <li><a href="#tab_7" data-toggle="tab">Today Ledger</a></li> -->
<li><a href="<?php echo Url::to(['today-rcv'])?>">Today Ledger</a></li>
<li><a href="#tab_14" data-toggle="tab">Monthly/Yearly Ledger</a></li>
<li><a href="<?php echo Url::to(['date-ledger'])?>">Date ledger</a></li>
<li><a href="#tab_8" data-toggle="tab">Student Arrears</a></li>
<li><a href="#tab_10" data-toggle="tab">Class Arrears</a></li>
<!-- if needed then uncomment,overall class recv fee
<li><a href="#tab_11" data-toggle="tab">Class Fee Receive</a></li> -->
<!-- if needed then uncomment,overall recv fee year wise
<li><a href="#tab_13" data-toggle="tab">Yearly Receive Fee</a></li> -->
<li><a href="#tab_15" data-toggle="tab">Yearly Class Fee Receive</a></li>
<li><a href="<?php echo Url::to(['session-fee'])?>">Session Ledger</a></li>
<li><a href="<?php echo Url::to(['transport-fee'])?>">Transport Ledger</a></li>
<!-- <li><a href="#tab_20" data-toggle="tab">Yearly Class Fee Arrears</a></li> -->


<!-- <li><a href="#tab_1" data-toggle="tab">Daily Basis Cash Flow</a></li>
<li><a href="#tab_2" data-toggle="tab">Ledger</a></li>
<li><a href="#tab_3" data-toggle="tab">Head Wise Payment Rcv</a></li>
<li><a href="#tab_4" data-toggle="tab">Student OverAll Report</a></li>
<li><a href="#tab_5" data-toggle="tab">Student Class Wise Report</a></li> -->
</ul>