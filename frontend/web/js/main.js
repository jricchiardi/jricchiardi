$(document).ready(function(){
	$('.mySelectBoxClass').customSelect();
});

//$(document).ready(function() {
//	$("#datepicker").datepicker();
//});

$(".menu").click(function(){ 
    $(".navbar").css("right", "0px");

    var pageHeight = $( document ).height();
    var pageWidth = $(window).width();

    $('#black').css({"height": pageHeight}).fadeIn( "fast", function() {

    $(this).show();
    });
});

$(document).on('click touchstart', '.black', function() {
	$(".navbar").css("right", "-240px");
	$(this).hide();
});

$("html").bind("click touchstart", function(){
	$(".notifications-panel").hide();
});
$(".notifications").click(function(){ 
	$(".notifications-panel").toggle();
});
$('.navbar').click(function(event){
    event.stopPropagation();
});
$('.menu').click(function(event){
    event.stopPropagation();
});
$('.notifications').click(function(event){
    event.stopPropagation();
});
$(".reportsSearch").click(function(){ 
  $(".reportsFilters").toggle();

  if($(".reportsFilters").css('display') == 'none')
  {
    $(".reportsSearch.showFilters").show();
    $(".reportsSearch.hideFilters").hide();

    $('#containerReport').removeClass('col-md-7');
    $('#containerReport').removeClass('col-lg-8');
    $('#containerReport').addClass('col-md-12');
    $('#containerReport').addClass('col-lg-12');
  }
  else
  {
    $(".reportsSearch.showFilters").hide();
    $(".reportsSearch.hideFilters").show();

    $('#containerReport').removeClass('col-md-12');
    $('#containerReport').removeClass('col-lg-12');
    $('#containerReport').addClass('col-md-7');
    $('#containerReport').addClass('col-lg-8');
  }
  $(window).resize();
});


//$('.close').click(function () {
//    $(this).parent().fadeOut();
//});

// With JQuery
//$("#range").slider({});

//$('.range').draggable({});

$('.table-con-link tbody tr').each( function(i, e) 
{
    $(e).attr('data-toggle', 'modal');
    $(e).attr('data-target', '#modal');
    $(e).attr('data-url', $(this).find('a').attr('href'));
});
$('.table-con-link tbody tr').click( function() {
    // window.location = $(this).find('a').attr('href');
    
    // console.log($(this).find('a').attr('href'));
    // $('#modal').data('bs.modal', null);
    // $('#modal').removeData('bs.modal');
    // $('#modal').modal(
    // {
    //   remote: $(this).find('a').attr('href'),
    //   toggle: 'modal',
    //   show: true
    // });
}).hover( function() {
    $(this).toggleClass('hover');
});

$('#tipo-de-reclamo').on('shown.bs.collapse', function () {
   $(".glyphicon-1").removeClass("collapse-open").addClass("collapse-close");
});
$('#tipo-de-reclamo').on('hidden.bs.collapse', function () {
   $(".glyphicon-1").removeClass("collapse-close").addClass("collapse-open");
});

$('#informacion-basica').on('shown.bs.collapse', function () {
   $(".glyphicon-2").removeClass("collapse-open").addClass("collapse-close");
});
$('#informacion-basica').on('hidden.bs.collapse', function () {
   $(".glyphicon-2").removeClass("collapse-close").addClass("collapse-open");
});

$('#informacion-semilla').on('shown.bs.collapse', function () {
   $(".glyphicon-3").removeClass("collapse-open").addClass("collapse-close");
});
$('#informacion-semilla').on('hidden.bs.collapse', function () {
   $(".glyphicon-3").removeClass("collapse-close").addClass("collapse-open");
});
$('#calidad-de-semillas').on('shown.bs.collapse', function () {
   $(".glyphicon-4").removeClass("collapse-open").addClass("collapse-close");
});

$('#calidad-de-semillas').on('hidden.bs.collapse', function () {
   $(".glyphicon-4").removeClass("collapse-close").addClass("collapse-open");
});

$('#relevamiento-del-reclamo').on('shown.bs.collapse', function () {
   $(".glyphicon-5").removeClass("collapse-open").addClass("collapse-close");
});
$('#relevamiento-del-reclamo').on('hidden.bs.collapse', function () {
   $(".glyphicon-5").removeClass("collapse-close").addClass("collapse-open");
});

$(function() {
    $('.table-reclamos tbody tr td:first-child input').change(function() {
        $(this).closest('tr').toggleClass("highlight", this.checked);
    });
});

$('.anio2013').addClass("disable");
$('.anio2012').addClass("disable");
$('.anio2011').addClass("disable");


$('#collapse2014').on('shown.bs.collapse', function () {
   $('.anio2014').removeClass("disable");
});
$('#collapse2014').on('hidden.bs.collapse', function () {
   $('.anio2014').addClass("disable");
});

$('#collapse2013').on('shown.bs.collapse', function () {
   $('.anio2013').removeClass("disable");
});
$('#collapse2013').on('hidden.bs.collapse', function () {
   $('.anio2013').addClass("disable");
});

$('#collapse2012').on('shown.bs.collapse', function () {
   $('.anio2012').removeClass("disable");
});
$('#collapse2012').on('hidden.bs.collapse', function () {
   $('.anio2012').addClass("disable");
});

$('#collapse2011').on('shown.bs.collapse', function () {
   $('.anio2011').removeClass("disable");
});
$('#collapse2011').on('hidden.bs.collapse', function () {
   $('.anio2011').addClass("disable");
});


$('#cultivo').change(function() {
    if ($(this).val() === "") {
      $('.sl-cultivo label').addClass('needsfilled-label');
      $('.sl-cultivo .customSelect').addClass('needsfilled-select');
    } else {
      $('.sl-cultivo label').removeClass('needsfilled-label');
      $('.sl-cultivo .customSelect').removeClass('needsfilled-select');
    }
});

$('#problema').change(function() {
    if ($(this).val() === "") {
      $('.sl-problema label').addClass('needsfilled-label');
      $('.sl-problema .customSelect').addClass('needsfilled-select');
    } else {
      $('.sl-problema label').removeClass('needsfilled-label');
      $('.sl-problema .customSelect').removeClass('needsfilled-select');
    }
});

$('#detalle').change(function() {
    if ($(this).val() === "") {
      $('.sl-detalle label').addClass('needsfilled-label');
      $('.sl-detalle .customSelect').addClass('needsfilled-select');
    } else {
      $('.sl-detalle label').removeClass('needsfilled-label');
      $('.sl-detalle .customSelect').removeClass('needsfilled-select');
    }
});

$(document).ready(function(){  
    $("#form-nuevo-reclamo").submit(function () {  
        if($("#cultivo").val() === "") {  
            $('.sl-cultivo label').addClass('needsfilled-label');
            $('.sl-cultivo .customSelect').addClass('needsfilled-select');
            $('.mensaje-error-validacion').show();
        } 
        if($("#problema").val() === "") {  
            $('.sl-problema label').addClass('needsfilled-label');
            $('.sl-problema .customSelect').addClass('needsfilled-select');
            $('.mensaje-error-validacion').show();  
        } 
        if($("#detalle").val() === "") {  
            $('.sl-detalle label').addClass('needsfilled-label');
            $('.sl-detalle .customSelect').addClass('needsfilled-select');
            $('.mensaje-error-validacion').show();
        }
        return false;  
    });  
});  

/**
 * MODAL FUNCTIONS
 * ===============
 */

var divLoading = 
'<div class="row">\
    <div class="col-md-4 col-md-offset-4 text-center">\
        <h1 style="font-size:60px"><span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></h1>\
    </div>\
</div>';

$('body').on('show.bs.modal','#modal', function (event) {
    var link = $(event.relatedTarget); // link that triggered the modal
    var contentUrl = link.data('url'); 
    var title = link.data('title');// Extract info from data-* attributes
    var modal = $(this);

    modal.find('.modal-body').html(divLoading);

    if(typeof title !== 'undefined')
        modal.find('.modal-title').text(title);
    else
        modal.find('.modal-title').hide();
    
    modal.find('.modal-body').load(contentUrl);
    
    // This could be change at future
    modal.find('.modal-footer').hide();
});


// Reset modal
$('#modalItem').on('show.bs.modal', function(e){
    var alertSuccess = $('#alertSuccess');
    var alertFail = $('#alertFail');
    var form = $('#itemForm');
    
    alertSuccess.hide();
    alertFail.hide();
    form.show();
    form[0].reset();
});

$(document).on('click','[data-reload="#modal"]', function (event) {
    var link = $(this); // link that triggered the modal
    var contentUrl = link.data('url'); 
    var title = link.data('title');// Extract info from data-* attributes
    var modal = $(link.data('reload'));
    
    modal.find('.modal-body').html(divLoading);

    if(typeof title !== 'undefined')
        modal.find('.modal-title').text(title);
    else
        modal.find('.modal-title').hide();

    modal.find('.modal-body').load(contentUrl);

    // This could be change at future
    modal.find('.modal-footer').hide();
});

$(document).on('hidden.bs.modal', '#modal', function (e) {
    //data: return data from server
    if($('#itemList').length) 
        $.pjax.reload({container:'#itemList'});  //Reload
});

$(document).on('pjax:send', function(xhr) {
  $(xhr.target).find('button,input,a').attr('disabled','disabled')
});
    
$(document).on('pjax:complete', function(xhr) {
  $(xhr.target).find('button,input,a').removeAttr('disabled','disabled')
});

$(document).on('submit','#itemForm', function(event) {
    var postData = $(this).serializeArray();
    var alertFail = $('#alertFail');
    var form = $('#itemForm');
    var formURL = form.attr('action');
    var modal = $('#modal');

    postData[postData.length] = {'name': 'submit', 'value':'1'};
    //alertSuccess.hide();
    //alertFail.hide();

    form.find('button[type="submit"]').attr('disabled','disabled');

    $.ajax(
    {
        url : formURL,
        type: 'POST',
        data : postData,
        success:function(data, textStatus, jqXHR) 
        {
            modal.find('.modal-body').html(data);
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            if(jqXHR.status !== 302)// redirect
                form.replaceWith(alertFail.html());
        }
    });

    event.preventDefault();
    event.stopPropagation();
});