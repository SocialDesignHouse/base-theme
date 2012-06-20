	$(function() {
		if($(".ballot-form").length > 0) {
			var double_answer = false;
			var other_def_text = "Please Specify";	
			$(".answer-other-text").hide();
			$(".answer-other-extra").hide();	
			$("label").click(function() {
				if($(this).find(".answer-radio") || $(this).find(".answer-checkbox")) {
					if($(this).find(".answer-other").length > 0) {
						if($(this).find(".answer-other").is(":checked")) {
							$(this).parent().find(".answer-other-text").show();
							$(this).parent().find(".answer-other-extra").show();
						} else {
							$(this).parent().find(".answer-other-text").hide();
							$(this).parent().find(".answer-other-extra").hide();
						}
					} else {
						if($(this).find(".answer-radio").length > 0) {
							$(this).parent().find(".answer-other-text").hide();
							$(this).parent().find(".answer-other-extra").hide();
						}
					}
					if($(this).find(".answer-abstain").length > 0) {
						$(this).parent().find("input").each(function() {
							if($(this).is(":checked")) {
								if($(this).hasClass("answer-abstain")) {
									$(this).attr("checked",true);
								} else {
									$(this).attr("checked",false);
								}
							}
						});
						if($(".answer-other-text").is(":visible")) {
							$(".answer-other-text").hide();
						}
					} else {
						if($(this).parent().find(".answer-abstain").is(":checked")) {
							$(this).parent().find(".answer-abstain").attr("checked",false);
						}
					}
				}
			});	
			$(".answer-other-text").focus(function() {
				if($(this).val() == other_def_text) {
					$(this).val("");
				}
			});	
			$(".answer-other-text").blur(function() {
				if($(this).val() == "") {
					$(this).val(other_def_text);
				} else {
					var check_val_array = $(this).val().split(',');
					for(var i = 0; i < check_val_array.length; i++) {
						$(".ballot-answer").each(function() {
							var check_against = $(this).next('span').html();
							if(check_val_array[i] == check_against) {
								double_answer = true;
							}
						});
					}
				}
			});	
			$(".ballot-submit-button").click(function() {
				$(this).hide();
				var form = $(this).parent();
				var data = "";
				var valid = false;
				var limit_choices = false;
				var too_many = false;
				var num_choices = $(form).find(".num_choices").val();
				if(num_choices) {
					limit_choices = true;
				}
				
				$(".error").remove();
				
				if($(form).find(".answer-radio").length > 0) {
					if($(form).find(".answer-other-text").val() != other_def_text && $(form).find(".answer-other").is(":checked")) {
						data = $(form).find(".answer-other-text").val();
						valid = true;
					} else {
						$(form).find(".answer-radio").each(function() {
							if($(this).is(":checked")) {
								data = $(this).val();
								valid = true;
							}
						});
					}
				} else {
					if($(form).find(".answer-other-text").val() != other_def_text) {
						data = $(form).find(".answer-other-text").val() + ",";
					}
					var data_val = "";
					$(form).find(".answer-checkbox").each(function() {
						if($(this).is(":checked") && $(this).val() != 'other') {
							data_val += $(this).val() + ",";
						}
					});
					data += data_val;
					var end_pos = data.length - 1;
					data = data.substring(0,end_pos);
					if(limit_choices) {
						if(data.split(',').length > num_choices) {
							too_many = true;
						}
					}
					var data_array = data.split(',');
					for(var i = 0; i < data_array.length; i++) {
						for(var j = 0; j < data_array.length; j++) {
							if(j != i) {
								if(data_array[i] == data_array[j]) {
									double_answer = true;
								}
							}
						}
					}
					if(data.length > 0) {
						valid = true;
					}
				}
				
				var ballot_id = $(form).find(".ballot-id").val();
				var vote_id = $(form).find(".ballot-vote-id").val();
				if(valid) {
					if(!too_many) {
						if(!double_answer) {
							jQuery.post(
								BallotJS.ajaxurl,
								{
									action: 'ballot_ajax_submit',
									bid: ballot_id,
									vid: vote_id,
									vote: data
								},
								function(response) {
									if(response.success) {
										$(form).parent().html("<p>Thanks for voting.</p>");
									} else {
										$(form).prepend("<p class='error'>There was an error submitting your ballot. Please try again or <a href='/contact-us/'>contact</a> an administrator if the problem persists.</p>");
									}
								}
							);
							$(form).prepend('<img src="/wp-content/themes/snagmetalsmith/_/images/loader.gif" width="16" height="16" alt="Submitting Vote..." />');
						} else {
							$(form).prepend("<p class='error'>You cannot vote for the same answer more than once.</p>");
						}
					} else {
						$(form).prepend("<p class='error'>You may only select " + num_choices + " answers.</p>");
					}
				} else {
					$(form).prepend("<p class='error'>Please vote before submitting your ballot.</p>");
				}
			});
		}
	});

