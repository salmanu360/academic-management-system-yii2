/**
 * Developed by salman khan <salman.u360@gmail.com>on 02/24/2016.
 * exam.js
 * for exam related js.
 */


$(document).on('click','a[data-toggle="tab"]', function (e) {
    var target = $(e.target).attr("href") // activated tab
    var targetText = $(e.target).text() // activated tab
    var details = $('#modal-type');
    details.find('.modal-header h4').text(targetText);
    if(target =='#Single-Examination'){
        $('#single-dropdown').show();
        $('#multiple-dropdown').hide();
    }
    else if(target =='#Multiple-Examination'){
        $('#multiple-dropdown').show();
        $('#single-dropdown').hide();
    }else if(target =='#Class-Wise-Examination'){
        $('#single-dropdown').show();
        $('#multiple-dropdown').hide();
    }else{
        $('#single-dropdown').hide();
        $('#multiple-dropdown').hide();
    }
    $('#tab_type').val(target);
    /*if(target != '#Class-Wise-Examination'){
        $('#modal-type').modal('show');
    }*/
    $('#modal-type').modal('show');
    return false;
});
/*search dmc std list.*/
$(document).on('click','#search-exam-dmc',function () {
   var url  =$(this).data('url');
    var formHtml = $(this).closest('.modal-content').find('.modal-body form');
    var formData = formHtml.serialize();
    var singleDropdown      = $('select#exam-fk_exam_type-1');
    var multipleDropdown    = $('#multiple-dropdown select');
    var tabId               = $('#tab_type').val();
    var classId     = $('#class-id').val();
    var groupId     = $('#group-id').val();
    var sectionId   = $('#section-id').val();

    var error = 0;
    if(tabId == '#Single-Examination'){
        if(singleDropdown.val()==''){
            singleDropdown.closest('.form-group').addClass('has-error');
            singleDropdown.closest('.form-group').removeClass('has-success');
            singleDropdown.closest('.form-group').find('.help-block').html('Exam is Required');
            error++;
        }else{
            singleDropdown.closest('.form-group').addClass('has-success');
            singleDropdown.closest('.form-group').removeClass('has-error');
            singleDropdown.closest('.form-group').find('.help-block').html('');
        }
    }else if (tabId == '#Multiple-Examination'){
        if(multipleDropdown.val() == '' || multipleDropdown.val() == null){
            multipleDropdown.closest('.form-group').addClass('has-error');
            multipleDropdown.closest('.form-group').removeClass('has-success');
            multipleDropdown.closest('.form-group').find('.help-block').html('Exam is Required');
            error++;
        }else{
            multipleDropdown.closest('.form-group').addClass('has-success');
            multipleDropdown.closest('.form-group').removeClass('has-error');
            multipleDropdown.closest('.form-group').find('.help-block').html('');
        }
    }else{

    }
    if(error == 0){
        $.ajax
        ({
            type: "POST",
            dataType:'JSON',
            data: {data:formData},
            url: url,
            success: function(result)
            {
                if(result.status ==1){
                    var examType = singleDropdown.val();
                    if(result.tabId =='Single-Examination'){
                        $("#"+result.tabId).html(result.html);
                        var exportUrl = $('.exportdmcs').data('url');

                        var dataUrl = exportUrl+"?class_id="+classId+"&group_id="+groupId+"&section_id="+sectionId+"&exam_id="+examType;
                        $('.exportdmcs').html('<a class="btn green-btn" href="'+dataUrl+'" ><span class="glyphicon glyphicon-print btn btn-success btn-sm btn-block" title="Print All DMC"></span></a>');
                        $('.exportdmcs').show();
                        $('.export-classwise-resultsheet').hide();
                        $('ul.std-exam-list li a').first()[0].click();
                    }
                    if(result.tabId =='Class-Wise-Examination'){
                        var arrayParam = {"param1":1,"param2":2};
                        $("#"+result.tabId).empty().html(result.html);
                        var exportUrl = $('.export-classwise-resultsheet').data('url');
                        var dataUrl = exportUrl+"?fk_class_id="+classId+"&fk_group_id="+groupId+"&fk_section_id="+sectionId+"&fk_exam_type="+examType;
                        $('.export-classwise-resultsheet').html('<a href="'+dataUrl+'"><span class="btn btn-info btn-sm glyphicon glyphicon-print"></span></a>');
                        $(".export-classwise-resultsheet a").attr( "params",arrayParam );

                        $('.exportdmcs').hide();
                    }
                    $('#modal-type').modal('hide');
                }else{
                    $("#"+result.tabId).html(result.html);
                    $('#modal-type').modal('hide');
                }
            }
        });
    }
    //console.log(formHtml.serialize());

});

$(document).on('click','#modal-type .close',function () {
   //alert('here');
});

/*on change of exam type get exam relted details.*/
$(document).on('change','#exam-type-id',function(){
    var examId      = $(this).val();
    var classId     = $('#class-id').val();
    var groupId     = $('#group-id').val();
    var sectionId   = $('#section-id').val();
    //console.log(classId+' '+groupId+' '+sectionId);
    var url=$(this).closest('.exam-dropdown-list').find('#exam-url').val();
    $("#exams-inner").empty().append("<div id='loading-loader'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
    $.ajax
    ({
        type: "POST",
        dataType:'JSON',
        data: {class_id:classId,group_id:groupId,section_id:sectionId,exam_id:examId},
        url: url,
        success: function(result)
        {
            $("#exams-inner").html(result.views);
        }
    });

});

/*get exam against year*/
$(document).on('change','#examYearUpcomming',function(){
    var radio=$('input[name="upcoming"]:checked').val();
    var year      = $(this).val(); 
    var class_id  = $('#getdataclass').val(); 
    var group_id      = $('#classdatagroup').val(); 
    var section_id      = $('#classdatasection').val(); 
    if(group_id ==''){ 
        group_id=null;
    }
    var url=$(this).data('url');
    $.ajax
    ({
        type: "POST",
        dataType:'json',
        cache: false,
        data: {class_id:class_id,group_id:group_id,section_id:section_id,year:year,radio:radio},
        url: url,
        success: function(result)
        {
            $("#getUpcommingExams").html(result.getYearexam);
        }
    });

});

/*get year exam*/
$(document).on('change','#examYear',function(){
    var year      = $(this).val(); 
    var class_id  = $('#class-id').val(); 
    var group_id  = $('#group-id').val(); 
    var section_id  = $('#section-id').val(); 
    //alert(section_id);
    if(group_id ==''){ 
        group_id=null;
    }
    var url=$(this).data('url');
    $.ajax
    ({
        type: "POST",
        data: {year:year,class_id:class_id,group_id:group_id,section_id:section_id},
        url: url,
        success: function(result)
        {
            $(".examttypeStudentMarks").html(result);
        }
    });

});
/*get award list exam type against year*/
$(document).on('change','#examYearAwardlist',function(){
   var year      = $(this).val(); 
   var sectionId = $('#section-idd').val();
   var classId = $('#class-id').val();
   var groupId = $('#group-id').val();
   var url = $(this).data('url');
    $("#subject-inner").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
    if(groupId ==''){ 
        groupId=null;
    }
    $.ajax
    ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {class_id:classId,group_id:groupId,section_id:sectionId,year:year},
        success: function(data)
        {
            if(data.status== 1){
                $("#subject-inner").empty().html(data.details);
                //$('#subject-details').html();
            $('#submit-std-account').show(); 

            }
        }
    });

});

/*get dmc of select student*/
$(document).on('click','#exam_std_list',function () {
   var url              = $(this).data('url');
   var stdid            = $(this).data('stdid');
   var classId          = $(this).data('class');
   var groupId          = $(this).data('group');
   var stdPosition      = $(this).data('position');
   var sectionId        = $(this).data('section');
   var exam_id           = $(this).data('examid');
    $('.fs_sidebar li').removeClass('active');
    $(this).closest('li').addClass('active');
    $('.performance-graphs').hide();
    $(".ajax-content").empty().append("<div id='loading-loader'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
    $.ajax
    ({
        type: "POST",
        dataType:'JSON',
        data: {stu_id:stdid,class_id:classId,group_id:groupId,section_id:sectionId,exam_id:exam_id,'stdPosition':stdPosition},
        url: url,
        success: function(result)
        {
            $(".ajax-content").empty().html(result.html);
            var chart = $('#dmc-graph-container').highcharts();
            if(Object.keys(chart.series).length > 0) {
                var seriesLength = chart.series.length;
                for (var i = seriesLength - 1; i > -1; i--) {
                    chart.series[i].remove();
                }
            }
           /* console.log(result.total_subjects);*/
            chart.xAxis[0].setCategories(result.total_subjects);
            for(i=0;i<=result.total_count;i++) {
                chart.addSeries({
                    name: result.total_subjects[i],
                    data: result.total_marks_subjects[i]
                });
                chart.redraw();
                // URL to Highcharts export server

            }
            $('.performance-graphs').show();
            /*$.each(html.attenance_data.total,
                function(key,value) {
                    data.push(parseInt(value));
                });*/
        }
    });
});
/*============get student marks against examid*/
$(document).on('change','.examttypeStudentMarks',function(){
$('getStudentMarksAgainstExamId').empty();
    var id      = $(this).val(); 
    var year      = $('#examYear').val(); 
    var stu_id      = $('#subject-inner').val(); 
    var class_id      = $('#class-id').val(); 
    var group_id      = $('#group-id').val(); 
    if(group_id ==''){ 
        group_id=null;
    }
    var section_id      = $('#section-id').val(); 
    var url=$(this).data('url');
    $.ajax
    ({
        type: "POST",
        dataType:'JSON',
        data: {id:id,year:year,stu_id:stu_id,class_id:class_id,group_id:group_id,section_id:section_id},
        url: url,
        success: function(result)
        {
            $("#getStudentMarksAgainstExamId").html(result.marksView);
        }
    });

});
/*get year all exam subject of individual students*/
$(document).on('change','#examAgainstYear',function(){
    $('getStudentMarksAgainstExamId').empty();
    var year      = $(this).val(); 
    var stu_id      = $('#subject-inner').val(); 
    var class_id      = $('#class-id').val(); 
    var group_id      = $('#group-id').val(); 
    if(group_id ==''){ 
        group_id=null;
    }
    var section_id      = $('#section-id').val(); 
    var url=$(this).data('url');
    $.ajax
    ({
        type: "POST",
        dataType:'JSON',
        data: {year:year,stu_id:stu_id,class_id:class_id,group_id:group_id,section_id:section_id},
        url: url,
        success: function(result)
        {
            $("#getStudentMarksAgainstExamId").html(result.marksView);
        }
    });

});
$(document).on('click','#YearWise',function(){
$('#examWiseType').hide();
$('#yearTwo').show();
$('#yearOne').hide();

});
$(document).on('click','#examWise',function(){
$('#examWiseType').show();
$('#yearTwo').hide();
$('#yearOne').show();

});

/*get class subjects for exam time table*/
$(document).on('change','.classSubjects',function () {
   var sectionId = $(this).val();
   var classId = $('#class-id').val();
   var groupId = $('#group-id').val();
   var url = $('#subject-url').val();
   
   //var url = $(this).closest('.col-sm-4').find('#subject-url').val();
    $("#subject-inner").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");
    if(groupId =='Loading ...'){ 
        groupId=null;
    }
    $.ajax
    ({
        type: "POST",
        dataType:"JSON",
        url: url,
        data: {class_id:classId,group_id:groupId,section_id:sectionId},
        success: function(data)
        {
            if(data.status== 1){
                $("#subject-inner").empty().html(data.details);
                //$('#subject-details').html();
            $('#submit-std-account').show(); 

            }
        }
    });
});
/*end of get class subjects for exam time table*/
/*============= parent section scripts*/
$(document).on('change','#examTypeParent',function () {
    $("#subject-inner").empty();
   var url  =$(this).data('url');
    var formHtml = $(this).closest('.modal-content').find('.modal-body form');
    var formData = formHtml.serialize();
    var singleDropdown      = $('select#exam-fk_exam_type-1');
    var multipleDropdown    = $('#multiple-dropdown select');
    var tabId               = $('#tab_type').val();
    
    var examid      = $(this).val();
    $("#subject-inner").empty().append("<div id='loading'><img  class='loading-img-set' src='../img/loading.gif' alt='Loading' /></div>");

    var error = 0;
    if(tabId == '#Single-Examination'){
        if(singleDropdown.val()==''){
            singleDropdown.closest('.form-group').addClass('has-error');
            singleDropdown.closest('.form-group').removeClass('has-success');
            singleDropdown.closest('.form-group').find('.help-block').html('Exam is Required');
            error++;
        }else{
            singleDropdown.closest('.form-group').addClass('has-success');
            singleDropdown.closest('.form-group').removeClass('has-error');
            singleDropdown.closest('.form-group').find('.help-block').html('');
        }
    }else if (tabId == '#Multiple-Examination'){
        if(multipleDropdown.val() == '' || multipleDropdown.val() == null){
            multipleDropdown.closest('.form-group').addClass('has-error');
            multipleDropdown.closest('.form-group').removeClass('has-success');
            multipleDropdown.closest('.form-group').find('.help-block').html('Exam is Required');
            error++;
        }else{
            multipleDropdown.closest('.form-group').addClass('has-success');
            multipleDropdown.closest('.form-group').removeClass('has-error');
            multipleDropdown.closest('.form-group').find('.help-block').html('');
        }
    }else{

    }
    if(error == 0){
        $.ajax
        ({
            type: "POST",
            dataType:'JSON',
            data: {examid:examid},
            url: url,
            success: function(result)
            {
                if(result.status ==1){
                    var examType = singleDropdown.val();
                    if(result.tabId =='Single-Examination'){
                        $("#"+result.tabId).html(result.html);
                        $("#subject-inner").html(result.html);
                        $("#exam_std_list").trigger("click")
                        var exportUrl = $('.exportdmcs').data('url');

                        var dataUrl = exportUrl+"?class_id="+classId+"&group_id="+groupId+"&section_id="+sectionId+"&exam_id="+examType;
                        $('.exportdmcs').html('<a class="btn green-btn" href="'+dataUrl+'" ><span class="glyphicon glyphicon-print btn btn-success btn-sm btn-block" title="Print All DMC"></span></a>');
                        $('.exportdmcs').show();
                        $('.export-classwise-resultsheet').hide();
                        $('ul.std-exam-list li a').first()[0].click();
                    }
                    if(result.tabId =='Class-Wise-Examination'){
                        var arrayParam = {"param1":1,"param2":2};
                        $("#"+result.tabId).empty().html(result.html);
                        var exportUrl = $('.export-classwise-resultsheet').data('url');
                        var dataUrl = exportUrl+"?fk_class_id="+classId+"&fk_group_id="+groupId+"&fk_section_id="+sectionId+"&fk_exam_type="+examType;
                        $('.export-classwise-resultsheet').html('<a href="'+dataUrl+'"><span class="btn btn-info btn-sm glyphicon glyphicon-print"></span></a>');
                        $(".export-classwise-resultsheet a").attr( "params",arrayParam );

                        $('.exportdmcs').hide();
                    }
                    $('#modal-type').modal('hide');
                }else{
                     $("#subject-inner").html('No result found..!');
                    $("#"+result.tabId).html(result.html);
                    $('#modal-type').modal('hide');
                }
            }
        });
    }
     });
/*============= end of parent section scripts*/
