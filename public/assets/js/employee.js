(function($) {
	'use strict';
	
	$(document).ready(function () {
		$('#branch_id').on('change', function() {
			var branchID = $(this).val();
			getDesignationByBranch(branchID);
			getDepartmentByBranch(branchID);

			$.ajax({
				url: base_url + "custom_field/getFieldsByBranch",
				type: 'POST',
				data: {
					branch_id: branchID,
					belongs_to: 'employee'
				},
				success: function (data) {
					$('#customFields').html(data);
					$('#customFields [data-plugin-selecttwo]').each(function() {
						var $this = $(this);
						$this.themePluginSelect2({});
					});
					$('#customFields [data-plugin-datepicker]').each(function() {
						var $this = $(this);
						$this.themePluginDatePicker({});
					});
				}
			});
		});
		
		$('#branchID_mod').on('change', function() {
			var branchID = $(this).val();
			$.ajax({
				url: base_url + 'ajax/getDataByBranch',
				type: 'POST',
				data: {
					table: "staff_designation",
					branch_id: branchID
				},
				success: function (response) {
					$('#designationID_mod').html(response);
				}
			});
			$.ajax({
				url: base_url + 'ajax/getDataByBranch',
				type: 'POST',
				data: {
					table: "staff_department",
					branch_id: branchID
				},
				success: function (response) {
					$('#departmentID_mod').html(response);
				}
			});
		});

	    $("form#importCSV").each(function(i, el)
	    {
	        var $this = $(el);
	        $this.on('submit', function(e){
	            e.preventDefault();
	            var btn = $this.find('[type="submit"]');
	            $.ajax({
	                url: $(this).attr('action'),
	                type: "POST",
	                data: new FormData(this),
	                dataType: "json",
	                contentType: false,
	                processData: false,
	                cache: false,
	                beforeSend: function () {
	                    btn.button('loading');
	                },
	                success: function (data) {
	                    $('.error').html("");
	                    if (data.status == "fail") {
	                        $.each(data.error, function (index, value) {
	                            $this.find("[name='" + index + "']").parents('.form-group').find('.error').html(value);
	                        });
	                        btn.button('reset');
	                    } else if(data.status == "errlist") {
							$('#errorList').html(data.errMsg).show("slow").delay(2500).hide("slow");
							btn.button('reset');
	                    } else {
	                        if (data.url) {
	                            window.location.href = data.url;
	                        } else if (data.status == "access_denied") {
	                            window.location.href = base_url + "dashboard";
	                        } else {
	                            location.reload(true);
	                        }
	                    }
	                },
	                error: function () {
	                    btn.button('reset');
	                }
	            });
	        });
	    });
	});
})(jQuery);
