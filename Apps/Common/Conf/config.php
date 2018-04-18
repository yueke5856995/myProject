<?php
 return array(
		'URL_MODEL' => 0,
	    'URL_CASE_INSENSITIVE'  =>  false,
	    'VAR_PAGE'=>'p',
	    'PAGE_SIZE'=>15,
		'DB_TYPE'=>'mysql',
	    'DB_HOST'=>'localhost',
	    'DB_NAME'=>'pjhl',
	    'DB_USER'=>'root',
	    'DB_PWD'=>'root',
	    'DB_PREFIX'=>'yk_',
	    'DEFAULT_C_LAYER' =>  'Action',
	    'DEFAULT_CITY' => '440100',
	    'DATA_CACHE_SUBDIR'=>true,
        'DATA_PATH_LEVEL'=>2, 
	    'SESSION_PREFIX' => 'WSTMALL',
        'COOKIE_PREFIX'  => 'WSTMALL',
		'LOAD_EXT_CONFIG' => 'wst_config',
        'DEFAULT_MODULE' =>'Admin',

     //'配置项'=>'配置值'
     'APPID'=>'wxc52bd123c5dc4ded',
     'SECRET'=>'5634aae5cb3d3765bf4c93135168822e',
     'MCH_ID'=>'1284278101',//商户号
     'SIGNKEY'=>'01bd38ea76c55c26673b07f9ac904137',//验签密钥，自己在微信后台设置的
     'NOTIFY_URL'=>'https://xiyi.honghuseo.cn/index.php/api/buy/notify',
	);
?>