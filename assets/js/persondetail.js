$(document).ready(function() {	

	$("#marital_id").select2();

	$("#position_id").select2();

	$("#empl_status_id").select2();

	$("#employee_status_id").select2();

	$("#position_group_id").select2();

	$("#grade_id").select2();

	$("#resign_reason_id").select2();

	$("#active_inactive_id").select2();
	
	/***** Tabs *****/
	//Normal Tabs - Positions are controlled by CSS classes
    $('#tab-1 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	$('#tab-1 li:eq(0) a').tab('show'); 
  
    $('#tab-2 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	
	$('#tab-2 li:eq(1) a').tab('show'); 
	  
	$('#tab-3 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});
	
	$('#tab-3 li:eq(2) a').tab('show'); 
	  
	$('#tab-4 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	$('#tab-4 li:eq(3) a').tab('show'); 
	  
	$('#tab-5 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	$('#tab-5 li:eq(4) a').tab('show'); 

	$('#tab-6 a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
	});

	$('#tab-6 li:eq(5) a').tab('show'); 

	//datepicker
	$('.input-append.date').datepicker({
  			format: "dd/mm/yyyy",
			autoclose: true,
			todayHighlight: true
	   });

	$("tr.itemjobass").each(function() {
        var iditemjobass = $(this).attr('id');
        $('#viewdetail-' + iditemjobass).click(function (e){
	     	e.preventDefault();
	      	$('#detail-' + iditemjobass).toggle();
	    });
	});

	$("tr.itemtraining").each(function() {
        var iditemtraining = $(this).attr('id');
        $('#viewtraining-' + iditemtraining).click(function (e){
	     	e.preventDefault();
	      	$('#trainingdetail-' + iditemtraining).toggle();
	    });
	});

	$("tr.itemcertificate").each(function() {
        var iditemcertificate = $(this).attr('id');
        $('#viewcertificate-' + iditemcertificate).click(function (e){
	     	e.preventDefault();
	      	$('#certificatedetail-' + iditemcertificate).toggle();
	    });
	});

	$("tr.itemeducation").each(function() {
        var iditemeducation = $(this).attr('id');
        $('#vieweducation-' + iditemeducation).click(function (e){
	     	e.preventDefault();
	      	$('#educationdetail-' + iditemeducation).toggle();
	    });
	});

	$("tr.itemexperience").each(function() {
        var iditemexperience = $(this).attr('id');
        $('#viewexperience-' + iditemexperience).click(function (e){
	     	e.preventDefault();
	      	$('#experiencedetail-' + iditemexperience).toggle();
	    });
	});

	$("tr.itemsk").each(function() {
        var iditemsk = $(this).attr('id');
        $('#viewsk-' + iditemsk).click(function (e){
	     	e.preventDefault();
	      	$('#skdetail-' + iditemsk).toggle();
	    });
	});

	$("tr.itemsertijah").each(function() {
        var iditemsertijah = $(this).attr('id');
        $('#viewsertijah-' + iditemsertijah).click(function (e){
	     	e.preventDefault();
	      	$('#sertijahdetail-' + iditemsertijah).toggle();
	    });
	});

	$("tr.itemjabatan").each(function() {
        var iditemjabatan = $(this).attr('id');
        $('#viewjabatan-' + iditemjabatan).click(function (e){
	     	e.preventDefault();
	      	$('#jabatandetail-' + iditemjabatan).toggle();
	    });
	});

	$("tr.itemaward").each(function() {
        var iditemaward = $(this).attr('id');
        $('#viewaward-' + iditemaward).click(function (e){
	     	e.preventDefault();
	      	$('#awarddetail-' + iditemaward).toggle();
	    });
	});

	$("tr.itemikatandinas").each(function() {
        var iditemikatandinas = $(this).attr('id');
        $('#viewikatandinas-' + iditemikatandinas).click(function (e){
	     	e.preventDefault();
	      	$('#ikatandinasdetail-' + iditemikatandinas).toggle();
	    });
	});

	$("a[rel^='prettyPhoto']").prettyPhoto({social_tools:false});

	var id = '#dialog';
		
	//Get the screen height and width
	var maskHeight = $(document).height();
	var maskWidth = $(window).width();
		
	//Set heigth and width to mask to fill up the whole screen
	$('#mask').css({'width':maskWidth,'height':maskHeight});

	//transition effect
	$('#mask').fadeIn(500);	
	$('#mask').fadeTo("slow",0.9);	
		
	//Get the window height and width
	var winH = $(window).height();
	var winW = $(window).width();
	              
	//Set the popup window to center
	$(id).css('top',  winH/2-$(id).height()/2);
	$(id).css('left', winW/2-$(id).width()/2);
		
	//transition effect
	$(id).fadeIn(2000); 	
		
	//if close button is clicked
	$('.window .close').click(function (e) {
	//Cancel the link behavior
	e.preventDefault();

	$('#mask').hide();
	$('.window').hide();
	});

	var url = $.url();
	var empId = url.segment(4);
	//if mask is clicked
	/*$('#mask').click(function () {
	alert(empId);
	$(this).hide();
	$('.window').hide();
	});*/

	$('#boxes').click(function () {
		$.ajax({
          type: 'POST',
          url: '../update_bd_reminder',
          data: {id : empId},
          success: function(data) { 
			$(this).hide();
			$('#mask').hide();
			$('.window').hide();
          }
      });
	});
});