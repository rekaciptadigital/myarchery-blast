<?php
$db = db_connect();
$db->query('REPAIR TABLE `'.TB_WHATSAPP_SCHEDULES.'`;');
$db->query('REPAIR TABLE `'.TB_WHATSAPP_CONTACTS.'`;');

$module_paths = get_module_paths();
if(!empty($module_paths))
{

	$whatsapp_modules = [
		"Whatsapp_profile" => [
			"path" => "inc/core/Whatsapp_profile",
			"config" => [
		        'tab' => 2,
		        'type' => 'top',
		        'position' => 1090,
		        'name' => 'WA Profiles'
		    ]
		],
		"Whatsapp_autoresponder" => [
			"path" => "inc/core/Whatsapp_autoresponder",
			"config" => [
		        'tab' => 2,
		        'type' => 'top',
		        'position' => 1080,
		        'name' => 'WA Autoresponder'
		    ]
		],
		"Whatsapp_chatbot" => [
			"path" => "inc/core/Whatsapp_chatbot",
			"config" => [
		        'tab' => 2,
		        'type' => 'top',
		        'position' => 1070,
		        'name' => 'WA Chatbot'
		    ]
		],
		"Whatsapp_bulk" => [
			"path" => "inc/core/Whatsapp_bulk",
			"config" => [
		        'tab' => 2,
		        'type' => 'top',
		        'position' => 1060,
		        'name' => 'WA Bulk messaging'
		    ]
		],
		"Whatsapp_api" => [
			"path" => "inc/core/Whatsapp_api",
			"config" => [
		        'tab' => 2,
		        'type' => 'top',
		        'position' => 1050,
		        'name' => 'WA Rest api'
		    ]
		],
		"Whatsapp_export_participants" => [
			"path" => "inc/core/Whatsapp_export_participants",
			"config" => [
		        'tab' => 2,
		        'type' => 'top',
		        'position' => 1040,
		        'name' => 'WA Export participants'
		    ]
		],
		"Whatsapp_list_message_template" => [
			"path" => "inc/core/Whatsapp_list_message_template",
			"config" => [
		        'tab' => 2,
		        'type' => 'top',
		        'position' => 1030,
		        'name' => 'WA List message template'
		    ]
		],
		"Whatsapp_button_template" => [
			"path" => "inc/core/Whatsapp_button_template",
			"config" => [
		        'tab' => 2,
		        'type' => 'top',
		        'position' => 1020,
		        'name' => 'WA Button template'
		    ]
		],
		"Whatsapp_contact" => [
			"path" => "inc/core/Whatsapp_contact",
			"config" => [
		        'tab' => 2,
		        'type' => 'top',
		        'position' => 1010,
		        'name' => 'WA Contact'
		    ]
		]
	];

    foreach ($module_paths as $module_path) 
    {
    	foreach($whatsapp_modules as $whatsapp_module) {

    		$config_file = $module_path."/Config.php";

        	if (file_exists($config_file)) {

        		$config = include $config_file;

		        if(is_array($config) && isset($config['id']) && $config['id'] == "whatsapp"){

		        	if(get_option('wa_menu_type', 0)){
		        		$config['name'] = "Whatsapp";
		        		unset($config['menu']);
		        		file_put_contents($config_file, '<?php return ' . var_export($config, true) . ';');
		        	}else{
		        		$config['name'] = "Report";
		        		$config['menu'] = [
					        'tab' => 2,
					        'type' => 'top',
					        'position' => 1000,
					        'name' => 'Whatsapp'
					    ];
					    file_put_contents($config_file, '<?php return ' . var_export($config, true) . ';');
		        	}
		        }

		        $res = strpos($module_path, $whatsapp_module['path']);
		        
	        	if ($res !== false){
	        	
	        		if(is_array($config) && !isset($config['menu'])){
	        			if(get_option('wa_menu_type', 0)){
		        			if ( strpos($module_path, "inc/core/Whatsapp_profiles") === false ) {
			        			$config['menu'] = $whatsapp_module['config'];
			        			$config['show_plan'] = false;
			        			file_put_contents($config_file, '<?php return ' . var_export($config, true) . ';');
		        			}
		        		}
	        		}else{
	        			if(!get_option('wa_menu_type', 0)){
		        			unset($config['menu']);
		        			$config['show_plan'] = false;
		        			file_put_contents($config_file, '<?php return ' . var_export($config, true) . ';');
		        		}
	        		}
	        	}
	        }

        }
    }
}


