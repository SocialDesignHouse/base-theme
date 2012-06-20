	
	jQuery(function() {
		if(!jQuery("#num_choices").hasClass('set')) {
			jQuery("#num_choices").hide();
		}
		jQuery(".activate-link").click(function() {
			var change_link = jQuery(this);
			jQuery.post(
				BallotJS.ajaxurl,
				{
					action: 'ballot_activate',
					ballot: jQuery(change_link).attr("data-id")
				},
				function(response) {
					if(response.success) {
						jQuery(change_link).addClass("deactivate-link");
						jQuery(change_link).removeClass("activate-link");
						jQuery(change_link).html("Deactivate");
					} else {
						alert("There was an error activating this ballot. Make sure it's end date hasn't already passed.");
					}
				}
			);
		});
		jQuery(".deactivate-link").click(function() {
			var change_link = jQuery(this);
			jQuery.post(
				BallotJS.ajaxurl,
				{
					action: 'ballot_deactivate',
					ballot: jQuery(this).attr("data-id")
				},
				function(response) {
					if(response.success) {
						jQuery(change_link).addClass("activate-link");
						jQuery(change_link).removeClass("activate-link");
						jQuery(change_link).html("Activate");
					} else {
						alert("There was an error activating this ballot. Make sure it's end date hasn't already passed.");
					}
				}
			);
		});
		jQuery(".delete-link").click(function() {
			var change_ballot = jQuery(this).parent();
			var user_accept = window.confirm('Delete this ballot and the answers and votes associated with it?');
			if(user_accept) {
				jQuery.post(
					BallotJS.ajaxurl,
					{
						action: 'ballot_delete',
						ballot: jQuery(this).attr("data-id")
					},
					function(response) {
						if(response.success) {
							jQuery(change_ballot).remove();
						} else {
							alert("There was an error deleting this ballot. Make sure it hasn't already been deleted.");
						}
					}
				);
			}
		});
		jQuery(".add_answer_link").click(function() {
			var last_answer = parseInt(jQuery(this).attr("data-num")) + 1;
			var insert = '<tr><th><label for"ballot_answer_' + last_answer + '">Answer ' + last_answer + ':</label></th>';
			insert += '<td colspan="3"><input class="ballot_answer default" type="text" name="ballot_answer_' + last_answer + '" id="ballot_answer_' + last_answer + '" value="' + jQuery(this).attr("data-def") + '" /></td></tr>';
			jQuery("#add_answer").before(insert);
			jQuery(this).attr("data-num",last_answer);
		});
		jQuery(".ballot_answer").live("focus", function() {
			var def_text = jQuery(".add_answer_link").attr("data-def");
			if(jQuery(this).val() == def_text) {
				jQuery(this).val('');
				jQuery(this).removeClass('default');
			}
		});
		jQuery(".ballot_answer").live("blur",function() {
			var def_text = jQuery(".add_answer_link").attr("data-def");
			if(jQuery(this).val() == "") {
				jQuery(this).val(def_text);
				jQuery(this).addClass('default');
			}
		});
		jQuery(".ballot_question").bind("focus",function() {
			var def_text = jQuery(this).attr("data-def");
			if(jQuery(this).val() == def_text) {
				jQuery(this).val('');
				jQuery(this).removeClass('default');
			}
		});
		jQuery(".ballot_question").bind("blur",function() {
			var def_text = jQuery(this).attr("data-def");
			if(jQuery(this).val() == '') {
				jQuery(this).val(def_text);
				jQuery(this).addClass('default');
			}
		});
		jQuery("#multi_choice").change(function() {
			if(jQuery(this).is(":checked")) {
				jQuery("#num_choices").show();
			}
		});
		jQuery("#single_choice").change(function() {
			if(jQuery(this).is(":checked")) {
				jQuery("#num_choices").hide();
				jQuery("#ballot_num_choices").addClass('default');
				jQuery("#ballot_num_choices").val('0');
			}
		});
		jQuery("#ballot_num_choices").focus(function() {
			if(jQuery(this).val() == '0') {
				jQuery(this).val('');
				jQuery(this).removeClass('default');
			}
		});
		jQuery("#ballot_num_choices").blur(function() {
			if(jQuery(this).val() == '') {
				jQuery(this).val('0');
				jQuery(this).addClass('default');
			}
		});
	});