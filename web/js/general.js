/**
 * Developed by salman khan <salman.u360@gmail.com>on 20/2/2017.
 * reports.js
 * for General related js.
 */

 /*===================================================================
start of get class and group subjects, use for subject time table*/
/*==================================================================*/

$(document).on('change','#getdataclass',function(){
        var id=$(this).val();
        $('#classdatagroup').empty();
        $('#sectiondetails').empty();
        $('#getSubjectsdata').empty();
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
                 $("#getSubjectsdata").html(html.getSubjectsdata);

            } 
        });
        
    });
/*get subject of class and group*/
$(document).on('change','#classdatagroup',function(){
        var classid=$('#getdataclass').val();
        $('#getSubjectsdata').empty();

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
                 $("#getSubjectsdata").html(html.getSubjectsdata);
            } 
        });
        
    });

$(document).on('change','#getSubjectsdata',function(){
        var classid=$('#getdataclass').val();
        var groupid=$('#classdatagroup').val();
        var id=$(this).val();
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",

            data: {id:id,classid:classid,groupid:groupid} ,
            url: url,
            cache: false,
            success: function(html)
            {
                 //console.log(html.renderView);
                 $(".renderView").html(html.renderView);
                 $("#classtimetable-subjectid").val(html.subjectid);
                 $("#classtimetable-class_id").val(html.classid);
                 $("#classtimetable-group_id").val(html.groupId);
            } 
        });
        
    });


$(document).on('click','#save_timetable',function(e){
         e.preventDefault();
         e.stopImmediatePropagation();
        var classid=$('#classtimetable-class_id').val();
        var groupid=$('#classtimetable-group_id').val();
        var subjectid=$('#classtimetable-subjectid').val();
        var dayname=$('.classtimetable-day').map( function(){
                 return $(this).val(); 
                }).get();
        //var day = dayname.join(", ");
        //alert(dayname);
        //return false;
        var starttime=$('.starttime').map( function(){
                 return $(this).val(); 
                }).get();
       // var startString = starttime.join(", ");
        //alert(starttime);
        //return false;
        var endtime=$('.endtime').map( function(){
                 return $(this).val(); 
                }).get();
        //var endString = endtime.join(", ");

                //var starttime=$('.starttime').val();
       // var endtime=$('.endtime').val();
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",

            data: {classid:classid,groupid:groupid,subjectid:subjectid,dayname:dayname,starttime:starttime,endtime:endtime} ,
            url: url,
            cache: false,
            success: function(html)
            {
                 //console.log(html.renderView);
                 $(".renderView").html(html.renderView);
                 $("#classtimetable-subjectid").val(html.subjectid);
                 $("#classtimetable-class_id").val(html.classid);
                 $("#classtimetable-group_id").val(html.groupId);
            } 
        });
        
    });

$(document).on('click','#save_timetable',function(){

        var classid=$('#classtimetable-class_id').val();
        var groupid=$('#classtimetable-group_id').val();
        var subjectid=$('#classtimetable-subjectid').val();
        var dayname=$('.classtimetable-day').map( function(){
                 return $(this).val(); 
                }).get();
        var starttime=$('.starttime').map( function(){
                 return $(this).val(); 
                }).get();
        var endtime=$('.endtime').map( function(){
                 return $(this).val(); 
                }).get();
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",

            data: {classid:classid,groupid:groupid,subjectid:subjectid,dayname:dayname,starttime:starttime,endtime:endtime} ,
            url: url,
            cache: false,
            success: function(html)
            {
                 //console.log(html.renderView);
                 $(".renderView").html(html.renderView);
                 $("#classtimetable-subjectid").val(html.subjectid);
                 $("#classtimetable-class_id").val(html.classid);
                 $("#classtimetable-group_id").val(html.groupId);
            } 
        });
        
    });

$(document).on('click','#searchtimetable',function(){
        var classid=$('#getdataclass').val();
        var groupid=$('#classdatagroup').val();
        var subjectid=$('#getSubjectsdata').val();
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {classid:classid,groupid:groupid,subjectid:subjectid},
            url: url,
            cache: false,
            success: function(html)
            {
                 //console.log(html.renderView);
                 $(".renderSearchView").html(html.renderSearchView);
            } 
        });
        
    });

$(document).on('click','#searchtimetableClasswise',function(){
        var classid=$('#getdataclass').val();
        var groupid=$('#classdatagroup').val();
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {classid:classid,groupid:groupid},
            url: url,
            cache: false,
            success: function(html)
            {
                 //console.log(html.renderView);
                 $(".renderSearchView").html(html.renderSearchView);
            } 
        });
        
    });


$(document).on('click','#ClassTimetablePdf',function(){
  var url=$(this).data('url');
  var classid=$(this).data('classid');
  var groupid=$(this).data('groupid');
  var subjectid=$(this).data('subjectid');
  
  var attrname = $(this).attr('name'); 
  if(attrname =='Generate Report'){
    window.location.replace(url+"?classid="+classid+"&groupid="+groupid+"&subjectid="+subjectid);
  }
   });

$(document).on('click','#ClassTimetableClasswisePdf',function(){
  var url=$(this).data('url');
  var classid=$(this).data('classid');
  var groupid=$(this).data('groupid');
  
  var attrname = $(this).attr('name'); 
  if(attrname =='Generate Report'){
    window.location.replace(url+"?classid="+classid+"&groupid="+groupid);
  }
   });


  $(document).on('click','#checktimetable',function(){
    var val=$(this).val();
    if(val== '0'){ 
        $('.renderSearchView').empty();
        $('#showSubject').hide('slow');
        $('#showSubjectSearch').show();
    }else{
        $('.renderSearchView').empty();
        
        $('#showSubject').slideDown('slow');
        $('#showSubjectSearch').slideUp('slow');
    } 
});




/*===================================================================
end of get class and group subjects, use for subject time table*/
/*==================================================================*/

/*===================================================================
start of messgae module
/*==================================================================*/

$(document).on('click','#composeMessage',function(){
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
           // data: {classid:classid,groupid:groupid,subjectid:subjectid},
            url: url,
            cache: false,
            success: function(html)
            {
                 $(".displayMessage").html(html.displayMessage);
            } 
        });
        
    });

$(document).on('click','#getUserMesg',function(){
  
        var id=$(this).data('id');
        var sender=$(this).data('sender');
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {id:id,sender:sender},
            url: url,
            cache: false,
            success: function(html)
            {
                 $(".displayMessage").html(html.displayMessage);
            } 
        });
        
    });

$(document).on('click','#replyusers',function(){
        var id=$(this).data('id');
        var senderIdpass=$('#senderIdpass').val();
        var getSubject=$('#getSubject').val();
        var val=$("#replyMessage").val();
        $('.chatreply').show();
         $('#replyMesg').html(val);
         $('#replyMessage').val('');
        var url=$(this).data('url');
        //alert(url);
      // return false;
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {id:id,val:val,senderIdpass:senderIdpass,getSubject:getSubject},
            url: url,
            cache: false,
            success: function(html)
            {
             

            } 
        });
        
    });


/*===================================================================
end of messgae module
/*==================================================================*/
/*============ get subject against class,group*/
$(document).on('change','.sectionSubjects',function(){
        $("#quizGrid").empty();
        var section_id=$(this).val();
        var classid=$('#class-id').val();
        var id=$('#group-id').val();
        var url=$('#getSubjects').val();
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {id:id,classid:classid,section_id:section_id} ,
            url: url,
            cache: false,
            success: function(html)
            {
               $("#quizSubjects").html(html.getSubjectsdata);

            } 
        }); 
    });
$(document).on('change','#quizSubjects',function(){  //get test aganist subject
        $("#quizGrid").empty();
        var subject_id=$(this).val();
        var section_id=$('#section-id').val();
        var class_id=$('#class-id').val();
        var group_id=$('#group-id').val();
        var url=$(this).data('url');
        if(group_id ==''){ 
        groupId=null;
        }
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",
            data: {subject_id:subject_id,group_id:group_id,class_id:class_id,section_id:section_id} ,
            url: url,
            cache: false,
            success: function(html)
            {
               $("#getSubjectsQuiz").html(html.subjectTest);

            } 
        }); 
    });
$(document).on('change','#getSubjectsQuiz',function(){
        $("#quizGrid").empty();
        var class_id=$('#class-id').val();
        var group_id=$('#group-id').val();
        var section_id=$('#section-id').val();
        var quiz_id=$(this).val();
        var subject_id=$('#quizSubjects').val();
        var url=$(this).data('url');
        $.ajax
        ({
            type: "POST",
            dataType:"JSON",

            data: {quiz_id:quiz_id,subject_id:subject_id,class_id:class_id,group_id:group_id,section_id:section_id} ,
            url: url,
            cache: false,
            success: function(html)
            {
                 //console.log(html.renderView);
                 $("#quizGrid").html(html.quizGrid);
            } 
        });
        
    });

/*============ end of get subject against class,group*/

