/**
 * Developed by salman khan <salman.u360@gmail.com>on 20/2/2017.
 * reports.js
 * for reports related js.
 */


$(document).ready(function() {
  $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
    e.preventDefault();
    $(this).siblings('a.active').removeClass("active");
    $(this).addClass("active");
    var index = $(this).index();
    var url  =  $(this).attr('href');
    //alert(url);
    /*ajax call*/
    if(index != 0){
      $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: $('#exam-form').serialize(),
        success: function(data)
        {
         // $("#getTransport").html(data.viewtransport);
          //$("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).html("hi there");
          //$("#getWidthdrawl").html(data.withdrawlStu);
        }
      });
    }


    $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
    $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
  });
});

/*show overall student attendance*/
$(document).on('click','#overallAtt',function(){

  $('.submitAttendance').show();
  $('.submitcls').hide();
  //$('.showDate').show();
  $('#displayclasses').hide();
   $('#overallsCls').hide();
  $('#overalls').show();
  $("#loading").hide();



 });

$(document).on('click','#other',function(){
  $('.submitAttendance').hide();
  $('.submitcls').show();
  //$('.showDate').hide();
  $('#displayclasses').show();
  $('#overalls').hide();
   $('#overallsCls').show();
  $("#loading").hide();

 });


/*$(document).on('change','#class-id',function(){
        var id=$(this).val();
        var group_id=$('#group-id').val();
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",

            data: {id:id,group_id:group_id} ,
            url: url,
            cache: false,
            success: function(html)
            {
             // console.log(html);
                $("#group-id").html(html);
            } 
        });
        
    });*/

$(document).on('change','#group-id',function(){

       // alert('sadf');
        var id=$(this).val();
        //alert(id);
        var url=$(this).data('url');
        //alert(url);
        var dataString = 'id='+ id;
        //alert(dataString);
        $.ajax
        ({
            type: "POST",
            data: dataString,
            url: url,
            cache: false,
            success: function(html)
            {
                $("#sections-id").html(html);
            } 
        });
        
    });



$(document).on('click','.submitAttendance',function(){
  var url=$(this).data('url');
  var start=$('#startDate').val();
  var end=$('#endDate').val();

  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {start:start,end:end},
        success: function(data)
        {
          //console.log(data);

          $("#overalls").html(data.overallview);
          //$("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).html("hi there");
          //$("#getWidthdrawl").html(data.withdrawlStu);
        }
      });
 

});

$(document).on('click','.submitcls',function(){
  var url=$(this).data('url');
  var start=$('#startDate').val();
  var end=$('#endDate').val();
  var cls=$('#class-id').val();
  var grp=$('#group-id').val();
  var sectn=$('#section-ids').val();
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {start:start,end:end,cls:cls,grp:grp,sectn:sectn},
        success: function(data)
        {
          $("#overallsCls").html(data.overallclass);
        }
      });
});
$(document).on('click','#submitgrps',function(){
  var url=$(this).data('url');
  var start=$('#startDate').val();
  var end=$('#endDate').val();
  var grp=$('#pasGrp').val();
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {start:start,end:end,grp:grp},
        success: function(data)
        {
          $("#overallsGrps").html(data.overallgrps);
        }
      });
});

/*  trnasport */
$(document).on('click','#paszonetoroute',function(){
  var url=$(this).data('url');
  var zoneid=$(this).data('zoneid');
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {zoneid:zoneid},
        success: function(data)
        {$(".showalltransport").html(data.zoneRoutes);
        }
      });
});
$(document).on('click','#pasroutetostop',function(){
  var url=$(this).data('url');
  var routeid=$(this).data('routeid');
  var zoneid=$(this).data('zoneid');
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {routeid:routeid,zoneid:zoneid},
        success: function(data)
        {
          $(".showalltransport").html(data.stopRoutes);
        }
      });
});
$(document).on('click','#passtoptostudent',function(){
  $('#loading').show();
  var url=$(this).data('url');
  var stopid=$(this).data('stopid');
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {stopid:stopid},
        success: function(data)
        {
          $('#loading').hide();
          $("#modalContent").html(data.stuView);
          $('#modal').modal('show');
        }
      });
});
$(document).on('click','#zonebacktrack',function(){
var url=$('#zone').data('url');
$.ajax
        ({
            type: "POST",
            dataType:"JSON",
            url: url,
            cache: false,
            success: function(html)
            {
              //console.log(html.zonegenric);
                $(".showalltransport").html(html.zonegenric);
            } 
        });
});


$(document).on('click','#routebacktrack',function(){
  var url=$(this).data('url');
  var zoneid=$('#zoneId').val();
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {zoneid:zoneid},
        success: function(data)
        {
          $(".showalltransport").html(data.zoneRoutes);
        }
      });
});
// start of finance
$(document).on('click','.cashflow',function(){
  var url=$(this).data('url');
  var start=$('#startDate').val();
  var end=$('#endDate').val();
  var attrname = $(this).attr('name'); 
  $("#cashflowCalendar").show();
  //alert(url);

  if(attrname =='Generate Report'){
      window.location.replace(url+"?start="+start+"&end="+end);
  }
  else{
    $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {start:start,end:end},
        success: function(data)
        {
          //console.log(data);

          $("#cashflowhere").html(data.cashflowhere);
          //$("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).html("hi there");
          //$("#getWidthdrawl").html(data.withdrawlStu);
        }
      });
 
  }

});



$(document).on('change','#getStuClassWise',function(){
  var url=$(this).data('url');
  var id=$(this).val();
  $('.showStu').show();
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {id:id},
        success: function(data)
        {
            $('#generate-std-ledger-pdf').hide();
            //console.log(data);
            $(".stu").html(data.studata);
        }
      });
 

});

/*another repoort at the end*/
$(document).on('change','#getAnotherStuClassWise',function(){
    var url=$(this).data('url');
    var id=$(this).val();
    $('.showStuAnother').show();
    $.ajax
    ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {id:id},
        success: function(data)
        {
            //console.log(data);
            $(".anotherstudentdata").html(data.studata);
        }
    });
});

$(document).on('change','.stu',function(){
  var url=$(this).data('url');
    var classId = $('#getStuClassWise').val();
  var stu_id=$(this).val();
    var reportUrl = $('#generate-std-ledger-pdf').data('url');
    $('#generate-std-ledger-pdf').hide();
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {stu_id:stu_id},
        success: function(data)
        {
          //console.log(data);

         $(".studentdata").html(data.studatas);
            $('#generate-std-ledger-pdf').attr('href',reportUrl+'?class_id='+classId+'&stu_id='+stu_id);
            if(data.countChallan) {
                //$('#generate-std-ledger-pdf').attr('data-stu',stu_id);
                $('#generate-std-ledger-pdf').show();
            }
        }
      });
 

});


$(document).on('click','.headWise',function(){

  var url=$(this).data('url');
  var start=$('#startDates').val();
  var end=$('#endDates').val();
  var attrname = $(this).attr('name'); 
  //alert(attrname);

  if(attrname =='Generate Report'){
      window.location.replace(url+"?start="+start+"&end="+end);
  }else{


 
    $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {start:start,end:end},
        success: function(data)
        {
          //console.log(data);

          $(".headwise-pay").html(data.cashflowhere);
          //$("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).html("hi there");
          //$("#getWidthdrawl").html(data.withdrawlStu);
        }
      });
    }
 

});



$(document).on('click','#cashInflowclasswise',function(){
  var url=$(this).data('url');
  var date=$(this).data('date');
  $("#cashflowCalendar").hide();
  //alert(date);
 
    $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {date:date},
        success: function(data)
        {
          //console.log(data);
         // $('#modal').modal('show');

          //$("#modalContents").html(data.cashflowclass);
          $("#cashflowhere").html(data.cashflowclass);
          
        }
      });
 

});

$(document).on('click','#classwiseDetail',function(){
  var url=$(this).data('url');
  var classid=$(this).data('classid');
  var dates=$(this).data('dates');
  //alert(dates);
 
    $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {classid:classid,dates:dates},
        success: function(data)
        {
          //console.log(data);
         // $('#modal').modal('show');

          //$("#modalContents").html(data.cashflowclass);
          $("#cashflowhere").html(data.cashflowclasswise);
          
        }
      });
 

});

// end of finance



// yearly admission report


$(document).on('change','.YearCal',function(){
  //alert('adfadf');
  var url=$(this).data('url');
  var year=$(this).val();
  $('.yearCalendar').show();

  //alert(dates);
 
    $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {year:year},
        success: function(data)
        {
          //console.log(data);
         
          $(".getYearadmission").html(data.getYearadmission);
          
        }
      });
 

});


$(document).on('click','.YearCals',function(){
  //alert('adfadf');
  var url=$(this).data('url');
  var year=$(this).data('year');
  $('.yearCalendar').show();

  //alert(dates);
 
    $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {year:year},
        success: function(data)
        {
          //console.log(data);
         
          $(".getYearadmission").html(data.getYearadmission);
          
        }
      });
 

});


$(document).on('click','.classwiseYearAdmisn',function(){
  var url=$(this).data('url');
  var years=$(this).data('year');
  //alert(years);
  $('.yearCalendar').hide();
  var attrname = $(this).attr('name'); 
  //alert(years);
 // alert(attrname);
  if(attrname =='Generate Report'){
      window.location.replace(url+"?years="+years+"&attrname="+attrname);
  }else{
  //alert(url);
 
    $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {years:years},
        success: function(data)
        {
          //console.log(data.getYearadmissionClasswise);
         
          $(".getYearadmission").html(data.getYearadmissionClasswise);
          
        }
      });
    }
   });


$(document).on('click','.classwiseYearAdmisnStudents',function(){
  var url=$(this).data('url');
  var classid=$(this).data('classid');
  var years=$(this).data('year');

  //alert(years);


  $('.yearCalendar').hide();
  var attrname = $(this).attr('name'); 
  //alert(attrname);



  if(attrname =='Generate Report'){
      window.location.replace(url+"?years="+years+"&classid="+classid);
  }else{
  //alert(url);
 
    $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {classid:classid,years:years},
        success: function(data)
        {
          //console.log(data.getYearadmissionClasswise);
         
          $(".getYearadmission").html(data.getYearadmissionClasswiseStudents);
          
        }
      });
    }
   });



// end of yealy admissin report

/*class wise result sheet */
$(document).on('click', '#class-wise-result-sheet-btn', function (e) {
    var classId = $('#class-id').val();
    var groupId = $('#group-id').val();
    var examType = $('#exam-type-id').val();
    var examSection = $('#exam-section-id').val();
    var url = $(this).data('url');
    getClassWiseResultSheet(classId,examType,examSection,groupId,url);
});

function getClassWiseResultSheet(classId,examType,examSection,groupId,url){
    var errors=0;
    $("#generate-report-sheet-btn").hide();
    if(classId ==''){
        var title = $('#class-id').closest('.form-group').find('.control-label').text();

        $('#class-id').closest('.form-group').addClass('has-error');
        $('#class-id').closest('.form-group').removeClass('has-success');
        $('#class-id').closest('.form-group').find('.help-block').html(title+' cannot be blank');
        errors++;
    }
    else{
        $('#class-id').closest('.form-group').addClass('has-success');
        $('#class-id').closest('.form-group').removeClass('has-error');
        $('#class-id').closest('.form-group').find('.help-block').html('');
    }

    if(examType ==''){
        title = $('#exam-type-id').closest('.form-group').find('.control-label').text();
        $('#exam-type-id').closest('.form-group').addClass('has-error');
        $('#exam-type-id').closest('.form-group').removeClass('has-success');
        $('#exam-type-id').closest('.form-group').find('.help-block').html(title+' cannot be blank');
        errors++;
    }else{
        $('#exam-type-id').closest('.form-group').addClass('has-success');
        $('#exam-type-id').closest('.form-group').removeClass('has-error');
        $('#exam-type-id').closest('.form-group').find('.help-block').html('');
    }
    if(examSection ==''){
        title = $('#exam-section-id').closest('.form-group').find('.control-label').text();
        $('#exam-section-id').closest('.form-group').addClass('has-error');
        $('#exam-section-id').closest('.form-group').removeClass('has-success');
        $('#exam-section-id').closest('.form-group').find('.help-block').html(title+' cannot be blank');
        errors++;
    }else{
        $('#exam-section-id').closest('.form-group').addClass('has-success');
        $('#exam-section-id').closest('.form-group').removeClass('has-error');
        $('#exam-section-id').closest('.form-group').find('.help-block').html('');
    }

    if(errors ==0){
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            url: url,
            data: {class_id:classId,group_id:groupId,exam_type:examType,section:examSection},
            success: function(data)
            {
                if(data.status==1){
                    $("#displaysearch").html(data.details);
                    $("#generate-report-sheet-btn").show();
                }


            }
        });
    }
}
/*$('body').bind('keypress',function (event){
 if (event.keyCode === 13){
 alert('ere');
 }
 });*/


 /* new admission class wise pdf */
 $(document).on('click','#newAdmissionClassWise',function(){
//alert('asdfasdf');
  var url=$(this).data('url');

  var name= $(this).attr('name');
  
  if(name == 'Generate Report'){
    window.location.replace(url+"?name="+name); 
  }
 });

  $(document).on('click','#promotedClassWise',function(){
//alert('asdfasdf');
  var url=$(this).data('url');

  var name= $(this).attr('name');
  
  if(name == 'Generate Report'){
    window.location.replace(url+"?name="+name); 
  }
 });


  $(document).on('click','#overallTransport',function(){
//alert('asdfasdf');
  var url=$(this).data('url');

  var name= $(this).attr('name');
  
  if(name == 'Generate Report'){
    window.location.replace(url+"?name="+name); 
  }
 });

   $(document).on('click','#overalltransportzone',function(){
  var url=$(this).data('url');
  var name= $(this).attr('name');
  if(name == 'Generate Report'){
    window.location.replace(url+"?name="+name); 
  }
 });

  $(document).on('click','#overalltransportroute',function(){
  var url=$(this).data('url');
  var name= $(this).attr('name');
  var zoneid=$(this).data('zoneid');
  
  if(name == 'Generate Report'){
    window.location.replace(url+"?name="+name+"&zoneid="+zoneid); 
  }
 });


  $(document).on('click','#stopwise',function(){
  var url=$(this).data('url');
  var name= $(this).attr('name');
  var zoneid=$(this).data('zoneid');
  var routeid=$(this).data('route');
  
  if(name == 'Generate Report'){
    window.location.replace(url+"?name="+name+"&zoneid="+zoneid+"&routeid="+routeid); 
  }
 });
 /* end of new admission class wise pdf */


$(document).on('click','.studentOverlReport',function(){
  
  var url=$(this).data('url');
  var startdate=$("#startDatess").val();
  var enddate=$("#endDatess").val();

  var attrname=$(this).attr('name');
  if(attrname =='Generate Report'){
      window.location.replace(url+"?startdate="+startdate+"&enddate="+enddate);
  }
  else{
 
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {startdate:startdate,enddate:enddate},
        success: function(data)
        {
            console.log(data);
            $(".showOverallStudent").html(data.overallstudents);
        }
      });
    }
});


$(document).on('change','#yearLeave',function(){
  var url=$(this).data('url');
  var years=$(this).val();
  $('#showPdfLeave').show();
    $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {years:years},
        success: function(data)
        {
          $("#showleavestu").html(data.showleavestu);
        }
      });
    
   });

 $(document).on('click','#yearlevpdf',function(){
  var url=$(this).data('url');
  var years=$("#yearLeave").val();
  var attrname = $(this).attr('name'); 
  if(attrname =='Generate Report'){
    window.location.replace(url+"?years="+years);
  }
   });

  $(document).on('click','.leaveYear',function(){
  var url=$(this).data('url');
  var years=$(this).data('year');
   $('#showPdfLeave').hide();
   $('#leaveYearpdf').show();
    $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {years:years},
        success: function(data)
        {
          $("#showleavestu").html(data.showleavestu); 
        }
      });
   });


  $(document).on('click','#levyearpdf',function(){
  var url=$(this).data('url');
  var years=$("#yearLeave").val();
  var attrname = $(this).attr('name'); 
  if(attrname =='Generate Report'){
    window.location.replace(url+"?years="+years);
  }
   });


$(document).on('click','.leaveYearstud',function(){
  var url=$(this).data('url');
  var years=$(this).data('year');
  var clas=$(this).data('clas');
  $('#clsxId').val(clas);
  $('#leaveYearpdf').hide();
  $('#leaveYearstudpdf').show();
    $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {years:years,clas:clas},
        success: function(data)
        {
          $("#showleavestu").html(data.showleavestu);  
        }
      });
   });


 $(document).on('click','#leaveYearstudntpdf',function(){
  var url=$(this).data('url');
  var years=$("#yearLeave").val();
  var clas=$('#clsxId').val();
  var attrname = $(this).attr('name'); 
  if(attrname =='Generate Report'){
    window.location.replace(url+"?years="+years+"&clas="+clas);
  }
   });


/*student class wise attendance*/
$(document).on('change','#getStuClassWiseStu',function(){

  var url=$(this).data('url');
  //alert(url);
  var id=$(this).val();
  $('.showStu').show();
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {id:id},
        success: function(data)
        {
            //$('#generate-std-ledger-pdf').hide();
            //console.log(data);
            $(".studnts").html(data.studata);
        }
      });
});


$(document).on('change','.studnts',function(){
  var url=$(this).data('url');
  var classId = $('#getStuClassWise').val();
    //alert(classId);
   // return false;
  var stu_id=$(this).val();
    var reportUrl = $('#generate-std-ledger-pdf').data('url');
    $('#generate-std-ledger-pdf').hide();
  $.ajax
      ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {stu_id:stu_id},
        success: function(data)
        {
          $('.attendance').html(data.attendance);
          //console.log(data);

         //$(".studentdata").html(data.studatas);
            //$('#generate-std-ledger-pdf').attr('href',reportUrl+'?class_id='+classId+'&stu_id='+stu_id);
            /*if(data.countChallan) {
                //$('#generate-std-ledger-pdf').attr('data-stu',stu_id);
                $('#generate-std-ledger-pdf').show();
            }*/
        }
      });
 

});

/*end of student class wise attendance*/


/*start of get data on basis of class*/

$(document).on('change','#getdataclass',function(){
        var id=$(this).val();
        $('#classdatagroup').empty();
        $('#classdatasection').empty();
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",

            data: {id:id} ,
            url: url,
            cache: false,
            success: function(html)
            {
             //console.log(html.counStudent);
                $("#classdatagroup").html(html.groupdata);
                $("#classdatasection").html(html.sectiondata);
                $("#counStudent").html(html.counStudent);
            } 
        });
        
    });

$(document).on('change','#classdatagroup',function(){
        var classid=$('#getdataclass').val();
        var id=$(this).val();
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",

            data: {id:id,classid:classid} ,
            url: url,
            cache: false,
            success: function(html)
            {
             //console.log(html.counStudent);
                $("#classdatasection").html(html.sectiondata);
                $("#counStudent").html(html.counStudent);
            } 
        });
        
    });


$(document).on('change','#classdatasection',function(){
        var classid=$('#getdataclass').val();
        var groupid=$('#classdatagroup').val();
        var id=$(this).val();
        var url=$(this).data('url');
    $("#counStudent").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",

            data: {id:id,classid:classid,groupid:groupid} ,
            url: url,
            cache: false,
            success: function(html)
            {
             //console.log(html.counStudent);
               $("#counStudent").html(html.counStudent);
            } 
        });
        
    });



$(document).on('click','#counStudent',function(){
        var classid=$('#getdataclass').val();
        var groupid=$('#classdatagroup').val();
        var sectionid=$('#classdatasection').val();
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",

            data: {classid:classid,groupid:groupid,sectionid:sectionid} ,
            url: url,
            cache: false,
            success: function(html)
            {
             //console.log(html.counStudent);
               $("#counStudent").html(html.counStudent);
            } 
        });
        
    });
/*end  of get data on basis of class*/


/*library user type click*/
$(document).on('change','.userType',function(){
  var value=$(this).val();
  
  if(value == 1){
    
    $('.getClassForLibrary').slideDown('slow');
    $('.employeshowForLibrary').slideUp('slow');
  }else if(value == 2){
   // alert(value);

    $('.employeshowForLibrary').slideDown('slow');
    $('.getClassForLibrary').slideUp('slow');
    $('.showClassWiseStudent').hide();
    
  }else{
    $('.employeshowForLibrary').slideUp('slow');
    $('.getClassForLibrary').slideUp('slow');
  }
});


$(document).on('change','#bookissue-status',function(){
  var value=$(this).val();
  
  if(value == 'renewal'){
    
    $('#renewalBook').slideDown('slow');
  }else if(value == 'return'){
    $('#renewalBook').slideUp('slow');

  }else{
    $('#renewalBook').slideUp('slow');
  }
});
/*end oflibrary user type click*/


/*============= expense report script*/
$(document).on('click','#dateExpenseReport',function(){

        var start=$('#startdateExpense').val();
        var end=$('#enddateExpense').val();
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {start:start,end:end} ,
            url: url,
            cache: false,
            success: function(html)
            {
                $("#showDateExpense").html(html.showDateExpense);
            } 
        });
        
    });

$(document).on('click','#dateExpensePdf',function(){
  var url=$(this).data('url');
  var start=$(this).data('start');
  var end=$(this).data('end');
  var attrname = $(this).attr('name');
  window.location.replace(url+"?start="+start+"&end="+end);
  
});
/*============= end of expense report script*/

/*============= receivalble report script*/
$(document).on('click','#dateReceivableReport',function(){
        var start=$('#startdateReceivablee').val();
        var end=$('#enddateReceivable').val();
        var url=$(this).data('url');
         $("#showDateReceivable").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {start:start,end:end} ,
            url: url,
            cache: false,
            success: function(html)
            {
                $("#showDateReceivable").html(html.showDateReceivable);
            } 
        });
        
    });

/*$(document).on('click','#dateExpensePdf',function(){
  var url=$(this).data('url');
  var start=$(this).data('start');
  var end=$(this).data('end');
  var attrname = $(this).attr('name');
  window.location.replace(url+"?start="+start+"&end="+end);
  
});*/
/*============= end of receivalble report script*/

/*============= student slc and other report script*/
$(document).on('change','.classwisestudent',function(){
        var stuId=$(this).val();
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {stuId:stuId} ,
            url: url,
            cache: false,
            success: function(html)
            {
                $("#showStudentDetails").html(html.showStudentDetails);
            } 
        });
        
    });

/*start of fee reports*/
$(document).on('change','#studentFeeOne',function(){
        var stuId=$(this).val();
        var classId=$('#studentClassWise').val();
        var url=$(this).data('url');
    $("#studentFeeDetails").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");

        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {stuId:stuId,classId:classId} ,
            url: url,
            cache: false,
            success: function(html)
            {
                $("#studentFeeDetails").html(html.studentFeeDetails);
            } 
        });
        
    });
// fee recv
$(document).on('change','#studentFeeTwo',function(){
        var stuId=$(this).val();
        var classId=$('#studentClassWise').val();
        var url=$(this).data('url');
    $("#studentFeeRcv").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {stuId:stuId,classId:classId} ,
            url: url,
            cache: false,
            success: function(html)
            {
                $("#studentFeeRcv").html(html.studentFeeRcv);
            } 
        });
    });
/*fee arrear*/
$(document).on('change','#studentFeeThree',function(){
        var stuId=$(this).val();
        var classId=$('#studentClassWise').val();
        var alumniStudents=$('#alumniStudents').val();
        var url=$(this).data('url');
    $("#studentArrear").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {stuId:stuId,classId:classId,alumniStudents:alumniStudents} ,
            url: url,
            cache: false,
            success: function(html)
            {
                $("#studentArrear").html(html.studentArrear);
            } 
        });
    });
$(document).on('change','#yearlyFeeReport',function(){
        var year=$(this).val();
        var url=$(this).data('url');
    $("#getYearlyReport").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {year:year},
            url: url,
            cache: false,
            success: function(html)
            {
                $("#getYearlyReport").html(html.getYearlyReport);
            } 
        });
    });

/*get yearly report of class and section wise student*/
$(document).on('change','#yearlyFeeReportClassWise',function(){
        var year=$(this).val();
        var class_id=$('.classIdYearly').val();
        var student_id=$('#studentFee14').val();
        var url=$(this).data('url');
    $("#yearlyFeeReportClassWiseStudents").append("<div id='loading'><img class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {year:year,class_id:class_id,student_id:student_id},
            url: url,
            cache: false,
            success: function(html)
            {
                $("#yearlyFeeReportClassWiseStudents").html(html.yearlyFeeReportClassWiseStudents);
            } 
        });
    });

$(document).on('change','#monthlyDateFeeStudentsRcv',function(){
        var month=$(this).val();
        var year=$('#yearlyFeeReportClassWise').val();
        var class_id=$('.classIdYearly').val();
        var student_id=$('#studentFee14').val();
        var url=$(this).data('url');
    $("#yearlyFeeReportClassWiseStudents").append("<div id='loading'><img class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {month:month,year:year,class_id:class_id,student_id:student_id},
            url: url,
            cache: false,
            success: function(html)
            {
                $("#yearlyFeeReportClassWiseStudents").html(html.yearlyFeeReportClassWiseStudents);
            } 
        });
    });
/*get yearly Fee report of classwise student*/
// var radioValue= $('input[name="dateWiseClassLedger"]:checked').val();
$(document).on('change','#yearlyClassFeeReport',function(){
        var year=$(this).val();
        var class_id = $('#class-id').val(); 
        var group_id = $('#group-id').val(); 
        var section_id = $('#section-id').val(); 
        var url=$(this).data('url');
        if(group_id ==''){ 
        group_id=null;
        }
        $("#classwise-fee-report-yearly").empty().append("<div id='loading'><img class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {year:year,class_id:class_id,group_id:group_id,section_id:section_id},
            url: url,
            cache: false,
            success: function(html)
            {
                $("#classwise-fee-report-yearly").html(html.data);
            } 
        });
    });
$(document).on('change','#enddateFeeLedger',function(){
        var year=$(this).val();
        var class_id = $('#class-id').val(); 
        var group_id = $('#group-id').val(); 
        var section_id = $('#section-id').val(); 
        var startdate = $('#startdateFeeLedger').val(); 
        var enddate = $(this).val(); 
        var url=$(this).data('url');
        if(group_id ==''){ 
        group_id=null;
        }
        $("#classwise-fee-report-yearly").empty().append("<div id='loading'><img class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {startdate:startdate,enddate:enddate,class_id:class_id,group_id:group_id,section_id:section_id},
            url: url,
            cache: false,
            success: function(html)
            {
                $("#classwise-fee-report-yearly").html(html.data);
            } 
        });
    });
$(document).on('change','.studentIdSlc',function(e){
         e.preventDefault();
         e.stopImmediatePropagation();
    var radioValue= $('input[name="generalCertificate"]:checked').val();
        var id=$(this).val();
        var url=$(this).data('url');
    //$("#studentInactive").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");

        $.ajax
        ({
            type: "POST",
            data: {id:id,radioValue:radioValue},
            url: url,
            cache: false,
            success: function(html)
            {
             // $("#studentInactive").hide();
            } 
        });
    });
/*end of fee reports*/
/*get Employee Experiance certificate*/
$(document).on('change','#employeeCertificateCalendar',function(e){
         e.preventDefault();
         e.stopImmediatePropagation();
         var date=$(this).val();
        var id=$('#employeeCertificate').val();
        var url=$('#employeeCertificate').data('url');
        $.ajax
        ({
            type: "POST",
            data: {date:date,id:id},
            url: url,
            cache: false,
            success: function(html)
            {
            } 
        });
    });

$(document).on('click','#studentFeeDetailYearly',function(){
  $('#yearlyFeeReportClassWiseStudents').empty();
    $('#monthlyStudentFee').hide();   
    $('#yearlyStudentFee').show();   
  });
$(document).on('click','#studentFeeDetailMonthly',function(){
  $('#yearlyFeeReportClassWiseStudents').empty();
    $('#monthlyStudentFee').show();   
    $('#yearlyStudentFee').hide();   
  });
$(document).on('change','#todayLedger',function(){
        var class_id=$(this).val();
        var url=$(this).data('url');
    $("#todayClassLedgerView").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");

        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {class_id:class_id},
            url: url,
            cache: false,
            success: function(html)
            {
                $("#todayClassLedgerView").html(html.data);

            } 
        });
    });$(document).on('change','#todayLedger',function(){
        var class_id=$(this).val();
        var url=$(this).data('url');
    $("#todayClassLedgerView").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");

        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {class_id:class_id},
            url: url,
            cache: false,
            success: function(html)
            {
                $("#todayClassLedgerView").html(html.data);

            } 
        });
    });$(document).on('change','#todayLedger',function(){
        var class_id=$(this).val();
        var url=$(this).data('url');
    $("#todayClassLedgerView").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");

        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {class_id:class_id},
            url: url,
            cache: false,
            success: function(html)
            {
                $("#todayClassLedgerView").html(html.data);

            } 
        });
    });$(document).on('change','#todayLedger',function(){
        var class_id=$(this).val();
        var url=$(this).data('url');
    $("#todayClassLedgerView").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");

        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {class_id:class_id},
            url: url,
            cache: false,
            success: function(html)
            {
                $("#todayClassLedgerView").html(html.data);

            } 
        });
    });
$(document).on('change','#sportsArray',function(){
    var user_id=$('#studentFeeOne').val();
    var sportsName=$('#sportsArray option:selected').val();
    var url=$(this).data('url');
//$("#showLoader").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");

    $.ajax
    ({
        type: "POST",
        dataType:"JSON",
        data: {user_id:user_id,sportsName:sportsName},
        url: url,
        cache: false,
        success: function(html)
        {
        } 
    });
});
$(document).on('change','#previousSlip',function(){
var url=$(this).data('url');
var stu_id=$(this).val();
$.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {stu_id:stu_id},
            url: url,
            cache: false,
            success: function(html)
            {
                $("#showPreviousSlip").html(html.showPreviousSlip);
            } 
        });
});
$(document).on('change','#enddateFee',function(){
var url=$(this).data('url');
var startdate=$('#startdateFee').val();
var enddate=$(this).val();
$("#showDateWiseFee").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");

$.ajax
  ({
      type: "POST",
      dataType:"JSON",
      data: {startdate:startdate,enddate:enddate},
      url: url,
      cache: false,
      success: function(html)
      {
          $("#showDateWiseFee").html(html.showDateWiseFee);
      } 
  });
});
/*quiz report*/
$(document).on('change','#quizReport1',function(){
var radioValue= $('input[name="quizReport"]:checked').val();
if(radioValue == 'classWise'){
  var url=$('#class-wise-quiz').val();
}else if(radioValue == 'classQuizReport'){  
var url=$(this).data('url');
}else{
  var url=$(this).data('url');
}
var date=$(this).val();
var class_id=$('#getdataclass').val();
var group_id=$('#classdatagroup').val();
var subject_id=$('#getSubjectsdata').val();
if(group_id ==''){ 
        group_id=null;
    }
$("#getFirstReportData").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
$.ajax
  ({
      type: "POST",
      dataType:"JSON",
      data: {date:date,class_id:class_id,group_id:group_id,subject_id:subject_id},
      url: url,
      cache: false,
      success: function(html)
      {
          $("#getFirstReportData").html(html.getFirstReportData);
      } 
  });
});

$(document).on('click','#classSubjectQuizReport',function(){
   $('.classwisestudent').empty();
  $('#classSubjectHideShow').show();
   $('#quizDateHide').show();
   $('#removeClassQuiz').hide();
   $('#subjectClassHide').show();
   $('#groupShow').show();
   
 });

$(document).on('click','#classQuizReport',function(){
   $('.renderView').empty();
   $('#getSubjectsdata').empty();
  $('#classSubjectHideShow').hide();
   $('#quizDateHide').show();
   $('#removeClassQuiz').show();
   $('#subjectClassHide').hide();
   $('#groupShow').hide();
 });

/*quiz report*/
/*print age certificate*/
$(document).on('click','#findByRegisteration',function(){
  var reg=$("#registerationNO").val();
  var url=$(this).data('url');
  var attrname = $('#registerationNO').attr('name'); 
  if(attrname =='generate_report'){
  window.location.replace(url+"?reg="+reg);
 }else{
  $.ajax
  ({
      type: "POST",
      dataType:"JSON",
      data: {reg:reg},
      url: url,
      cache: false,
      success: function(html)
      {
          $("#studentQuizDetails").html(html.view);
      } 
  });
 }
    });
$(document).on('click','#subjectWiseDateQuiz',function(){
  var testId=$(this).data('testid');
  var url=$(this).data('url');
  $.ajax
  ({
      type: "POST",
      dataType:"JSON",
      data: {testId:testId},
      url: url,
      cache: false,
      success: function(html)
      {
        console.log(html.view);
          $(".renderView").html(html.view);
      } 
  });
 });
$(document).on('click','#classWiseDateQuiz',function(){
  var testId=$(this).data('testid');
  var url=$(this).data('url');
  $.ajax
  ({
      type: "POST",
      dataType:"JSON",
      data: {testId:testId},
      url: url,
      cache: false,
      success: function(html)
      {
          $(".classwisestudent").html(html.view);
      } 
  });
 });
$(document).on('change','.studentWiseQuizReport',function(){
  var class_id=$('#class-id').val();
  var group_id=$('#group-id').val();
  var section_id=$('#section-id').val();
  var stu_id=$(this).val();
  var url=$(this).data('url');
  $.ajax
  ({
      type: "POST",
      dataType:"JSON",
      data: {class_id:class_id,group_id:group_id,section_id:section_id,stu_id:stu_id},
      url: url,
      cache: false,
      success: function(html)
      {
          $("#studentQuizDetails").html(html.view);
      } 
  });
 });

$(document).on('click','#dateWiseClassLedger',function(){
$('#classwise-fee-report-yearly').empty();
$('#showDateWiseLedger').show();
$('#yearLedgerFee').hide();
});
$(document).on('click','#dateWiseYearLedger',function(){
$('#classwise-fee-report-yearly').empty();
$('#showDateWiseLedger').hide();
$('#yearLedgerFee').show();
});
/*new promotion student name*/
$(document).on('click','#newlyPromotedName',function(){
  $('#promtionClassDetails').empty();
  var class_id=$(this).data('classid');
  var url=$(this).data('url');
  $("#showNewlyPromotionName").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
  $.ajax
  ({
      type: "POST",
      dataType:"JSON",
      data: {class_id:class_id},
      url: url,
      cache: false,
      success: function(html)
      {
          $("#showNewlyPromotionName").html(html.view);
      } 
  });
 });

$(document).on('change','.promotedStudents',function(){
        var class_id=$('#getdataclass').val();
        var group_id=$('#classdatagroup').val();
        var section_id=$(this).val();
        var radioValue= $('input[name="promotionDemotion"]:checked').val();
        var url=$('#promotedDataUrl').val();
        if(group_id ==''){ 
        group_id=null;
        }
  $("#promotedStudentsShow").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
        $.ajax
        ({
            type: "POST",
            data: {class_id:class_id,group_id:group_id,section_id:section_id,radioValue:radioValue},
            url: url,
            cache: false,
            success: function(html)
            {
                $("#promotedStudentsShow").html(html);
            } 
        });
        
    });
$(document).on('change','#monthCalendar',function(){
        var date=$(this).val();
        var class_id = $('#class-id').val(); 
        var group_id = $('#group-id').val(); 
        var section_id = $('#section-ids').val(); 
        var url=$(this).data('url');
        if(group_id ==''){ 
        group_id=null;
        }
        $("#subject-inner").empty().append("<div id='loading'><img class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {date:date,class_id:class_id,group_id:group_id,section_id:section_id},
            url: url,
            cache: false,
            success: function(html)
            {
                $("#subject-inner").html(html.view);
            } 
        });
    });
/*new promotion student name ends*/