$.extend(theme.PluginDatePicker.defaults, {
	format: "yyyy-mm-dd",
	autoclose: true
});

(function($) {
	'use strict';

	// DataTable Config
	$('.table_default').DataTable({
		"dom": '<"row"<"col-sm-6"l><"col-sm-6"f>><"table-responsive"t>p',
		"pageLength": 25,
		"autoWidth": false,
		"ordering": false
	});

	var table = $('.table-export').DataTable({
		"dom": '<"row"<"col-sm-6 mb-xs"B><"col-sm-6"f>><"table-responsive"t>p',
		"ordering": false,
		"autoWidth": false,
		"pageLength": 25,
		"buttons": [
			{
				extend: 'copyHtml5',
				text: '<i class="far fa-copy"></i>',
				titleAttr: 'Copy',
				title: $('.export_title').html(),
				exportOptions: {
					columns: ':visible'
				}
			},
			{
				extend: 'excelHtml5',
				text: '<i class="fa fa-file-excel"></i>',
				titleAttr: 'Excel',
				title: $('.export_title').html(),
				exportOptions: {
					columns: ':visible'
				}
			},
			{
				extend: 'csvHtml5',
				text: '<i class="fa fa-file-alt"></i>',
				titleAttr: 'CSV',
				title: $('.export_title').html(),
				exportOptions: {
					columns: ':visible'
				}
			},
			{
				extend: 'pdfHtml5',
				text: '<i class="fa fa-file-pdf"></i>',
				titleAttr: 'PDF',
				title: $('.export_title').html(),
				footer: true,
				customize: function ( win ) {
					win.styles.tableHeader.fontSize = 10;
					win.styles.tableFooter.fontSize = 10;
					win.styles.tableHeader.alignment = 'left';
				},
				exportOptions: {
					columns: ':visible'
				}
			},
			{
				extend: 'print',
				text: '<i class="fa fa-print"></i>',
				titleAttr: 'Print',
				title: $('.export_title').html(),
				customize: function ( win ) {
					$(win.document.body)
						.css( 'font-size', '9pt' );

					$(win.document.body).find( 'table' )
						.addClass( 'compact' )
						.css( 'font-size', 'inherit' );

					$(win.document.body).find( 'h1' )
						.css( 'font-size', '14pt' );
				},
				footer: true,
				exportOptions: {
					columns: ':visible'
				}
			},
			{
				extend: 'colvis',
				text: '<i class="fas fa-columns"></i>',
				titleAttr: 'Columns',
				title: $('.export_title').html(),
				postfixButtons: ['colvisRestore']
			},
		]
	});


		// permission page select all
		$("#all_view").on( "click", function() {
			if($(this).is(':checked')){           
				$(".cb_view").prop("checked", true);
			}else{
				$(".cb_view").prop("checked", false);
			}
		});

		$("#all_add").on( "click", function() {
			if($(this).is(':checked')){           
				$(".cb_add").prop("checked", true);
			}else{
				$(".cb_add").prop("checked", false);
			}
		});

		$("#all_edit").on( "click", function() {
			if($(this).is(':checked')){           
				$(".cb_edit").prop("checked", true);
			}else{
				$(".cb_edit").prop("checked", false);
			}
		});
		
		$("#all_delete").on( "click", function() {
			if($(this).is(':checked')){           
				$(".cb_delete").prop("checked", true);
			}else{
				$(".cb_delete").prop("checked", false);
			}
		});



	if($.isFunction($.fn.validate)) {
		$("form.validate").each(function(i, el)
		{
			var $this = $(el),
			opts = {
				highlight: function( label ) {
					$(label).closest('.form-group').removeClass('has-success').addClass('has-error');
				},
				success: function( label ) {
					$(label).closest('.form-group').removeClass('has-error');
					label.remove();
				},
				errorPlacement: function( error, element ) {
					var placement = element.closest('.input-group');
					if (!placement.get(0)) {
						placement = element;
					}
					if (error.text() !== '') {
						if(element.parent('.checkbox, .radio').length || element.parent('.input-group').length) {
							placement.after(error);
						} else {
							var placement = element.closest('div');
							placement.append(error);
							wrapper: "li"
						}
					}
				}
			};
			$this.validate(opts);
		});
	}

	// page full screen
	$(".s-expand").on('click', function(e) {
		if (typeof screenfull != 'undefined') {
			if (screenfull.enabled) {
				screenfull.toggle();
			}
		}
	});
	
	if (typeof screenfull != 'undefined') {
		if (screenfull.enabled) {
			$(document).on(screenfull.raw.fullscreenchange, function() {
				if (screenfull.isFullscreen) {
					$('.s-expand').find('i').toggleClass('fas fa-expand fas fa-expand-arrows-alt');
				} else {
					$('.s-expand').find('i').toggleClass('fas fa-expand-arrows-alt fas fa-expand');
				}
			});
		}
	}
	
	// checkbox, radio and selects
	$("#chk-radios-form, #selects-form").each(function() {
		$(this).validate({
			highlight: function(element) {
				$(element).closest('.form-group').removeClass('has-success').addClass('has-error');
			},
			success: function(element) {
				$(element).closest('.form-group').removeClass('has-error');
			},
			errorPlacement: function( error, element ) {
				var placement = element.closest('div');
				if (!placement.get(0)) {
					placement = element;
				}
				if (error.text() !== '') {
					placement.append(error);
				}
			}
		});
	});

	// date range picker
	if ($(".daterange").length) {
		$('.daterange').daterangepicker({
			opens: 'left',
		    locale: {format: 'YYYY/MM/DD'},
		    ranges: {
		       'Today': [moment(), moment()],
		       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
		       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		       'This Month': [moment().startOf('month'), moment().endOf('month')],
		       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		       'This Year': [moment().startOf('year'), moment().endOf('year')],
		       'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
		    }
		});
	}

	// modal dismiss
	$(document).on("click", ".modal-dismiss", function(e) {
		e.preventDefault();
		$.magnificPopup.close();
	});

	$(document).ready(function () {
		// document print function
		$("#print").on( "click", function() {
			var mode = 'iframe'; //popup
			var close = mode == "popup";
			var options = {
				mode: mode,
				popClose: close
			};
			$("#printResult").printArea(options);
		});

		// document export CSV
		$("#csv_btn").on("click", function() {
			$("#export_table").table2csv({filename: 'student_attendance_sheet.csv'});
		});

		// script to print show / hidden all
		$("input[name='chkPrint']").on("change", function() {	
			if ($(this).is(":checked"))
			{
				$(this).parents('tr').removeClass("hidden-print");
			} else {
				$(this).parents('tr').addClass("hidden-print");
			}
		});

		// script to id card print show / hidden all
		$("input[name='chk_idcard']").on( "change", function() {
			if ($(this).is(":checked"))
			{
				$(this).parents('.idcard-col').removeClass("hidden-print");
			} else {
				$(this).parents('.idcard-col').addClass("hidden-print");
			}
		});

		// student admission guardian slow / hidden
		$("#chkGuardian").on( "change", function() {
			if ($(this).is(":checked")){
				$("#guardian_form").hide("slow");
				$("#exist_list").show("slow");
			} else {
				$("#guardian_form").show("slow");	
				$("#exist_list").hide("slow");
			}
		});

		// script for all checkbox checked / unchecked
		$("#selectAllchkbox").on("change", function(ev)
		{
			var $chcks = $(".checked-area input[type='checkbox']");
			if($(this).is(':checked'))
			{
				$chcks.prop('checked', true).trigger('change');
			} else {
				$chcks.prop('checked', false).trigger('change');
			}
		});

		// event holiday show / hide
		$("#chk_holiday").on("change", function(ev)
		{
			if($(this).is(':checked'))
			{
				$("#typeDiv").hide("slow");
			} else {
				$("#typeDiv").show("slow");
			}
		});

		// attachments class and subject show / hide
		$("#all_class_set").on("change", function()
		{
			if($(this).is(':checked'))
			{
				$("#class_div").hide("slow");
				$("#sub_div").hide("slow");
			} else {
				$("#class_div").show("slow");
				$("#sub_div").show("slow");
			}
		});

		$("#subject_wise").on("change", function()
		{
			if($(this).is(':checked'))
			{
				$("#subject_div").hide("slow");
			} else {
				$("#subject_div").show("slow");
			}
		});

		// skipped employee bank details
		$("#chk_bank_skipped").on( "change", function() {
			if ($(this).is(":checked")){
				$("#bank_details_form").hide("slow");
			} else {
				$("#bank_details_form").show("slow");
			}
		});


		// message delete script
		$(document).on('click', '#msgAction', function() {
			var id = "";
			var type = $(this).data('type');
			var arrayID = [];
			$("input[type='checkbox'].msg_checkbox").each(function (index) {
				if(this.checked) {
					id = $(this).attr('id');
					arrayID.push(id);
				}
			});
			if (arrayID.length != 0) {
				$.ajax({
					url: base_url + "communication/trash_observe",
					type: 'POST',
					data: {
						array_id : arrayID,
						mode : type
					},
					success: function (data) {
						location.reload();
					}
				});
			}
		});

		// message conversation is important
		$(".mailbox-fav").on("click", function() {
			var messageID = $(this).attr('data-id');
			var $this = $(this).find('i');
			var status = $this.hasClass('far fa-bell');
			$this.toggleClass("far fa-bell");
			$this.toggleClass("fas fa-bell");
			$.ajax({
				url: base_url + "communication/set_fvourite_status",
				type: 'POST',
				data: {
					messageID: messageID,
					status: status
				},
				dataType: "json",
				success: function (data) {
					if(data.status == true) {
						alertMsg(data.msg);
					}
				}
			});  
		});

		// events status
		$(".event-switch").on("change", function() {
			var state = $(this).prop('checked');
			var id = $(this).data('id');
			if (state != null) {
				$.ajax({
					type: 'POST',
					url: base_url + "event/status",
					data: {
						id: id,
						status: state
					},
					dataType: "json",
					success: function (data) {
						if(data.status == true) {
							alertMsg(data.msg);
						}
					}
				});
			}
		});
	});

	// bootstrapToggle configurations
	if ($(".toggle-switch").length) {
		$('.toggle-switch').bootstrapToggle();
	}

	// dropify basic configurations
	if (typeof Dropify != 'undefined') {
		if ($(".dropify").length) {
			$(".dropify").dropify();
		}
	}

	// month and year mode datepicker
	if ($(".monthyear").length) {
        $(".monthyear").datepicker({
            orientation: 'bottom',
            autoclose: true,
            startView: 1,
            format: 'yyyy-mm',
            minViewMode: 1,
        });
    }

	// customize summernote
	if ($(".summernote").length) {
		$('.summernote').summernote({
			height: 220,
			toolbar: [
				["style", ["style"]],
				["name", ["fontname","fontsize"]],
				["font", ["bold","italic","underline", "clear"]],
				["color", ["color"]],
				["para", ["ul", "ol", "paragraph"]],
				["insert", ["link","table"]],
				["misc", ["fullscreen", "undo", "codeview"]]
			]
		});
	}

	// date range picker
	if ($(".daterange").length) {
		$('.daterange').daterangepicker({
			opens: 'left',
		    locale: {format: 'YYYY/MM/DD'},
		    ranges: {
		       'Today': [moment(), moment()],
		       'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		       'Last 7 Days': [moment().subtract(6, 'days'), moment()],
		       'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		       'This Month': [moment().startOf('month'), moment().endOf('month')],
		       'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		       'This Year': [moment().startOf('year'), moment().endOf('year')],
		       'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
		    }
		});
	}
})(jQuery);

// payroll salary add more allowances
function add_more_allowances() {
	var add_new = $('<div class="row"><div class="col-md-6 mt-md"> <input class="form-control" name="allowance_name[]" placeholder="Name Of Allowance" type="text">\n\
	</div><div class="col-md-4 mt-md"> <input class="salary form-control" name="allowance_value[]" placeholder="Amount" type="number"></div>\n\
	<div class="col-md-2 mt-md text-right"><button type="button" class="btn btn-danger removeAL" ><i class="fas fa-times"></i> </button></div></div>');
	$("#add_new_allowance").append(add_new);
}

// payroll salary allowances remove
$("#add_new_allowance").on('click', '.removeAL', function () {
	$(this).parent().parent().remove();
	payrollCheckSum();
});

// payroll salary add more deduction
function add_more_deduction() {
	var add_new = $('<div class="row"><div class="col-md-6 mt-md"> <input class="form-control" name="deduction_name[]" placeholder="Name Of Deductions" type="text">\n\
	</div><div class="col-md-4 mt-md"> <input class="deduction form-control" name="deduction_value[]" placeholder="Amount" type="number"></div>\n\
	<div class="col-md-2 mt-md text-right"><button type="button" class="btn btn-danger removeDE"><i class="fas fa-times"></i> </button></div></div>');
	$("#add_new_deduction").append(add_new);
}

// payroll salary deduction remove
$("#add_new_deduction").on('click', '.removeDE', function () {
	$(this).parent().parent().remove();
	payrollCheckSum();
});

function payrollCheckSum() {
    var sum = 0;
    var deduc = 0;
    $(".salary").each(function () {
        sum += $(this).val() ? parseFloat($(this).val()) : 0;
    });

    $(".deduction").each(function () {
        deduc += $(this).val() ? parseFloat($(this).val()) : 0;
    });

    $("#total_allowance").val(sum);
    $("#total_deduction").val(deduc);

    var net_salary = 0;
	var basic = $('#basic_salary').val() ? parseFloat($('#basic_salary').val()) : 0;
	
    net_salary = (basic + sum) - deduc;
    $("#net_salary").val(net_salary);
    $("#v_basic_salary").val(basic);
}


// event modal showing
function viewEvent(id) {
	$.ajax({
		url: base_url + "event/getDetails",
		type: 'POST',
		data: {
			event_id: id
		},
		success: function (data) {
			$('#ev_table').html(data);
			mfp_modal('#modal');
		}
	});
}


function ajaxModal(url) {
	// show ajax response on request success
	$.ajax({
		url: url,
		success: function (data) {
			$.magnificPopup.open({
				items: {
					src: data,
					type: 'inline',
				},
				fixedContentPos: false,
				fixedBgPos: true,
				overflowY: 'auto',
				closeBtnInside: true,
				preloader: false,
				midClick: true,
				removalDelay: 400,
				mainClass: 'my-mfp-zoom-in',
				modal: true
			});
		}
	});
}

// modal with css animation
function mfp_modal(data){
	$.magnificPopup.open({
		items: {
			src: data,
			type: 'inline',
		},
		fixedContentPos: false,
		fixedBgPos: true,
		overflowY: 'auto',
		closeBtnInside: true,
		preloader: false,
		midClick: true,
		removalDelay: 400,
		mainClass: 'my-mfp-zoom-in',
		modal: true
	});
}
