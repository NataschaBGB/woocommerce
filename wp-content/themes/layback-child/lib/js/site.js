jQuery(document).ready(function ($){

	/* Mobile navigation
	------------------------------------------------------------------ */

	jQuery('#mobileicon, #mobilemenu-overlay').on('click', function () {
		jQuery('body').toggleclass('overflow');
		jQuery('body').toggleclass('open-mobilemenu');
	});

	jQuery('#mobilemenu nav ul li > span').on('click', function () {
		jQuery(this).toggleClass('open');
		jQuery(this).parent().find('.sub-menu').slideToggle(300);
	});

	jQuery('.current-menu-parent span').addClass('open');



	/* 						CUSTOM AJAX
	------------------------------------------------------------------ */

	jQuery('.create_animal').on('click', function(){

		let oThis 		= jQuery(this);

		// GET INPUT VALUES
		var $species 	= $('input[name=species]').val();
		var $race 		= $('input[name=race]').val();
		var $color 		= $('input[name=color]').val();
		var $gender 	= $('select[name=gender]').val();
		var $birthdate 	= $('input[name=birthdate]').val();
		
		// DATE AND TIME
		var $year = new Date().getFullYear();
		var $month = new Date().getMonth()+1;
		var $date = new Date().getDate();
		var $hours = new Date().getHours();
		var $minutes = new Date().getMinutes();
		var $seconds = new Date().getSeconds();
		var minDate = '1980-01-01';
		// same as $updated_at
        var $created_at = $year+'-'+$month+'-'+$date+' '+$hours+'-'+$minutes+'-'+$seconds;

		// data to send
		var data = {
			action: 'ajax_animals_create_single',
			species: $species,
			race: $race,
			color: $color,
			gender: $gender,
			birthdate: $birthdate,
			updated_at: $created_at,
			created_at: $created_at
		}

		jQuery(oThis).css('pointer-events', 'none');
		jQuery(oThis).fadeTo('fast', 0.2);

		// send ajax
		jQuery.ajax({
			url: ajaxurl,
			data: data,
			dataType: 'JSON',
			method: 'POST',
		})
		.done(function(oResult)
		{
			if(oResult.bSucces == true)
			{
				// reload page
				window.location.reload();
				// success - show success sMessage (layback-child\inc\ajax-animals.php)
				alert(oResult.sMessage);

				// add sHTML (layback-child\inc\ajax-animals.php) to table body each time new animal is created
				// sHTML could be table info to insert new rows instead
				// jQuery('table tbody').append(oResult.sHTML);
			}
			else{
				// show what error sMessage in #errorMsg div
				jQuery("#error_create").html(oResult.sMessage);
			}
		})
		.fail(function()
		{
			// failed
		})
		.always(function()
		{
			jQuery(oThis).css('pointer-events', 'auto');
			jQuery(oThis).fadeTo('450', 1);
		});
	});

	jQuery('.animal_update').on('click', function(){
		let oThis 		= jQuery(this);
		let $table_row 	= oThis.closest("tr");
		let $animal_id 	= $table_row.attr("attr-animal_id");

		// ADD INPUTS
		let $species 	= $table_row.find(".species_id").val();
		let $race 		= $table_row.find(".race_id").val();
		let $color 		= $table_row.find(".color_id").val();
		let $gender 	= $table_row.find(".gender_id").val();
		let $birthdate 	= $table_row.find(".birthdate_id").val();
		
		// DATE AND TIME
		var $year = new Date().getFullYear();
		var $month = new Date().getMonth()+1;
		var $date = new Date().getDate();
		var $hours = new Date().getHours();
		var $minutes = new Date().getMinutes();
		var $seconds = new Date().getSeconds();
		var $updated_at = $year+'-'+$month+'-'+$date+' '+$hours+'-'+$minutes+'-'+$seconds;

		if(!$animal_id)
		{
			alert("No ID matches the selected row.");
			return;
		}

		// data to send
		var data = {
			action: 'ajax_animals_update_single',
			id: $animal_id,
			species: $species,
			race: $race,
			color: $color,
			gender: $gender,
			birthdate: $birthdate,
			updated_at: $updated_at
		}

		jQuery(oThis).css('pointer-events', 'none');
		jQuery(oThis).fadeTo('fast', 0.2);

		// send ajax
		jQuery.ajax({
			url: ajaxurl,
			data: data,
			dataType: 'JSON',
			method: 'POST',
		})
		.done(function(oResult)
		{
			if(oResult.bSucces == true)
			{
				// reload page
				window.location.reload();
				// success - show success sMessage (layback-child\inc\ajax-animals.php)
				alert(oResult.sMessage);

				// add sHTML (layback-child\inc\ajax-animals.php) to table body each time new animal is created
				// sHTML could be table info to insert new rows instead
				// jQuery('table tbody').append(oResult.sHTML);
			}
			else{
				// show what error sMessage in #errorMsg div
				jQuery("#error_update").html(oResult.sMessage);
			}
		})
		.always(function()
		{
			jQuery(oThis).css('pointer-events', 'auto');
			jQuery(oThis).fadeTo('450', 1);
		});
	});

	jQuery('.animal_delete').on('click', function(){
		$("#confirmation").show();
		// the clicked 'delete' button
		let oThis = jQuery(this);
		// select the table row closest to clicked 'delete' button
		let $table_row = oThis.closest("tr");
		// id taken from <tr attr-animal_id>
		let $animal_id = $table_row.attr("attr-animal_id");

		if(!$animal_id)
		{
			alert("No ID matches the selected row.");
			return;
		}

		// CONFIRMATION
		var buttonclicked;
		// if cancel_delete (no)
		$('.cancel_delete').click(function(){ 
			if(buttonclicked != false) {
				window.location.reload();
				alert("No animals were deleted!");
				$("#confirmation").hide();
			} 
		});
		$('.confirm_delete').click(function(){
			// if confirm_delete (yes)
			if(buttonclicked != false) {
				var data = {
					action: 'ajax_animals_delete_single',
					id: $animal_id
				}
	
				jQuery(oThis).css('pointer-events', 'none');
				jQuery(oThis).fadeTo('fast', 0.2);
	
				// send ajax
				jQuery.ajax({
					url: ajaxurl,
					data: data,
					dataType: 'JSON',
					method: 'POST',
				})
				.done(function(oResult)
				{
					// success - send ajax and remove tr from db and ui
					$table_row.remove();
					window.location.reload();
					alert(oResult.sMessage);
					$("#confirmation").hide();
				})
				.always(function()
				{
					jQuery(oThis).css('pointer-events', 'auto');
					jQuery(oThis).fadeTo('450', 1);
				}); 
			} 
		});
	});

	/* 						CUSTOM AJAX END
	------------------------------------------------------------------ */




















});



function is_touch_device() {
	var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
	var mq = function (query) {
		return window.matchMedia(query).matches;
	}

	if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
		return true;
	}

	// include the 'heartz' as a way to have a non matching MQ to help terminate the join
	// https://git.io/vznFH
	var query = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
	return mq(query);
}
