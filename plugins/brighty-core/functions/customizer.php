<?php

/**
* Using brighty-core adding options
*/


/**
 * Add Panels
 */


new \Kirki\Panel(
	'brighty-user',
	[
		'priority'    => 10,
		'title'       => esc_html__( 'Brighty: User Dashboard', 'brighty-core' ),
		'description' => esc_html__( 'Customize User Dashboard of Birighty', 'brighty-core' ),
	]
);

/**
 * Add options for user dashboard
 */

new \Kirki\Section(
	'my-notifications',
	[
		'title'       => esc_html__( 'My Notification', 'brighty-core' ),
		'description' => esc_html__( 'You can control options for notifications feature here', 'brighty-core' ),
		'panel'       => 'brighty-user',
		'priority'    => 160,
	]
);


new \Kirki\Section(
	'my-documents',
	[
		'title'       => esc_html__( 'My Documents', 'brighty-core' ),
		'description' => esc_html__( 'Control Documents Settings from Here. Select Which Documents You will Require from Users', 'brighty-core' ),
		'panel'       => 'brighty-user',
		'priority'    => 160,
	]
);




new \Kirki\Section(
	'my-profile',
	[
		'title'       => esc_html__( 'My Profile', 'brighty-core' ),
		'description' => esc_html__( 'Control Profile Settings from Here. Select Which Documents You will Require from Users', 'brighty-core' ),
		'panel'       => 'brighty-user',
		'priority'    => 160,
	]
);





/**
 * Add options for admin dashboard
 */

// NOTIFICATIONS: : allows users to turn notifications feature on or off
new \Kirki\Field\Checkbox_Switch(
	[
		'settings'    => 'enable-notifications',
		'label'       => esc_html__( 'Enable Notifications Feature', 'brighty-core' ),
		'section'     => 'my-notifications',
		'default'     => 'on',
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'brighty-core' ),
			'off' => esc_html__( 'Disable', 'brighty-core' ),
		],
	]
);

new \Kirki\Field\Repeater(
	[
		'settings'     => 'notification-types',
		'label'        => esc_html__( 'Notification Opt-Ins', 'brighty-core' ),
		'section'      => 'my-notifications',
		'priority'     => 10,
		'row_label'    => [
			'type'  => 'field',
			'value' => esc_html__( '', 'brighty-core' ),
			'field' => 'notification-name',
		],
		'button_label' => esc_html__( 'Add new notification Type', 'brighty-core' ),
		'default'      => [
			[
				'notification-name'   => esc_html__( 'Company News', 'brighty-core' ),
				'notification-description'    => 'Get Rocket news, announcements, and product updates',
                'notification-status'   => 'ON',
				'notification-id'   => 'companynews'
			],
			[
				'notification-name'   => esc_html__( 'Account Activity', 'brighty-core' ),
				'notification-description'    => 'Get important notifications about you or activity you\'ve missed',
                'notification-status'   => 'ON',
				'notification-id'   => 'companyaccountactivity'
			],
			[
				'notification-name'   => esc_html__( 'Meetups Near You', 'brighty-core' ),
				'notification-description'    => 'Get an email when a Dribbble Meetup is posted close to my location',
                'notification-status'   => 'ON',
				'notification-id'   => 'meetups'
			],
		],
		'fields'       => [
			'notification-name'   => [
				'type'        => 'text',
				'label'       => esc_html__( 'Notification Name', 'brighty-core' ),
				'default'     => '',
			],
			'notification-description'    => [
				'type'        => 'textarea',
				'label'       => esc_html__( 'Notification Description', 'brighty-core' )
			],
			'notification-status'    => [
				'type'        => 'radio',
				'label'       => esc_html__( 'Default Status', 'brighty-core' ),
				'default'     => '',
                'choices'     => [
					'OFF' => esc_html__( 'OFF', 'brighty-core' ),
					'ON'  => esc_html__( 'ON', 'brighty-core' ),
				]
			],
			
			'notification-id'   => [
				'type'        => 'text',
				'label'       => esc_html__( 'Notification ID', 'brighty-core' ),
				'default'     => 'notification'.rand(99,1000),
				'description' =>'Enter number or name without spaces in small letters no hyphen,underscore,or symbol'
			],
		],
	]
);



// DOCUMENTS: : allows users to turn notifications feature on or off
new \Kirki\Field\Checkbox_Switch(
	[
		'settings'    => 'enable_documents',
		'label'       => esc_html__( 'Enable Documents Feature', 'brighty-core' ),
		'section'     => 'my-documents',
		'default'     => 'on',
		'choices'     => [
			'on'  => esc_html__( 'Enable', 'brighty-core' ),
			'off' => esc_html__( 'Disable', 'brighty-core' ),
		],
	]
);

new \Kirki\Field\Repeater(
	[
		'settings'     => 'documents_required',
		'label'        => esc_html__( 'Documents Required', 'brighty-core' ),
		'section'      => 'my-documents',
		'priority'     => 10,
		'row_label'    => [
			'type'  => 'field',
			'value' => esc_html__( '', 'brighty-core' ),
			'field' => 'document-name',
		],
		'button_label' => esc_html__( 'Add new document type', 'brighty-core' ),
		'default'      => [
			[
				'document-name'   => esc_html__( 'ID Card', 'brighty-core' ),
				'document-description'    => 'JPG, GIF or PNG. Max size of 800K',
				'document-id' =>'idcard',
                'file-type' => '.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
			],
			[
				'document-name'   => esc_html__( 'Address Proof', 'brighty-core' ),
				'document-description'    => 'JPG, GIF or PNG. Max size of 800K',
				'document-id' => 'addressproof',
                'file-type' => '.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document'
			]
		],
		'fields'       => [
			'document-name'   => [
				'type'        => 'text',
				'label'       => esc_html__( 'Document Name', 'brighty-core' ),
				'default'     => '',
			],
			'document-description'    => [
				'type'        => 'textarea',
				'label'       => esc_html__( 'Document  Description', 'brighty-core' )
            ],
			'file-type'    => [
				'type'        => 'text',
				'label'       => esc_html__( 'Document Type', 'brighty-core' )
            ],
			
			'document-id'    => [
				'type'        => 'text',
				'description' => 'Document ID without any symbol or space. used for backend ',
				'label'       => esc_html__( 'Document ID', 'brighty-core' )
            ],
		],
	]
);


// PROFILE: : allows users to turn notifications feature on or off
new \Kirki\Field\Checkbox_Switch(
	[
		'settings'    => 'enable_profile',
		'label'       => esc_html__( 'Enable Profile Feature', 'brighty-core' ),
		'section'     => 'my-profile',
		'default'     => 'on',
		'choices'     => [
			'ON'  => esc_html__( 'ON', 'brighty-core' ),
			'OFF' => esc_html__( 'OFF', 'brighty-core' ),
		],
	]
);

new \Kirki\Field\Checkbox_Switch(
	[
		'settings'    => 'enable_account_manager',
		'label'       => esc_html__( 'Enable Account Manager', 'brighty-core' ),
		'description' => 'You can assign different account manager for each user in admin dashboard (Go to users->find user->Edit->scroll down see a field called account manager. If a user doesnt have an assigned manager, following details will be shown',
		'section'     => 'my-profile',
		'default'     => 'ON',
		'choices'     => [
			'ON'  => true,
			'OFF' => false,
		]
	]
);


new \Kirki\Field\Text(
	[
		'settings' => 'default_account_manager_name',
		'label'    => esc_html__( 'Default Account Manager Name', 'brighty-core' ),
		'section'  => 'my-profile',
		'priority' => 10,
		'description' =>'Leave blank for none',
		'active_callback' => [
			[
				'setting'  => 'enable_account_manager',
				'operator' => '==',
				'value'    => true,
			]
		]
		
	]
);



new \Kirki\Field\Text(
	[
		'settings' => 'default_account_manager_position',
		'label'    => esc_html__( 'Default Account Manager Position', 'brighty-core' ),
		'section'  => 'my-profile',
		'priority' => 10,
		'description' =>'Leave blank for none',
		'active_callback' => [
			[
				'setting'  => 'enable_account_manager',
				'operator' => '==',
				'value'    => true,
			]
		]
		
	]
);


new \Kirki\Field\Text(
	[
		'settings' => 'default_account_manager_phone',
		'label'    => esc_html__( 'Default Account Manager Phone', 'brighty-core' ),
		'section'  => 'my-profile',
		'priority' => 10,
		'description' =>'Leave blank for none',
		'active_callback' => [
			[
				'setting'  => 'enable_account_manager',
				'operator' => '==',
				'value'    => true,
			]
		]
		
	]
);



new \Kirki\Field\Text(
	[
		'settings' => 'default_account_manager_email',
		'label'    => esc_html__( 'Default Account Manager Email', 'brighty-core' ),
		'section'  => 'my-profile',
		'priority' => 10,
		'description' =>'Leave blank for none',
		'active_callback' => [
			[
				'setting'  => 'enable_account_manager',
				'operator' => '==',
				'value'    => true,
			]
		]
		
	]
);




new \Kirki\Field\Textarea(
	[
		'settings' => 'default_account_manager_description',
		'label'    => esc_html__( 'Default Account Manager About', 'brighty-core' ),
		'section'  => 'my-profile',
		'priority' => 10,
		'description' =>'Leave blank for none',
		'active_callback' => [
			[
				'setting'  => 'enable_account_manager',
				'operator' => '==',
				'value'    => true,
			]
		]
		
	]
);


new \Kirki\Field\Image(
	[
		'settings' => 'default_account_manager_photo',
		'label'    => esc_html__( 'Account Manager Photo', 'brighty-core' ),
		'section'  => 'my-profile',
		'priority' => 10,
		'default' => BRIGHTY_CORE_PLUGIN_DIR.'/public/assets/img/signin-bg.svg',
		'active_callback' => [
			[
				'setting'  => 'enable_account_manager',
				'operator' => '==',
				'value'    => true,
			]
		]
		
	]
);



new \Kirki\Field\Image(
	[
		'settings' => 'default_user_cover_picture',
		'label'    => esc_html__( 'User Cover Picture', 'brighty-core' ),
		'section'  => 'my-profile',
		'priority' => 10,
		'default' => BRIGHTY_CORE_PLUGIN_DIR.'/public/assets/img/signin-bg.svg',
		
		
	]
);




new \Kirki\Field\Checkbox_Switch(
	[
		'settings'    => 'allow_avatar_upload',
		'label'       => esc_html__( 'Allow Profile Picture Upload', 'brighty-core' ),
		'section'     => 'my-profile',
		'default'     => 'ON',
		'choices'     => [
			'ON'  => true,
			'OFF' => false,
		]
	]
);



new \Kirki\Field\Repeater(
	[
		'settings'     => 'profile_fields',
		'label'        => esc_html__( 'Extra Fields on Profile Page', 'brighty-core' ),
		'section'      => 'my-profile',
		'priority'     => 10,
		'row_label'    => [
			'type'  => 'field',
			'value' => esc_html__( '', 'brighty-core' ),
			'field' => 'field-name',
		],
		'button_label' => esc_html__( 'Add Extra Profile Fields', 'brighty-core' ),
		'default'      => [
			[
				'field-name'   => esc_html__( 'GST No.', 'brighty-core' ),
				'field-id'    => 'GSTID',
				'showin-invoice'    => 'OFF',
               
			]
		],
		'fields'       => [
			'field-name'   => [
				'type'        => 'text',
				'label'       => esc_html__( 'Field Name', 'brighty-core' ),
				'default'     => '',
			],
			'field-id'    => [
				'type'        => 'textarea',
				'label'       => esc_html__( 'Field ID', 'brighty-core' )
            ],
            
			'showin-invoice'    => [
				'type'        => 'radio',
				'label'       => esc_html__( 'Show in Invoice?', 'brighty-core' ),
				'default'     => '',
                'choices'     => [
					'NO' => esc_html__( 'NO', 'brighty-core' ),
					'YES'  => esc_html__( 'YES', 'brighty-core' ),
				],
			]
		],
	]
);