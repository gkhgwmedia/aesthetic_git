<?php

/* ------------------------------------------------------------------*/
/* ADD CUSTOM SCRIPTS FOR JQUERY UI */
/* ------------------------------------------------------------------*/

function eto_add_custom_scripts() {
	global $eto_custom_meta_fields, $eto_options;

	// Date Picker
	$output = '<script type="text/javascript">
				jQuery(function() {';

	foreach ($eto_custom_meta_fields as $field) { // loop through the fields looking for certain types
		if($field['type'] == 'date')
			$output .= 'jQuery(".datepicker").datepicker();';
			
		// Slider
		if ($field['type'] == 'slider') {
			$field_id = $field['id'];
			$value = $eto_options["$field_id"] != '' ? $eto_options["$field_id"] : '0';
			
			$output .= '
					jQuery( "#'.$field['id'].'-slider" ).slider({
						value: '.$value.',
						min: '.$field['min'].',
						max: '.$field['max'].',
						step: '.$field['step'].',
						slide: function( event, ui ) {
							jQuery( "#eto_val_slider_'.$field['id'].'" ).val( ui.value );
						}
					});';
		}
	}

	
	$output .= '});
		</script>';

	echo $output;
}

add_action('admin_head','eto_add_custom_scripts');

/* ------------------------------------------------------------------*/
/* CREATE THE FIELDS AND DISPLAY THEM */
/* ------------------------------------------------------------------*/

function eto_show_custom_tabs() {
	
	global $eto_custom_tabs;
	
	echo '<h2 class="nav-tab-wrapper">';
	foreach ($eto_custom_tabs as $tab) {
		echo '<a href="#'.$tab['id'].'" class="nav-tab">'.$tab['label'].'</a>';
	}
	echo '<a href="#help" class="nav-tab">'.__('Help', 'eto').'</a>';
	echo '</h2>';
}

/* ------------------------------------------------------------------*/
/* CREATE THE FIELDS AND DISPLAY THEM */
/* ------------------------------------------------------------------*/

function eto_show_custom_fields() {

	global $eto_custom_meta_fields;
	$prefix = 'eto_';
	
	// Use nonce for verification
	echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

	// Begin the field table and loop
	echo '<div id="tab_container">';

	
	
	foreach ($eto_custom_meta_fields as $field) {
		// get value of this field if it exists for this post
		$eto_options = get_option('eto_settings');
		
		// Begin a new tab
		if( $field['type'] == 'tab_start') {
			echo '<div class="tab_content" id="'.$field['id'].'">';
			echo '<table class="form-table">';
		}

		// begin a table row with
		echo '<tr>';

				if( $field['type'] != 'tab_start' && $field['type'] != 'tab_end') {
					if( $field['type'] == 'title') {
						echo '<th colspan="2"><h3 id="eto_settings['.$field['id'].']">'.$field['label'].'</h3></th>';
					} else {
						echo '<th><label for="eto_settings['.$field['id'].']">'.$field['label'].'</label></th>';
					}
				}
				
		if( $field['type'] != 'tab_start' && $field['type'] != 'tab_end') {
		echo	'<td>';
				if( isset( $eto_options[$field['id']] ) ) {
					$meta = $eto_options[$field['id']];
				} else {
					$meta = '';
				}
				switch($field['type']) {
					// text
					case 'text':
						echo '<input type="text" name="eto_settings['.$field['id'].']" id="eto_settings['.$field['id'].']" value="'.$meta.'" size="30" class="regular-text" />
							<span class="description">'.$field['desc'].'</span>';
					break;
					// text
					case 'password':
						echo '<input type="password" name="eto_settings['.$field['id'].']" id="eto_settings['.$field['id'].']" value="'.$meta.'" size="30" class="regular-text" />
							<span class="description">'.$field['desc'].'</span>';
					break;
					// textarea
					case 'textarea':
						echo '<textarea name="eto_settings['.$field['id'].']" id="eto_settings['.$field['id'].']" cols="60" rows="4">'.$meta.'</textarea>
							<br /><span class="description">'.$field['desc'].'</span>';
					break;
					// checkbox
					case 'checkbox':
						echo '<input type="checkbox" name="eto_settings['.$field['id'].']" id="eto_settings['.$field['id'].']" ',$meta != '' ? ' checked="checked"' : '',' />
							<label for="eto_settings['.$field['id'].']"><span class="description">'.$field['desc'].'</span></label>';
					break;
					// select
					case 'select':
						echo '<select name="eto_settings['.$field['id'].']" id="eto_settings['.$field['id'].']">';
						foreach ($field['options'] as $option) {
							echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
						}
						echo '</select>&nbsp;<span class="description">'.$field['desc'].'</span>';
					break;
					// radio
					case 'radio':
						foreach ( $field['options'] as $option ) {
							echo '<input type="radio" name="eto_settings['.$field['id'].']" id="eto_settings['.$option['value'].']" value="'.$option['value'].'" ',$meta == $option['value'] ? ' checked="checked"' : '',' />
									<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
						}
						echo '<span class="description">'.$field['desc'].'</span>';
					break;
					// checkbox_group
					case 'checkbox_group':
						foreach ($field['options'] as $option) {
							echo '<input type="checkbox" value="'.$option['value'].'" name="eto_settings['.$field['id'].'][]" id="eto_settings['.$option['value'].']"',$meta && in_array($option['value'], $meta) ? ' checked="checked"' : '',' />
									<label for="'.$option['value'].'">'.$option['label'].'</label><br />';
						}
						echo '<span class="description">'.$field['desc'].'</span>';
					break;
					// tax_select
					case 'tax_select':
						echo '<select name="eto_settings['.$field['id'].']" id="eto_settings['.$field['id'].']">
								<option value="">-- '.__('Select','eto').' --</option>'; // Select One
						$terms = get_terms($field['id'], 'get=all');
						$selected = wp_get_object_terms('', 'eto_settings['.$field['id'].']');
						foreach ($terms as $term) {
							if ($selected && $term->slug == $eto_options[$field['id']] )
								echo '<option value="'.$term->slug.'" selected="selected">'.$term->name.'</option>';
							else
								echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
						}
						$taxonomy = get_taxonomy($field['id']);
						echo '</select><br /><span class="description"><a href="'.get_bloginfo('home').'/wp-admin/edit-tags.php?taxonomy='.$field['id'].'">'.__('Manage', 'eto').' '.$taxonomy->label.'</a></span>';
					break; 
					// post_list
					case 'post_list':
						$items = get_posts( array (
							'post_type'	=> $field['post_type'],
							'posts_per_page' => -1
						));
						echo '<select name="eto_settings['.$field['id'].']" id="eto_settings['.$field['id'].']">
								<option value="">-- '.__('Select','eto').' --</option>'; // Select One
							foreach($items as $item) {
								if( $item->post_type == 'page' OR $item->post_type == 'post') {
									$post_type = str_replace('page', __('page', 'eto'), $item->post_type);
									$post_type = str_replace('post', __('post', 'eto'), $item->post_type);
								} else { $post_type = $item->post_type; }
								echo '<option value="'.$item->ID.'"',$eto_options[$field['id']] == $item->ID ? ' selected="selected"' : '','>'.$post_type.': '.$item->post_title.'</option>';
							} // end foreach
						echo '</select>&nbsp;<span class="description">'.$field['desc'].'</span>';
					break;     
					// date
					case 'date':
						echo '<input type="text" class="datepicker" name="eto_settings['.$field['id'].']" id="eto_settings['.$field['id'].']" value="'.$eto_options[$field['id']].'" size="30" />
								<span class="description">'.$field['desc'].'</span>';
					break;
					// image
					case 'image':
						//$image = get_template_directory_uri().'/images/image.png';
						if ($eto_options[$field['id']]) { 
							$image = wp_get_attachment_image_src($eto_options[$field['id']], 'medium');
							$image = $image[0]; } 
						else { 
							$image = ''; 
						}
						echo '<span class="custom_default_image" style="display:none">'.$image.'</span>';
						echo	'<input name="eto_settings['.$field['id'].']" id="eto_settings['.$field['id'].']" type="hidden" class="custom_upload_image" value="'.$eto_options[$field['id']].'" />
									<img src="'.$image.'" class="custom_preview_image" alt="" /><br />
										<input class="custom_upload_image_button button" type="button" value="'.__('Choose Image', 'eto').'" />
										<small> <a href="#" class="custom_clear_image_button">'.__('Remove Image', 'eto').'</a></small>
										<br clear="all" /><span class="description">'.$field['desc'].'';
					break;
					// slider
					case 'slider':
					$field_id = $field['id'];
					$value = $eto_options["$field_id"] != '' ? $eto_options["$field_id"] : '0';
						echo '<div id="'.$field['id'].'-slider"></div>
								<input type="text" name="eto_settings['.$field['id'].']" id="eto_val_slider_'.$field['id'].'" value="'.$value.'" size="5" />
								<br /><span class="description">'.$field['desc'].'</span>';
					break;
					// repeatable
					case 'repeatable':
						echo '
								<ul id="eto_settings['.$field['id'].']-repeatable" class="custom_repeatable">';
						$i = 0;

						if ( $eto_options[$field['id']] ) {
							foreach($eto_options[$field['id']] as $row) {
								echo '<li><span class="sort hndle"><img src="' . ETO_PLUGIN_DIR . 'includes/images/cursor_move.png" /></span>
											<input type="text" name="eto_settings['.$field['id'].']['.$i.']" id="eto_settings['.$field['id'].']" value="'.$row.'" size="30" />
											<a class="repeatable-remove button" href="#">'.__('Delete','eto').'</a></li>';
								$i++;
							}
						} else {
							echo '<li><span class="sort hndle">|||</span>
										<input type="text" name="eto_settings['.$field['id'].']['.$i.']" id="eto_settings['.$field['id'].']" value="" size="30" />
										<a class="repeatable-remove button" href="#">'.__('Delete','eto').'</a></li>';
						}
						echo '</ul>';
						echo '<a class="repeatable-add button" href="#">'.__('Add','eto').'</a>';
						echo '<br /><span class="description">'.$field['desc'].'</span>';
						
					break;
					// colorpicker
					case 'colorpicker':
						echo '<input type="text" class="color" name="eto_settings['.$field['id'].']" id="eto_settings['.$field['id'].']" value="'.$eto_options[$field['id']].'" size="30" />
								<br /><span class="description">'.$field['desc'].'</span>';
						break;

				} //end switch
		}
		echo '</td></tr>';
		
		
		// End a tab
		if( $field['type'] == 'tab_end') {
			echo '</table>';
			echo '</div>';
		}
		
	} // end foreach
	
	
	eto_help_center();
	
	echo '</div>'; // End Div tab container
}

?>