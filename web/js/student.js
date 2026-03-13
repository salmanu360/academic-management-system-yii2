/**
 * Student JS
 * Developed by salman khan <salman@kryptonstechnology.com> on 12/25/2015.
 */


$(document).on('click','#modal-pdf-detail',function () {
    var studentId = $(this).data('std_id');
    var studentName = $(this).data('std_name');
    var url         =  $(this).data('url');

    if(url){
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            url: url,
            data: {
                student_id:studentId,
            },
            success: function(data)
            {
                if(data.status== 1){
                    $("#exam_type_option").empty().html(data.options);

                    if(data.counter <= 0){
                        $('#print-pdf').addAttr({'disabled':true});
                    }else{
                        $('#print-pdf').addAttr({'disabled':false});
                    }
                }


            }
        });
    }
    $('#std_id').val(studentId);
    $('span#student-name').html(studentName);
});


$(document).on('click','button#print-pdf',function () {
    var html   = $(this).closest('.modal-content');
    var impToRead       = html.find('#imp-to-read').val();
    var exam            = html.find('#exam_type_option').val();
    var cmtCrd          = html.find('#comment-cordinator').val();
    var classTearcher   = html.find('#class_teacher').val();
    var areafocus1  = html.find('#area-to-focus-1').val();
    var areafocus2  = html.find('#area-to-focus-2').val();
    var areafocus3  = html.find('#area-to-focus-3').val();
    var url         = $(this).data('url');
    var studentId   = html.find('#std_id').val();
    var manners     = $('#manners').val();
    var confidence  = $('#confidence').val();
    var errors = 0;




    /*validate important to read*/
    if(exam ==''){
        html.find('#exam_type_option').css({'border-color':'red'});
        errors++;
    }else{
        html.find('#exam_type_option').removeAttr('style');
    }
    if(impToRead == ''){
        html.find('#imp-to-read').closest('div.field-imp-to-read').addClass('has-error');
        html.find('#imp-to-read').closest('.div.field-imp-to-read').removeClass('has-success');
        html.find('#imp-to-read').closest('div.field-imp-to-read').find('.help-block').html('Important to read can not be blank.');
        errors++;
    }
    else{
        html.find('#imp-to-read').closest('.field-imp-to-read').addClass('has-success');
        html.find('#imp-to-read').closest('.field-imp-to-read').removeClass('has-error');
        html.find('#imp-to-read').closest('.field-imp-to-read').find('.help-block').html('');
    }

    /*validate comment cordinator*/
    if(cmtCrd == ''){
        html.find('#comment-cordinator').closest('div.field-cmt-crdntr').addClass('has-error');
        html.find('#comment-cordinator').closest('.div.field-cmt-crdntr').removeClass('has-success');
        html.find('#comment-cordinator').closest('div.field-cmt-crdntr').find('.help-block').html('Comments of Coordinator can not be blank.');
        errors++;
    }
    else{
        html.find('#comment-cordinator').closest('.field-cmt-crdntr').addClass('has-success');
        html.find('#comment-cordinator').closest('.field-cmt-crdntr').removeClass('has-error');
        html.find('#comment-cordinator').closest('.field-cmt-crdntr').find('.help-block').html('');
    }

    /*validate manners*/
    if(manners == ''){
        html.find('#manners').closest('div.field-manners').addClass('has-error');
        html.find('#manners').closest('.div.field-manners').removeClass('has-success');
        html.find('#manners').closest('div.field-manners').find('.help-block').html('Manners rating is required.');
        errors++;
    }
    else{
        html.find('#manners').closest('.field-manners').addClass('has-success');
        html.find('#manners').closest('.field-manners').removeClass('has-error');
        html.find('#manners').closest('.field-manners').find('.help-block').html('');
    }

    /*validate confidence*/

    if(confidence == ''){
        html.find('#confidence').closest('div.field-confidence').addClass('has-error');
        html.find('#confidence').closest('.div.field-confidence').removeClass('has-success');
        html.find('#confidence').closest('div.field-confidence').find('.help-block').html('Confidence rating is required.');
        errors++;
    }
    else{
        html.find('#confidence').closest('.field-confidence').addClass('has-success');
        html.find('#confidence').closest('.field-confidence').removeClass('has-error');
        html.find('#confidence').closest('.field-confidence').find('.help-block').html('');
    }

    /*validate class teacher*/

    if(classTearcher == ''){
        html.find('#class_teacher').closest('div.field-class-teacher').addClass('has-error');
        html.find('#class_teacher').closest('.div.field-class-teacher').removeClass('has-success');
        html.find('#class_teacher').closest('div.field-class-teacher').find('.help-block').html('Class teacher is required.');
        errors++;
    }
    else{
        html.find('#class_teacher').closest('.field-class-teacher').addClass('has-success');
        html.find('#class_teacher').closest('.field-class-teacher').removeClass('has-error');
        html.find('#class_teacher').closest('.field-class-teacher').find('.help-block').html('');
    }

    if(errors > 0){
        return false;
    }
    else{
        $('#generate-dmc-form').submit();

    }
});

/*close pdf-details on close x btn and close-generate-pdf id button starts here*/
$(document).on('click','#pdf-details .close',function () {
    $(this).closest('.modal-content').find('input').val('');
    $('#manners').rating('clear');
    $('#confidence').rating('clear');
    $(this).closest('.modal-content').find('textarea#comment-cordinator').val('');
});

$(document).on('click','#close-generate-pdf',function () {
    $(this).closest('.modal-content').find('input').val('');
    $('#manners').rating('clear');
    $('#confidence').rating('clear');
    $(this).closest('.modal-content').find('textarea#comment-cordinator').val('');
});

/*close pdf-details on close x btn and close-generate-pdf id button ends here*/



/*--------------------get students class wise-------------------*/
/*student class wise attendance*/
$(document).on('change','#studentClassWise',function(){
  var url=$(this).data('url');
  var id=$(this).val();
  var studentAlumniCheck= $('input[name="studentAlumniCheck"]:checked').val();
  $('.showClassWiseStudent').show();
  $(".classwisestudent").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/42.gif' alt='Loading' /></div>");
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {id:id,studentAlumniCheck:studentAlumniCheck},
        success: function(data)
        {
            //$('#generate-std-ledger-pdf').hide();
            //console.log(data);
            $(".classwisestudent").html(data.studata);
        }
      });
});
/*--------------------end ofget students class wise-------------*/



/*student admission script, get class and group fee*/
$(document).on('change','.sectionPrev',function(){

  var id=$(this).val();
  var url=$('#getClassFee').val();
  var classId=$('#class-id').val();
  var groupId=$('#group-id').val(); 
  var parentCnic = $('input[name="StudentParentsInfo[cnic]"]').val();
   
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {id:id,classId:classId,groupId:groupId,parent_cnic:parentCnic},
        success: function(data)
        {
            $("#fee-details").html(data.viewFeedetails);
        }
      });
});

/*show submit button*/
$(document).on('change','#studentinfo-country_id',function(){
  var id=$(this).val();
  var netamntValue=$("#net-amount").data('net');
  if(id == ''){
  $('#showSubmitButtonAdmission').hide();
}else{
  $('#showSubmitButtonAdmission').show();

}
});
$(document).on('change','#class-id',function(){
  
  $('#fee-details').empty();
});


/*============update student form fee*/
function feeUpdate(sectionid){
  var id=sectionid;
  var url=$('#getClassFee').val();
  var classId=$('#class-id').val();
  var groupId=$('#group-id').val(); 
  var parentCnic = $('input[name="StudentParentsInfo[cnic]"]').val();
   
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {id:id,classId:classId,groupId:groupId,parent_cnic:parentCnic},
        success: function(data)
        {
            $("#fee-details").html(data.viewFeedetails);
        }
      });
}
/*============end of update student form fee*/

/*student discount first time*/
$(document).on('click','#discountModel',function(){
  var getvalue=$(this).data('headid');
  $('#unique_id').val(getvalue);
  /*var id=$(this).val();
  var url=$('#getClassFee').val();
  var classId=$('#class-id').val();
  var groupId=$('#group-id').val();
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {id:id,classId:classId,groupId:groupId},
        success: function(data)
        {
            console.log(data.viewFeedetails);
            $("#fee-details").html(data.viewFeedetails);
        }
      });*/
});
$(document).on('keyup','.headamount',function(){
  var inputVal=$(this).val();
  var totalHeadAmount=$('.totalHeadAmount').val();
  var netamount=$('#netAmount').val();
 // alert(netamount);
  var counts=0;
  $('.headamount').each(function () {
    counts += parseFloat(this.value);
});

//alert(counts);
//var getarears=totalHeadAmount-counts;
var assignArrear=$('.getDiscountAmount').val(counts);
// var assignNetAmount=$('#amountSpan').html(count)
  /*var nanamount=parseInt(netamount)-parseInt(count);
  $('.getDiscountAmount').val(nanamount);
    var assignNetAmount=$('#amountSpan').html(count);*/


});
$(document).on('keyup','.headDiscountInput',function(){
  var inputVal=$(this).val();
  var netamount=$('#netAmount').val();
  var headAmountFee=$('.headAmountFee').val();
  
  var totalSum = 0;
$('.headDiscountInput').each(function () {
    totalSum += parseFloat(this.value);
});

var values = [];
$("input[name='net_amount[]']").each(function() {
    values.push($(this).val());
});
alert('values');
if(isNaN(totalSum)) {
  var getPercentageminus=headAmountFee * inputVal;
  var totalPercent=getPercentageminus/100;
  //var totalGetPercent=headAmountFee - totalPercent;
  var getDiscountAmount=$('.getDiscountAmount').val(totalPercent);
  
  var nanamount=parseInt(netamount)-parseInt(totalPercent);
  var assignNetAmount=$('#amountSpan').html(nanamount);
}else{
    var getPercentageminus=headAmountFee * totalSum;
    alert(headAmountFee);

  var getDiscountAmount=$('.getDiscountAmount').val(totalSum);
  var totalNetamount=netamount-totalSum;
  var assignNetAmount=$('#amountSpan').html(totalNetamount);
}

});
// from and to date
$(document).on('change','.todate_admission',function(){
  var Fromdate=$('#from_date_admission').val();
  var toDate=$(this).val();
    var classid=$('#class_id').val();
    var groupid=$('#group_id').val();
    var sectionid=$('#section_id').val();
    var stu_id=$('#stu_id').val();
    var url=$('#getDateFee').data('url');
    var date1 = Fromdate;
    var date2 = toDate;
    var ts1 = new Date(date1);
    var ts2 = new Date(date2);
    var year1 = ts1.getFullYear();
    var year2 = ts2.getFullYear();
    var month1 = ts1.getMonth();
    var month2 = ts2.getMonth()+1; 
    var diff= ((year2 - year1) * 12) + (month2 - month1);
    //$("#challan-form-inner").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");

  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {diff:diff,classid:classid,groupid:groupid,sectionid:sectionid,stu_id:stu_id,Fromdate:Fromdate,toDate:toDate},
        success: function(data)
        {
            $('#challan-form-inner').html(data.html);

            
        }
      });
});

  /* =================== 
     Generate single student fee slip
     ===================
  */
$(document).on('click','#singleFeeSlip',function(){

  var formsearilze= $("#fee_submission_form").serialize();

  var Fromdate=$('#from_date_admission').val();
  var toDate=$('.todate_admission').val();
    var classid=$('#class_id').val();
    var groupid=$('#group_id').val();
    var stu_id=$('#stu_id').val();
    var netAmntForSlip=$('.netAmntForSlip').val();
    var arearAmount=$('#total-arrears-amount').val();
    var url=$(this).data('url');
    var date1 = Fromdate;
    var date2 = toDate;
    var ts1 = new Date(date1);
    var ts2 = new Date(date2);
    var year1 = ts1.getFullYear();
    var year2 = ts2.getFullYear();
    var month1 = ts1.getMonth();
    var month2 = ts2.getMonth()+1; 
    var diff= ((year2 - year1) * 12) + (month2 - month1);
    //window.location.replace(url+"?diff="+diff+"&classid="+classid+"&groupid="+groupid+"&stu_id="+stu_id+"&toDate="+toDate+"&arearAmount="+arearAmount+"&netAmntForSlip="+netAmntForSlip);

  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {formsearilze:formsearilze,classid:classid,groupid:groupid},
        success: function(data)
        {
            $('#challan-form-inner').html(data.html);
           
        }
      });
});
    /* =================== 
       end of Generate single student fee slip
       ===================
    */

//submit fee
$(document).on('click','#submitFee',function(){
    //alert('adf');
    //return false;
    var url=$(this).data('url');
    var Fromdate=$('#from_date_admission').val();
    var toDate=$('.todate_admission').val();
    var stu_id=$('#stu_id').val();
    var feeHeadId=$('.feeHeadId').map( function(){
                 return $(this).val(); 
                }).get();
    var headamount=$('.headamount').map( function(){
                 return $(this).val(); 
                }).get();
    var actualHeadAmount=$('.actualHeadAmount').map( function(){
                 return $(this).val(); 
                }).get();
    //alert(actualHeadAmount);
    //return false;
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {Fromdate:Fromdate,toDate:toDate,stu_id:stu_id,feeHeadId:feeHeadId,headamount:headamount,actualHeadAmount:actualHeadAmount},
        success: function(data)
        {
            //$("#fee-details").html(data.viewFeedetails);
           // $('#challan-form-inner').html(data.html);

            
        }
      });
});

/*--------------------end ofget students class wise-------------*/


