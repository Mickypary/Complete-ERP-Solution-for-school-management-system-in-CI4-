(function($) {
	'use strict';
	$(document).ready(function () {
		$(document).on('change', '#branch_id', function() {
			var branchID = $(this).val();
			$.ajax({
				url: base_url + "ajax/getClassByBranch",
				type: 'POST',
				data: { branch_id: branchID },
				success: function (data) {
					$('#class_id').html(data);
				}
			});

			$.ajax({
				url: base_url + "ajax/getDataByBranch",
				type: 'POST',
				data: {
					branch_id: branchID,
					table: 'student_category'
				},
				success: function (data) {
					$('#category_id').html(data);
				}
			});
			
			$.ajax({
				url: base_url + "ajax/getDataByBranch",
				type: 'POST',
				data: {
					branch_id: branchID,
					table: 'transport_route'
				},
				success: function (data) {
					$('#route_id').html(data);
				}
			});
			
			$.ajax({
				url: base_url + "ajax/getDataByBranch",
				type: 'POST',
				data: {
					branch_id: branchID,
					table: 'hostel'
				},
				success: function (data) {
					$('#hostel_id').html(data);
				}
			});
			
			$.ajax({
				url: base_url + "ajax/getDataByBranch",
				type: 'POST',
				data: {
					branch_id: branchID,
					table: 'parent'
				},
				success: function (data) {
					$('#parent_id').html(data);
				}
			});

			$("#section_id").empty();
			$('#section_id').append(new Option("Select Class First", ""));

			$.ajax({
				url: base_url + "custom_field/getFieldsByBranch",
				type: 'POST',
				data: {
					branch_id: branchID,
					belongs_to: 'student'
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
		
		$(document).on('change', '#route_id', function() {
			var routeID = $(this).val();
			$.ajax({
				url: base_url + "transport/get_vehicle_by_route",
				type: 'POST',
				data: {
					routeID: routeID
				},
				success: function (data) {
					$('#vehicle_id').html(data);
				}
			});
		});
		
		$(document).on('change', '#hostel_id', function() {
			var hostelID = $(this).val();
			$.ajax({
				url: base_url + "hostels/getRoomByHostel",
				type: 'POST',
				data: {
					hostel_id: hostelID
				},
				success: function (data) {
					$('#room_id').html(data);
				}
			});
		});
	});
})(jQuery);

function studentQuickView(id) {
    $.ajax({
        url: base_url + 'student/quickDetails',
        type: 'POST',
        data: {student_id: id},
        dataType: 'json',
        success: function (res) {
            $("#quick_image").attr("src", res.photo);
            $('#quick_full_name').html(res.full_name);
            $('#quick_category').html(res.student_category);
            $('#quick_register_no').html(res.register_no);
            $('#quick_roll').html(res.roll);
            $('#quick_admission_date').html(res.admission_date);
            $('#quick_date_of_birth').html(res.birthday);
            $('#quick_blood_group').html(res.blood_group);
            $('#quick_religion').html(res.religion);
            $('#quick_email').html(res.email);
            $('#quick_mobile_no').html(res.mobileno);
            $('#quick_state').html(res.state);
            $('#quick_address').html(res.address);
            mfp_modal('#quickView');
        }
    });
}
