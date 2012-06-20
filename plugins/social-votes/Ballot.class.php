<?php
	
	class Ballot {
		var $id, $question, $answers, $choice, $write_in, $abstain, $timeframe, $start_date, $end_date, $active;
		public function get_options() {
			$options['question'] = get_option('ballots_by_social_default_question');
			$options['answer'] = get_option('ballots_by_social_default_answer');
			$options['answer_number'] = get_option('ballots_by_social_default_answers_number');
			$options['max_years'] = get_option('ballots_by_social_years_in_timeframe');
			return $options;
		}

		public function get_ballot_info($ballot_id) {
			global $wpdb;
			$ballot_info = $wpdb->get_row("SELECT * FROM `wp_ballots_by_social_ballots` B WHERE B.ballot_id = $ballot_id");
			$this->id = $ballot_info->ballot_id;
			$this->question = $ballot_info->ballot_question;
			$this->choice = $ballot_info->ballot_select;
			$this->num_choices = $ballot_info->ballot_num_choices;
			$this->write_in = $ballot_info->ballot_write_ins;
			$this->abstain = $ballot_info->ballot_abstain;
			$this->start_date = $ballot_info->ballot_start;
			$this->end_date = $ballot_info->ballot_end;
			if($this->start_date != '0000-00-00' && $this->end_date != '0000-00-00') {
				$this->timeframe = 1;
			} else {
				$this->timeframe = 0;
			}
			$this->active = $ballot_info->ballot_active;
		}

		public function get_ballot_answers($ballot_id) {
			global $wpdb;
			$ballot_answers = $wpdb->get_results("SELECT * FROM `wp_ballots_by_social_answers` A WHERE A.ballot_id = $ballot_id ORDER BY A.ballot_id");
			$answer_array = array();
			if($wpdb->num_rows > 0) {
				foreach($ballot_answers as $answer) {
					$this_id = $answer->answer_id;
					$answer_array[$this_id] = $answer->answer_body;
				}
			}
			$this->answers = $answer_array;
		}

		public function get_ballot_results($ballot_id) {
			global $wpdb;
			$ballot_results = $wpdb->get_results("SELECT * FROM `wp_ballots_by_social_votes` V WHERE V.ballot_id = $ballot_id");
			$votes_array = array();
			if($wpdb->num_rows > 0) {
				foreach($ballot_results as $result) {
					$this_id = $result->vote_id;
					$votes_array[$this_id]['vote'] = $result->vote_body;
					$votes_array[$this_id]['voter_id'] = $result->vote_voter_id;
				}
			}
			$this->votes = $votes_array;
		}
		
		public function output_ballot_menu($ballot_id) {
			$this->get_ballot_info($ballot_id);
			if($this->active) {
				$action = 'deactivate';
			} else {
				$action = 'activate';
			}	
			$shortcode = '[ballot id="' . $this->id . '"]';
			$edit_link = '<a href="admin.php?page=social-ballots&id=' . $this->id . '&action=edit">Edit</a>';
			$result_link = '<a href="admin.php?page=social-ballots&id=' . $this->id . '&action=results">Results</a>';
			$active_link = '<a class="' . $action . '-link" data-id="' . $this->id . '" href="javascript: void(0)">' . ucfirst($action) . '</a>';
			$delete_link = '<a class="delete-link" data-id="' . $this->id . '" href="javascript: void(0)">Delete</a>';
			$ballot_output = '<tr>';
				$ballot_output .= '<td><input type="checkbox" value="ballot_' . $this->id . '" name="ballot_' . $this->id . '" id="ballot_' . $this->id . '_checkbox" /></td>';
				$ballot_output .= '<td><center>' . $this->id . '</center></td>';
				$ballot_output .= '<td>' . $this->question . '</td>';
				if($this->start_date != '0000-00-00') {
					$ballot_output .= '<td>' . $this->start_date . '</td>';
				} else {
					$ballot_output .= '<td>N/A</td>';
				}
				if($this->end_date != '0000-00-00') {
					$ballot_output .= '<td>' . $this->start_date . '</td>';
				} else {
					$ballot_output .= '<td>N/A</td>';
				}
				$ballot_output .= '<td>' . $shortcode . '</td>';
				$ballot_output .= '<td>' . $edit_link . ' - ' . $result_link . ' - ' . $active_link . ' - ' . $delete_link . '</td>';
			$ballot_output .= '</tr>';
			
			return $ballot_output;
		}
		
		public function check_dates() {
			if($this->active) {
				if($this->timeframe) {
					$curr_date = strtotime(date('Y-m-d'));
					if(strtotime($this->start_date) <= $curr_date || strtotime($this->end_date) >= $curr_date) {
						return true;
					} else {
						return false;
					}
				} else {
					return true;
				}
			} else {
				return false;
			}
		}
		
		public function switch_active_state($ballot_id) {
			global $wpdb;
			$this->get_ballot_info($ballot_id);
			if($this->check_dates()) {
				$entry_array['ballot_active'] = 0;
			} else {
				$entry_array['ballot_active'] = 1;
			}
			$where_array['ballot_id'] = $this->id;
			$switch_active = $wpdb->update('wp_ballots_by_social_ballots',$entry_array,$where_array);
			if($switch_active) {
				$response = json_encode(array('success' => true));
				header("Content-Type: application/json");
				echo $response;
			}
		}
		
		public function delete_ballot($ballot_id) {
			global $wpdb;
			$tables = array('ballots','answers','votes');
			$deleted = array();
			foreach($tables as $table) {
				$delete_sql = $wpdb->query("DELETE FROM `wp_ballots_by_social_" . $table . "` WHERE ballot_id = $ballot_id");
				if($delete_sql) {
					$deleted[] = true;
				} else {
					$deleted[] = false;
				}
			}
			if($deleted[0] && $deleted[1] && $deleted[2]) {
				$response = json_encode(array('success' => true));
				header("Content-Type: application/json");
				echo $response;
			}
		}
		
		public function new_ballot_form() {
			$options = $this->get_options();
			
			$form = '<form id="ballot_form" method="post">' . "\n";
				$form .= '<table class="widefat" style="margin-top: .5em">';
					$form .= '<thead>';
						$form .= '<tr>';
							$form .= '<th colspan="4">Ballot Information</th>';
						$form .= '</tr>';
					$form .= '</thead>';
					$form .= '<tbody>';
						$form .= '<tr>' . "\n";
							$form .= '<th><label for="ballot_question">Question:</label></th>' . "\n";
							$form .= '<td colspan="3"><input class="ballot_question default" data-def="' . $options['question'] . '" type="text" name="ballot_question" id="ballot_question" value="' . $options['question'] . '" /></td>' . "\n";
						$form .= '</tr>' . "\n";
						//run a loop that outputs answer boxes until we reach the default_answers_number amount
						for($i = 0; $i < $options['answer_number']; $i++) {
							$answer_label = $i + 1;
							$form .= '<tr>' . "\n";
								$form .= '<th><label for="ballot_answer_' . $i . '">Answer ' . $answer_label . ':</label>' . "\n";
								$form .= '<td colspan="3"><input class="ballot_answer default" type="text" name="ballot_answer_' . $i .'" id="ballot_answer_' . $i . '" value="' . $options['answer'] . '" /></td>' . "\n";
							$form .= '</tr>' . "\n";
						}
						$form .= '<tr id="add_answer">' . "\n";
							$form .= '<th></th>';
							$form .= '<td colspan="3"><a href="javascript: void(0)" class="add_answer_link" data-num="' . $i . '" data-def="' . $options['answer'] . '">+ Add Another Answer</a></td>';
						$form .= '</tr>' . "\n";
						$form .= '<tr>' . "\n";
							$form .= '<th>Choices:</th>' . "\n";
							$form .= '<td colspan="3">' . "\n";
								$form .= '<label><input type="radio" name="choice_type" id="single_choice" value="single" />&nbsp;&nbsp;Single Answer</label>&nbsp;&nbsp;&nbsp;&nbsp;' . "\n";
								$form .= '<label><input type="radio" name="choice_type" id="multi_choice" value="multiple" />&nbsp;&nbsp;Multiple Choice</label><br />' . "\n";
							$form .= '</td>' . "\n";
						$form .= '</tr>' . "\n";
						$form .= '<tr id="num_choices">' . "\n";
							$form .= '<th>How Many?</th>' . "\n";
							$form .= '<td colspan="3"><input class="ballot_num_choices default" type="text" name="ballot_num_choices" id="ballot_num_choices" value="0" /><br /><p>Leave 0 in box for unlimited selections.</p></td>' . "\n";
						$form .= '</tr>' . "\n";
						$form .= '<tr>' . "\n";
							$form .= '<th>Write Ins:</th>' . "\n";
							$form .= '<td colspan="3">' . "\n";
								$form .= '<label for="allow_write_ins">' . "\n";
									$form .= '<input type="checkbox" name="allow_write_ins" id="allow_write_ins" value="1" />' . "\n";
								$form .= '</label><br />' . "\n";
							$form .= '</td>' . "\n";
						$form .= '</tr>' . "\n";
						$form .= '<tr>' . "\n";
							$form .= '<th>Abstaining:</th>' . "\n";
							$form .= '<td colspan="3">' . "\n";
								$form .= '<label for="allow_abstain">' . "\n";
									$form .= '<input type="checkbox" name="allow_abstain" id="allow_abstain" value="1" />' . "\n";
								$form .= '</label><br />' . "\n";
							$form .= '</td>' . "\n";
						$form .= '</tr>' . "\n";
						$form .= '<tr>' . "\n";
							$form .= '<th>Timeframe:</th>' . "\n";
							$form .= '<td>' . "\n";
								$form .= '<label for="timeframe">' . "\n";
									$form .= '<input type="checkbox" name="timeframe" id="timeframe" value="1" />' . "\n";
								$form .= '</label>' . "\n";
							$form .= '</td>' . "\n";
							$form .= '<td colspan="2">' . "\n";
								$form .= '<div id="date_selection">' . "\n";
									$form .= '<select id="start_month" name="start_month">';
										$months = array();
										for($i = 1; $i <= 12; $i++) {
											$timestamp = mktime(0,0,0,$i,1);
											$months[date('n',$timestamp)] = date('M',$timestamp);
										}
										foreach($months as $num => $name) {
											if($num == date('n')) {
												$select = " selected";
											} else {
												$select = '';
											}
											$form .= '<option value="' . $num . '"' . $select . '>' . $name . '</option>';
										}
									$form .= '</select>';
									$form .= '<select id="start_day" name="start_day">';
										$days = array();
										for($i = 1; $i <= 31; $i++) {
											$timestamp = mktime(0,0,0,0,$i);
											$days[date('j',$timestamp)] = date('d',$timestamp);
										}
										foreach($days as $num => $name) {
											if($num == date('j')) {
												$select = " selected";
											} else {
												$select = '';
											}
											$form .= '<option value="' . $num . '"' . $select . '>' . $name . '</option>';
										}
									$form .= '</select>';
									$form .= '<select id="start_year" name="start_year">';
										$years = array();
										for($i = date('Y'); $i <= date('Y') + $options['max_years']; $i++) {
											$timestamp = mktime(0,0,0,0,0,$i);
											$years[date('Y',$timestamp)] = date('Y',$timestamp);
										}
										foreach($years as $num => $name) {
											if($num == date('Y')) {
												$select = " selected";
											} else {
												$select = '';
											}
											$form .= '<option value="' . $num . '"' . $select . '>' . $name . '</option>';
										}
									$form .= '</select>&nbsp;&nbsp;&nbsp;to&nbsp;&nbsp;&nbsp;';
									$form .= '<select id="end_month" name="end_month">';
										$months = array();
										for($i = 1; $i <= 12; $i++) {
											$timestamp = mktime(0,0,0,$i,1);
											$months[date('n',$timestamp)] = date('M',$timestamp);
										}
										foreach($months as $num => $name) {
											if($num == date('n')) {
												$select = " selected";
											} else {
												$select = '';
											}
											$form .= '<option value="' . $num . '"' . $select . '>' . $name . '</option>';
										}
									$form .= '</select>';
									$form .= '<select id="end_day" name="end_day">';
										$days = array();
										for($i = 1; $i <= 31; $i++) {
											$timestamp = mktime(0,0,0,0,$i);
											$days[date('j',$timestamp)] = date('d',$timestamp);
										}
										foreach($days as $num => $name) {
											if($num == date('j')) {
												$select = " selected";
											} else {
												$select = '';
											}
											$form .= '<option value="' . $num . '"' . $select . '>' . $name . '</option>';
										}
									$form .= '</select>';
									$form .= '<select id="end_year" name="end_year">';
										$years = array();
										for($i = date('Y'); $i <= date('Y') + $options['max_years']; $i++) {
											$timestamp = mktime(0,0,0,0,0,$i);
											$years[date('Y',$timestamp)] = date('Y',$timestamp);
										}
										foreach($years as $num => $name) {
											if($num == date('Y')) {
												$select = " selected";
											} else {
												$select = '';
											}
											$form .= '<option value="' . $num . '"' . $select . '>' . $name . '</option>';
										}
									$form .= '</select>';
								$form .= '</div>' . "\n";
							$form .= '</td>' . "\n";
						$form .= '</tr>' . "\n";
						$form .= '<tr>' . "\n";
							$form .= '<th>Active:</th>' . "\n";
							$form .= '<td colspan="3">' . "\n";
								$form .= '<label for="active">' . "\n";
									$form .= '<input type="checkbox" name="active" id="active" value="1" />' . "\n";
								$form .= '</label><br />' . "\n";
							$form .= '</td>' . "\n";
						$form .= '</tr>' . "\n";
						$form .= '<input type="hidden" name="ballot_status" value="new" />' . "\n";
					$form .= '</tbody>';
				$form .= '</table>';
				$form .= '<p class="submit">' . "\n";
					$form .= '<input type="submit" name="submit" value="Add Ballot" />' . "\n";
					$form .= '<a class="button" href="admin.php?page=social-ballots">Cancel</a>' . "\n";
				$form .= '</p>' . "\n";
			$form .= '</form>' . "\n";
			echo $form;
		}
		
		public function edit_ballot($id) {
			$options = $this->get_options();
			$this->get_ballot_info($id);
			$this->get_ballot_answers($id);
			$form = '<form id="ballot_form" method="post">' . "\n";
				$form .= '<table class="widefat" style="margin-top: .5em">';
					$form .= '<thead>';
						$form .= '<tr>';
							$form .= '<th colspan="4">Ballot Information</th>';
						$form .= '</tr>';
					$form .= '</thead>';
					$form .= '<tbody>';
						$form .= '<tr>' . "\n";
							$form .= '<th><label for="ballot_question">Question:</label></th>' . "\n";
							$form .= '<td colspan="3"><input class="ballot_question" data-def="' . $options['question'] . '" type="text" name="ballot_question" id="ballot_question" value="' . $this->question . '" /></td>' . "\n";
						$form .= '</tr>' . "\n";
						$form .= '<tr>' . "\n";
							$form .= '<th><label>Shortcode:</label></th>' . "\n";
							$form .= '<td colspan="3">' . "\n";
								$form .= '<p>[ballot id="' . $this->id . '"]</p>' . "\n";
								$form .= '<p>Copy and paste the above shortcode into a post or page so users can interact with the ballot.</p>' . "\n";
							$form .= '</td>' . "\n";
						$form .= '</tr>' . "\n";
						$answer_counter = 0;
						foreach($this->answers as $key => $value) {
							$answer_label = $answer_counter + 1;
							$form .= '<tr>' . "\n";
								$form .= '<th><label for"ballot_answer_' . $answer_counter . '">Answer ' . $answer_label . ':</label></th>' . "\n";
								$form .= '<td colspan="3"><input class="ballot_answer" type="text" name="ballot_answer_' . $key . '_new" id="ballot_answer_' . $key . '_new" value="' . $value . '" /></td>' . "\n";
							$form .= '</tr>' . "\n";
							$answer_counter++;
						}
						//run a loop that outputs answer boxes until we reach the default_answers_number amount
						for($i = $answer_counter; $i < $options['answer_number']; $i++) {
							$answer_label = $i + 1;
							$form .= '<tr>' . "\n";
								$form .= '<th><label for="ballot_answer_' . $i . '">Answer ' . $answer_label . ':</label>' . "\n";
								$form .= '<td colspan="3"><input class="ballot_answer default" type="text" name="ballot_answer_' . $i .'" id="ballot_answer_' . $i . '" value="' . $options['answer'] . '" /></td>' . "\n";
							$form .= '</tr>' . "\n";
						}
						$form .= '<tr id="add_answer">' . "\n";
							$form .= '<th></th>';
							$form .= '<td colspan="3"><a href="javascript: void(0)" class="add_answer_link" data-num="' . $answer_counter . '" data-def="' . $options['answer'] . '">+ Add Another Answer</a></td>';
						$form .= '</tr>' . "\n";
						$form .= '<tr>' . "\n";
							$form .= '<th>Choices:</th>' . "\n";
							$form .= '<td colspan="3">' . "\n";
								if($this->choice == "single") {
									$checked = ' checked';
								} else {
									$checked = '';
								}
								$form .= '<label><input type="radio" id="single_choice" name="choice_type" value="single"' . $checked . ' />&nbsp;&nbsp;Single Answer</label>&nbsp;&nbsp;&nbsp;&nbsp;' . "\n";
								if($this->choice == "multiple") {
									$checked = ' checked';
								} else {
									$checked = '';
								}
								$form .= '<label><input type="radio" id="multi_choice" name="choice_type" value="multiple"' . $checked . ' />&nbsp;&nbsp;Multiple Choice</label><br />' . "\n";
							$form .= '</td>' . "\n";
						$form .= '</tr>' . "\n";
						if($this->num_choices != 0) {
							$set = 'set';
							$default = '';
						} else {
							$set = '';
							$defualt = ' default';
						}
						$form .= '<tr id="num_choices" class="' . $set . '">' . "\n";
							$form .= '<th>How Many?</th>' . "\n";
							$form .= '<td colspan="3"><input class="ballot_num_choices' . $default . '" type="text" name="ballot_num_choices" id="ballot_num_choices" value="' . $this->num_choices . '" /><br /><p>Leave 0 in box for unlimited selections.</p></td>' . "\n";
						$form .= '</tr>' . "\n";
						$form .= '<tr>' . "\n";
							$form .= '<th>Write Ins:</th>' . "\n";
							$form .= '<td colspan="3">' . "\n";
								if($this->write_in == 1) {
									$checked = ' checked';
								} else {
									$checked = '';
								}
								$form .= '<label for="allow_write_ins">' . "\n";
									$form .= '<input type="checkbox" name="allow_write_ins" id="allow_write_ins" value="1"' . $checked . ' />' . "\n";
								$form .= '</label><br />' . "\n";
							$form .= '</td>' . "\n";
						$form .= '</tr>' . "\n";
						$form .= '<tr>' . "\n";
							$form .= '<th>Abstaining:</th>' . "\n";
							$form .= '<td colspan="3">' . "\n";
								if($this->abstain == 1) {
									$checked = ' checked';
								} else {
									$checked = '';
								}
								$form .= '<label for="allow_abstain">' . "\n";
									$form .= '<input type="checkbox" name="allow_abstain" id="allow_abstain" value="1"' . $checked . ' />' . "\n";
								$form .= '</label><br />' . "\n";
							$form .= '</td>' . "\n";
						$form .= '</tr>' . "\n";
						$form .= '<tr>' . "\n";
							$form .= '<th>Timeframe:</th>' . "\n";
							$form .= '<td>' . "\n";
								if($this->timeframe == 1) {
									$checked = ' checked';
								} else {
									$checked = '';
								}
								$form .= '<label for="timeframe">' . "\n";
									$form .= '<input type="checkbox" name="timeframe" id="timeframe" value="1"' . $checked . ' />' . "\n";
								$form .= '</label>' . "\n";
							$form .= '</td>' . "\n";
							$form .= '<td colspan="2">' . "\n";
								if($this->start_date != '0000-00-00' && $this->end_date != '0000-00-00') {
									$start_array = explode('-',$this->start_date);
									$end_array = explode('-',$this->end_date);
								}
								$form .= '<div id="date_selection">' . "\n";
									$form .= '<select id="start_month" name="start_month">';
										$months = array();
										for($i = 1; $i <= 12; $i++) {
											$timestamp = mktime(0,0,0,$i,1);
											$months[date('n',$timestamp)] = date('M',$timestamp);
										}
										foreach($months as $num => $name) {
											if($this->timeframe) {
												if($num == (int) substr($start_array[1],1)) {
													$select = ' selected';
												} else {
													$select = '';
												}
											} else {
												if($num == date('n')) {
													$select = ' selected';
												} else {
													$select = '';
												}
											}
											$form .= '<option value="' . $num . '"' . $select . '>' . $name . '</option>';
										}
									$form .= '</select>';
									$form .= '<select id="start_day" name="start_day">';
										$days = array();
										for($i = 1; $i <= 31; $i++) {
											$timestamp = mktime(0,0,0,0,$i);
											$days[date('j',$timestamp)] = date('d',$timestamp);
										}
										foreach($days as $num => $name) {
											if($this->timeframe) {
												if($num == (int) $start_array[2]) {
													$select = ' selected';
												} else {
													$select = '';
												}
											} else {
												if($num == date('j')) {
													$select = " selected";
												} else {
													$select = '';
												}
											}
											$form .= '<option value="' . $num . '"' . $select . '>' . $name . '</option>';
										}
									$form .= '</select>';
									$form .= '<select id="start_year" name="start_year">';
										$years = array();
										for($i = date('Y'); $i <= date('Y') + $options['max_years']; $i++) {
											$timestamp = mktime(0,0,0,0,0,$i);
											$years[date('Y',$timestamp)] = date('Y',$timestamp);
										}
										foreach($years as $num => $name) {
											if($this->timeframe) {
												if($num == (int) $start_array[0]) {
													$select = ' selected';
												} else {
													$select = '';
												}
											} else {
												if($num == date('Y')) {
													$select = " selected";
												} else {
													$select = '';
												}
											}
											$form .= '<option value="' . $num . '"' . $select . '>' . $name . '</option>';
										}
									$form .= '</select>&nbsp;&nbsp;&nbsp;to&nbsp;&nbsp;&nbsp;';
									$form .= '<select id="end_month" name="end_month">';
										$months = array();
										for($i = 1; $i <= 12; $i++) {
											$timestamp = mktime(0,0,0,$i,1);
											$months[date('n',$timestamp)] = date('M',$timestamp);
										}
										foreach($months as $num => $name) {
											if($this->timeframe) {
												if($num == (int) substr($end_array[1],1)) {
													$select = ' selected';
												} else {
													$select = '';
												}
											} else {
												if($num == date('n')) {
													$select = ' selected';
												} else {
													$select = '';
												}
											}
											$form .= '<option value="' . $num . '"' . $select . '>' . $name . '</option>';
										}
									$form .= '</select>';
									$form .= '<select id="end_day" name="end_day">';
										$days = array();
										for($i = 1; $i <= 31; $i++) {
											$timestamp = mktime(0,0,0,0,$i);
											$days[date('j',$timestamp)] = date('d',$timestamp);
										}
										foreach($days as $num => $name) {
											if($this->timeframe) {
												if($num == (int) $end_array[2]) {
													$select = ' selected';
												} else {
													$select = '';
												}
											} else {
												if($num == date('j')) {
													$select = " selected";
												} else {
													$select = '';
												}
											}
											$form .= '<option value="' . $num . '"' . $select . '>' . $name . '</option>';
										}
									$form .= '</select>';
									$form .= '<select id="end_year" name="end_year">';
										$years = array();
										for($i = date('Y'); $i <= date('Y') + $options['max_years']; $i++) {
											$timestamp = mktime(0,0,0,0,0,$i);
											$years[date('Y',$timestamp)] = date('Y',$timestamp);
										}
										foreach($years as $num => $name) {
											if($this->timeframe) {
												if($num == (int) $end_array[0]) {
													$select = ' selected';
												} else {
													$select = '';
												}
											} else {
												if($num == date('Y')) {
													$select = " selected";
												} else {
													$select = '';
												}
											}
											$form .= '<option value="' . $num . '"' . $select . '>' . $name . '</option>';
										}
									$form .= '</select>';
								$form .= '</div>' . "\n";
							$form .= '</td>' . "\n";
						$form .= '</tr>' . "\n";
						$form .= '<tr>' . "\n";
							$form .= '<th>Active:</th>' . "\n";
							$form .= '<td colspan="3">' . "\n";
								if($this->active) {
									$checked = ' checked';
								} else {
									$checked = '';
								}
								$form .= '<label for="active">' . "\n";
									$form .= '<input type="checkbox" name="active" id="active" value="1"' . $checked . ' />' . "\n";
								$form .= '</label><br />' . "\n";
							$form .= '</td>' . "\n";
						$form .= '</tr>' . "\n";
						$form .= '<input type="hidden" name="ballot_status" value="old" />' . "\n";
						$form .= '<input type="hidden" name="ballot_id" value="' . $id . '"/>' . "\n";
					$form .= '</tbody>';
				$form .= '</table>';
				$form .= '<p class="submit">' . "\n";
					$form .= '<input type="submit" name="submit" value="Update Ballot" />' . "\n";
					$form .= '<a class="button" href="admin.php?page=social-ballots">Cancel</a>' . "\n";
				$form .= '</p>' . "\n";
			$form .= '</form>' . "\n";
			echo $form;
		}
		
		public function process_form() {
			$options = $this->get_options();
			if($_REQUEST['ballot_question'] != $options['question']) {
				$this->question = $this->sanitize_input($_REQUEST['ballot_question']);
			}
			$answer_array = array();
			foreach($_REQUEST as $key => $value) {
				$key_array = explode("_",$key);
				if($key_array[1] == "answer" && $value != $options['answer'] && $value != '') {
					if($key_array[3] == "new") {
						$answer_array['update'][$key_array[2]] = $this->sanitize_input($value);
					} else {
						$answer_array['insert'][] = $this->sanitize_input($value);
					}
				}
			}
			$this->answers = $answer_array;
			$this->choice = $_REQUEST['choice_type'];
			$this->num_choices = $_REQUEST['ballot_num_choices'];
			if($_REQUEST['allow_write_ins']) {
				$this->write_in = 1;
			} else {
				$this->write_in = 0;
			}
			if($_REQUEST['allow_abstain']) {
				$this->abstain = 1;
			} else {
				$this->abstain = 0;
			}
			if($_REQUEST['timeframe']) {
				$this->timeframe = true;
			} else {
				$this->timeframe = false;
			}
			if($this->timeframe) {
				if(strlen($_REQUEST['start_month']) == 1) {
					$_REQUEST['start_month'] = "0" . $_REQUEST['start_month'];
				}
				if(strlen($_REQUEST['end_month']) == 1) {
					$_REQUEST['end_month'] = "0" . $_REQUEST['end_month'];
				}
				$this->start_date = $_REQUEST['start_year'] . '-' .  $_REQUEST['start_month'] . '-' . $_REQUEST['start_day'];
				$this->end_date = $_REQUEST['end_year'] . '-' . $_REQUEST['end_month'] . '-' . $_REQUEST['end_day'];
			} else {
				$this->start_date = '0000-00-00';
				$this->end_date = '0000-00-00';
			}
			if($_REQUEST['active']) {
				$this->active = 1;
			} else {
				$this->active = 0;
			}
			if($_REQUEST['ballot_status'] == "new") {
				$this->insert_ballot();
			} else {
				$this->update_ballot($_REQUEST['ballot_id']);
			}
		}
		
		public function insert_ballot() {
			global $wpdb;
			$entry_array = array();
			$entry_array['ballot_question'] = $this->question;
			$entry_array['ballot_select'] = $this->choice;
			$entry_array['ballot_num_choices'] = $this->num_choices;
			$entry_array['ballot_write_ins'] = $this->write_in;
			$entry_array['ballot_abstain'] = $this->abstain;
			$entry_array['ballot_start'] = $this->start_date;
			$entry_array['ballot_end'] = $this->end_date;
			$entry_array['ballot_active'] = $this->active;
			$ballot_insert = $wpdb->insert('wp_ballots_by_social_ballots',$entry_array);
			$this->insert_answers($wpdb->insert_id);
		}
		
		public function insert_answers($id) {
			global $wpdb;
			foreach($this->answers['insert'] as $answer) {
				$entry_array = array();
				$entry_array['ballot_id'] = $id;
				$entry_array['answer_body'] = $answer;
				$answer_insert = $wpdb->insert('wp_ballots_by_social_answers',$entry_array);
			}
			$this->generate_short_code($id);
		}
		
		public function update_ballot($id) {
			global $wpdb;
			$entry_array = array();
			$entry_array['ballot_question'] = $this->question;
			$entry_array['ballot_select'] = $this->choice;
			$entry_array['ballot_num_choices'] = $this->num_choices;
			$entry_array['ballot_write_ins'] = $this->write_in;
			$entry_array['ballot_abstain'] = $this->abstain;
			$entry_array['ballot_start'] = $this->start_date;
			$entry_array['ballot_end'] = $this->end_date;
			$entry_array['ballot_active'] = $this->active;
			$where_array['ballot_id'] = $id;
			$ballot_update = $wpdb->update('wp_ballots_by_social_ballots',$entry_array,$where_array);
			$this->update_answers($where_array['ballot_id']);
		}
		
		public function update_answers($id) {
			global $wpdb;
			foreach($this->answers as $key => $value) {
				if($key == "update") {
					foreach($value as $key2 => $value2) {
						$entry_array = array();
						$where_array = array();
						$where_array['ballot_id'] = $id;
						$where_array['answer_id'] = $key2;
						$entry_array['answer_body'] = $value2;
						$answer_update = $wpdb->update('wp_ballots_by_social_answers',$entry_array,$where_array);
					}
				} else {
					foreach($value as $key2 => $value2) {
						$entry_array = array();
						$entry_array['ballot_id'] = $id;
						$entry_array['answer_body'] = $value2;
						$answer_insert = $wpdb->insert('wp_ballots_by_social_answers',$entry_array);
				}
				}
			}
			$this->generate_short_code($id);
		}
		
		public function generate_short_code($id) {
			echo '<div id="message" class="updated fade">
				<p>Ballot created. Paste the following shortcode into your post or page: [ballot id="' . $id . '"]</p>
			</div>';
		}
		
		public function sanitize_input($input) {
			return filter_var(strip_tags($input), FILTER_SANITIZE_STRING);
		}
		
		public function process_shortcode($id,$vote_id) {
			global $wpdb;
			$this->get_ballot_info($id);
			$check_id = $wpdb->get_results("SELECT * FROM `wp_ballots_by_social_votes` V WHERE V.vote_voter_id = '$vote_id'");
			if($check_id) {
				$msg = '<div><p>You have already voted on "<strong>' . $this->question . '</strong>"</p></div>';
				return $msg;
			} else {
				$this->get_ballot_answers($id);
				if($this->check_dates()) {
					$display_ballot = $this->display_ballot($vote_id);
					return $display_ballot;
				} else {
					$msg = '<div><p>Voting for "<strong>' . $this->question . '</strong>" is closed.</p></div>';
					return $msg;
				}
			}
		}
		
		public function display_ballot($vote_id) {
			$form = '<div class="ballot" id="ballot-' . $this->id . '">' . "\n";
				$form .= '<form class="ballot-form" id="ballot-form-' . $this->id . '" method="post">' . "\n";
					$form .= '<h3 class="ballot-question">' . $this->question . '</h3>';
					//set up type of selection
					if($this->choice == 'single') {
						$type = 'radio';
						$name = false;
						$num_choice_msg = false;
					} else {
						$type = 'checkbox';
						$name = true;
						$num_choice_msg = true;
					}
					if($num_choice_msg && $this->num_choices != 0) {
						$form .= '<p>Choose ' . $this->num_choices . ' answers.</p>';
					}
					//output admin-defined answers
					foreach($this->answers as $key => $value) {
						if($name) {
							$input_name = 'answer-' . $key;
						} else {
							$input_name = 'vote-' . $this->id;
						}
						$form .= '<label for="answer-' . $key . '">' . "\n";
							$form .= '<input type="' . $type . '" value="' . $key . '" class="ballot-answer answer-' . $type . '" id="answer-' . $key . '" name="' . $input_name . '" /> ' . "\n";
							$form .= '<span>' . $value . '</span>';
						$form .= '</label><br />' . "\n";
					}
					//check for abstain vote
					if($this->abstain) {
						if($name) {
							$input_name = 'answer-abstain-' . $this->id;
						} else {
							$input_name = 'vote-' . $this->id;
						}
						$form .= '<label for="answer-abstain-' . $this->id . '">' . "\n";
							$form .= '<input type="' . $type . '" value="abstain" class="ballot-answer answer-' . $type . ' answer-abstain" id="answer-abstain-' . $this->id . '" name="' . $input_name . '" /> <span>Abstain</span>' . "\n";
						$form .= '</label><br />' . "\n";
					}
					//check for write-in vote
					if($this->write_in) {
						if($name) {
							$input_name = 'answer-other-' . $this->id;
						} else {
							$input_name = 'vote-' . $this->id;
						}
						$form .= '<label for="answer-other-' . $this->id . '">' . "\n";
							$form .= '<input type="' . $type . '" value="other" class="ballot-answer answer-' . $type . ' answer-other" id="answer-other-' . $this->id . '" name="' . $input_name . '" /> <span>Write-In</span>' . "\n";
						$form .= '</label>' . "\n";
						$form .= '<input type="text" value="Please Specify" class="answer-other-text" id="answer-other-' . $this->id . '-text" name="answer-other-' . $this->id . '-text" /><br />' . "\n";
						if($this->num_choices != 0) {
							$form .= '<p class="answer-other-extra">To write-in more than one answer, please separate answers with commas (,). No spaces are needed between answers and commas.</p><br />' . "\n";
						}
					}
					$form .= '<input type="hidden" value="' . $this->num_choices . '" name="num_choices" class="num_choices" id="ballot_' . $this->id . '_num_choices" />';
					//add encoded vote id to the form
					$form .= '<input type="hidden" class="ballot-vote-id" id="ballot-vote-id-' . $this->id . '" value="' . $vote_id . '" />' . "\n";
					$form .= '<input type="hidden" class="ballot-id" id="ballot-id-' . $this->id . '" value="' . $this->id . '" />' . "\n";
					$form .= '<input type="button" class="ballot-submit-button" id="ballot-submit-' . $this->id . '" value="Vote" />' . "\n";
				$form .= '</form>' . "\n";
			$form .= '</div>' . "\n";
			return $form;
		}
		
		public function submit_ballot($vote_id,$ballot_id,$voter_id) {
			global $wpdb;
			$entry_array = array();
			
			$this->get_ballot_info($ballot_id);
			$this->get_ballot_answers($ballot_id);

			if(strpos($vote_id,",")) {
				$vote_array = explode(",",$vote_id);
				$answer_body = "";
				foreach($vote_array as $vote) {
					$vote = $this->sanitize_input($vote);
					if($this->answers[$vote]) {
						$answer_body .= $this->answers[$vote] . ",";
					} else {
						$answer_body .= $vote . ",";
					}	
				}
				$answer_body = substr($answer_body,0,-1);
			} else {
				if($this->answers[$vote_id]) {
					$answer_body = $this->answers[$vote_id];
				} else {
					$answer_body = $vote_id;
				}
			}

			$entry_array['ballot_id'] = $ballot_id;
			$entry_array['vote_body'] = $answer_body;
			$entry_array['vote_voter_id'] = $voter_id;

			$insert_vote = $wpdb->insert('wp_ballots_by_social_votes',$entry_array);

			if($insert_vote) {
				$response = json_encode(array('success' => true));
				header("Content-Type: application/json");
				echo $response;
			}
		}
		
		public function ballot_results($ballot_id) {
			$this->get_ballot_info($ballot_id);
			$this->get_ballot_answers($ballot_id);
			$this->get_ballot_results($ballot_id);
			
			$output = '<h3>Ballot: ' . $this->question . '</h3>' . "\n";
			$output .= '<table class="widefat" style="margin-top: .5em">' . "\n";
				$vote_array = array();
				$write_in_array = array();
				$answer_array = array();
				$answer_array = $this->answers;
				$total_votes = 0;
				$abstainers = 0;
				foreach($this->votes as $key => $value) {
					if($value['vote'] != 'abstain') {
						if(strpos($value['vote'],",")) {
							$this_vote_array = explode(",",$value['vote']);
							foreach($this_vote_array as $vote) {
								$found = false;
								foreach($answer_array as $answer) {
									if($vote == $answer) {
										$found = true;
										break;
									} else {
										$found = false;
									}
								}
								if($found) {
									if($vote_array[$vote]) {
										$vote_array[$vote]++;
										$total_votes++;
									} else {
										$vote_array[$vote] = 1;
										$total_votes++;
									}
								} else {
									if($write_in_array[$vote]) {
										$write_in_array[$vote]++;
										$total_votes++;
									} else {
										$write_in_array[$vote] = 1;
										$total_votes++;
									}
								}
							}
						} else {
							$found = false;
							foreach($answer_array as $answer) {
								if($value['vote'] == $answer) {
									$found = true;
									break;
								} else {
									$found = false;
								}
							}
							if($found) {
								if($vote_array[$value['vote']]) {
									$vote_array[$value['vote']]++;
									$total_votes++;
								} else {
									$vote_array[$value['vote']] = 1;
									$total_votes++;
								}
							} else {
								if($write_in_array[$value['vote']]) {
									$write_in_array[$value['vote']]++;
									$total_votes++;
								} else {
									$write_in_array[$value['vote']] = 1;
									$total_votes++;
								}
							}
						}
					} else {
						$abstainers++;
					}
				}
				$output .= '<tbody>' . "\n";
					$output .= '<tr>' . "\n";
						$output .= '<td><center><span class="big_num">' . $total_votes . '</span><br />Votes</center></td>' . "\n";
						$output .= '<td><center><span class="big_num">' . count($this->votes) . '</span><br />Voters</center></td>' . "\n";
						$output .= '<td><center><span class="big_num">' . $abstainers . '</span><br />Abstained</center></td>' . "\n";
					$output .= '</tr>' . "\n";
				$output .= '</tbody>';
			$output .= '</table>';
			
			arsort($vote_array,SORT_NUMERIC);
			
			$output .= '<h3>Votes</h3>';
			$output .= '<table class="widefat" style="margin-top: .5em">' . "\n";
				$output .= '<thead>' . "\n";
					$output .= '<tr>' . "\n";
						$output .= '<th width="33%">Answer</th>' . "\n";
						$output .= '<th width="33%">Votes</th>' . "\n";
						$output .= '<th width="33%">Percentage</th>' . "\n";
					$output .= '</tr>' . "\n";
				$output .= '</thead>' . "\n";
				$output .= '<tbody>' . "\n";
					foreach($vote_array as $key => $value) {
						$percentage = $value / $total_votes * 100;
						$output .= '<tr><td>' . $key . '</td><td>' . $value . '</td><td>' . round($percentage,2) . '%</td></tr>' . "\n";
					}
				$output .= '</tbody>' . "\n";
			$output .= '</table>' . "\n";
			
			arsort($write_in_array,SORT_NUMERIC);
			
			$output .= '<h3>Write Ins</h3>';
			$output .= '<table class="widefat" style="margin-top: .5em">' . "\n";
				$output .= '<thead>' . "\n";
					$output .= '<tr>' . "\n";
						$output .= '<th width="33%">Answer</th>' . "\n";
						$output .= '<th width="33%">Votes</th>' . "\n";
						$output .= '<th width="33%">Percentage</th>' . "\n";
					$output .= '</tr>' . "\n";
				$output .= '</thead>' . "\n";
				$output .= '<tbody>' . "\n";
					foreach($write_in_array as $key => $value) {
						$percentage = $value / $total_votes * 100;
						$output .= '<tr><td>' . $key . '</td><td>' . $value . '</td><td>' . round($percentage,2) . '%</td></tr>' . "\n";
					}
				$output .= '</tbody>' . "\n";
			$output .= '</table>' . "\n";
			
			echo $output;
		}
	}
	
?>