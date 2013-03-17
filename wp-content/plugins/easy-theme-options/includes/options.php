<?php

/* ----------------------------------------
* To retrieve a value use: $eto_options[$prefix.'var']
----------------------------------------- */

$prefix = 'eto_';

/* ----------------------------------------
* Create the TABS
----------------------------------------- */

$eto_custom_tabs = array(
		array(
			'label'=> __('General', 'eto'),
			'id'	=> $prefix.'general'
		),
//		array(
//			'label'=> __('Advanced', 'eto'),
//			'id'	=> $prefix.'advanced'
//		)
	);

/* ----------------------------------------
* Options Field Array
----------------------------------------- */

$eto_custom_meta_fields = array(

	/* -- TAB 1 -- */
	array(
		'id'	=> $prefix.'general', // Use data in $eto_custom_tabs
		'type'	=> 'tab_start'
	),
	
	array(
		'label'	=> 'Facebook URL',
		'desc'	=> '',
		'id'	=> $prefix.'facebook_url',
		'type'	=> 'text'
	),
	array(
		'label'	=> 'Twitter URL',
		'desc'	=> '',
		'id'	=> $prefix.'twitter_url',
		'type'	=> 'text'
	),
	array(
		'label'	=> 'Youtube URL',
		'desc'	=> '',
		'id'	=> $prefix.'youtube_url',
		'type'	=> 'text'
	),
	
//	array(
//		'label'=> 'Title',
//		'id'	=> $prefix.'title',
//		'type'	=> 'title'
//	),
//	array(
//		'label'=> 'Text Input',
//		'desc'	=> 'A description for the field.',
//		'id'	=> $prefix.'textinput',
//		'type'	=> 'text'
//	),
//	array(
//		'label'=> 'Password Input',
//		'desc'	=> 'A description for the field.',
//		'id'	=> $prefix.'password',
//		'type'	=> 'password'
//	),
//	array(
//		'label'=> 'Textarea',
//		'desc'	=> 'A description for the field.',
//		'id'	=> $prefix.'textarea',
//		'type'	=> 'textarea'
//	),
//	array(
//		'label'=> 'Checkbox Input',
//		'desc'	=> 'A description for the field.',
//		'id'	=> $prefix.'checkbox',
//		'type'	=> 'checkbox'
//	),
//	array(
//		'label'=> 'Select Box',
//		'desc'	=> 'A description for the field.',
//		'id'	=> $prefix.'select',
//		'type'	=> 'select',
//		'options' => array (
//			'one' => array (
//				'label' => 'Option One',
//				'value'	=> 'one'
//			),
//			'two' => array (
//				'label' => 'Option Two',
//				'value'	=> 'two'
//			),
//			'three' => array (
//				'label' => 'Option Three',
//				'value'	=> 'three'
//			)
//		)
//	),
//	array (
//		'label' => 'Radio Group',
//		'desc'	=> 'A description for the field.',
//		'id'	=> $prefix.'radio',
//		'type'	=> 'radio',
//		'options' => array (
//			'one' => array (
//				'label' => 'Option One',
//				'value'	=> 'one'
//			),
//			'two' => array (
//				'label' => 'Option Two',
//				'value'	=> 'two'
//			),
//			'three' => array (
//				'label' => 'Option Three',
//				'value'	=> 'three'
//			)
//		)
//	),
//	array (
//		'label'	=> 'Checkbox Group',
//		'desc'	=> 'A description for the field.',
//		'id'	=> $prefix.'checkbox_group',
//		'type'	=> 'checkbox_group',
//		'options' => array (
//			'one' => array (
//				'label' => 'Option One',
//				'value'	=> 'one'
//			),
//			'two' => array (
//				'label' => 'Option Two',
//				'value'	=> 'two'
//			),
//			'three' => array (
//				'label' => 'Option Three',
//				'value'	=> 'three'
//			)
//		)
//	),
//	array(
//		'label' => 'Category',
//		'id'	=> 'category',
//		'type'	=> 'tax_select'
//	),
//	array(
//		'label' => 'Post List',
//		'desc' => 'A description for the field.',
//		'id' 	=>  $prefix.'post_id',
//		'type' => 'post_list',
//		'post_type' => array('post','page')
//	),
	
	array(
		'type'	=> 'tab_end'
	),
	/* -- /TAB 1 -- */
	
	/* -- TAB 2 -- */
//	array(
//		'id'	=> $prefix.'advanced', // Use data in $eto_custom_tabs
//		'type'	=> 'tab_start'
//	),
//	
//	array(
//		'label'	=> 'Repeatable',
//		'desc'	=> 'A description for the field. The items inserted here are movable.',
//		'id'	=> $prefix.'repeatable',
//		'type'	=> 'repeatable'
//	),
//	
//	array(
//		'label'	=> 'Date',
//		'desc'	=> 'A description for the field.',
//		'id'	=> $prefix.'date',
//		'type'	=> 'date'
//	),
//	array(
//		'label'	=> 'Image',
//		'desc'	=> 'A description for the field.',
//		'id'	=> $prefix.'image',
//		'type'	=> 'image'
//	),
//	array(
//		'label'	=> 'Slider',
//		'desc'	=> 'A description for the field.',
//		'id'	=> $prefix.'slider',
//		'type'	=> 'slider',
//		'min'	=> '0',
//		'max'	=> '100',
//		'step'	=> '5'
//	),
//	
//	array(
//		'label'	=> 'Color Picker',
//		'desc'	=> 'A description for the field.',
//		'id'	=> $prefix.'colorpicker',
//		'type'	=> 'colorpicker'
//	),
//	
//	array(
//		'type'	=> 'tab_end'
//	)
	/* -- /TAB 2 -- */

);

?>