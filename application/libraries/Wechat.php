<?php
	if(!defined('BASEPATH')) exit('此文件不可被直接访问');

	/**
	* 微信类 // 未完成
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class Wechat {
		protected $appid; // appid，微信公众平台提供，静态
		protected $appsecret; // appsecret，微信公众平台提供，静态，可通过微信公众平台后台重置更换
		protected $token; // token，通过微信公众平台后台设置
		
		public $access_token; // access_token，通过相应API从微信公众平台获取

		public $message_input; // 接收到的消息
		public $message_output; // 发送出去的消息
		public $input_type = 'text'; //接收到的消息类型，默认为文本消息
		public $output_type = 'news'; //发送出的消息类型，默认为图文消息
		public $user_name; // 用户ID
		public $account_name; // 微信公众号ID

		public $api_urls = array( // 微信公众平台API网址
			'access_token_get' => 'https://api.weixin.qq.com/cgi_bin/token?grant_type=client_credential&appid=' . $appid . '&secret=' . $appsecret, // 获取access_token
			'menu_create' => 'https://api.weixin.qq.com/cgi_bin/menu/create?access_token=' . $access_token, // 创建自定义菜单
			'menu_get' => 'https://api.weixin.qq.com/cgi_bin/menu/get?access_token=' . $access_token, // 获取自定义菜单
			'menu_delete' => 'https://api.weixin.qq.com/cgi_bin/menu/delete?access_token=' . $access_token // 删除自定义菜单
			/*
			'qr_static_create' => '', // 创建永久二维码
			'qr_temporary_create' => '', // 创建临时二维码
			'' => '',
			'' => '',
			'' => ''
			*/
		);
		public $output_templates = array( // 发送消息模板
			'header' = '<?xml version="1.0" encoding="UTF-8"?>
							<xml>
								<ToUserName><![CDATA[' . $this->user_name . ']]></ToUserName>
								<FromUserName><![CDATA[' . $this->account_name . ']]></FromUserName>
								<CreateTime>' . $time . '</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>', // 通用模板头部
			'footer' = '<FuncFlag>0</FuncFlag></xml>', // 通用模板尾部
			'text' = '<Content><![CDATA[%s]]></Content>', // 文本消息模板
			'image' = '<Image><MediaId><![CDATA[%s]]></MediaId></Image>', // 图片消息模板
			'voice' = '<Voice><MediaId><![CDATA[%s]]></MediaId></Voice>', // 语音消息模板（必须上传）
			'music' = '<Music>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<MusicUrl><![CDATA[%s]]></MusicUrl>
							<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
							<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
						</Music>', // 音乐模板（可外链）
			'video' = '<Video>
							<MediaId><![CDATA[%s]]></MediaId>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
						</Video>', // 视频消息模板
			'news' = array( // 图文消息模板
				'frame' = '<ArticleCount>%s</ArticleCount><Articles>%s</Articles>', // 图文消息开头
				'item' = '<item>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
						</item>' // 图文消息项
			)
		);

		public function __construct($appid, $appsecret, $token)
		{
			// 配置微信公众平台API所需的基本参数
			$this->appid = $appid;
			$this->appsecret = $appsecret;
			$this->token = $token;

			// 验证信息来自微信
			if (!$this->checkSignature()):
				echo '仅用于微信公众平台开发';
				exit;
			endif;

			// 验证开发者身份
			if ($this->input->get('echostr'):
				$this->valid();
				exit;
			endif;

			// 接收用户发来的各类型信息
			$message_input = file_get_contents('php://input');

			//验证收到的消息不为空
			if (!empty($message_input)):
				$this->message_input = trim($message_input);
			else:
				echo '接收到的消息为空';
				exit;
			endif;
		}

		// 解析并处理消息
		private function index()
		{
			//解析信息
			$input = simplexml_load_string($this->message_input, 'SimpleXMLElement', LIBXML_NOCDATA); // 解析收到的用户消息XML对象为PHP对象
			$this->user_name = $input->FromUserName;
			$this->account_name = $input->ToUserName;
			$this->input_type = $input->MsgType; // 接收到的消息类型

			//处理收到的消息

			// 生成需发送出的消息
			$this->output_type = 'text'; // 发送出的消息类型
			$time = time();

			// 组装消息模板及内容
			switch($this->output_type)
			{
				case 'news': // 发送图文消息
					//拼接图文消息模板
					foreach($news_items => $news_item)
					{
						array_push($message_output_items, sprintf($this->output_templates['news']['item'], $news_item['title'], $news_item['description'], $news_item['picurl'], $news_item['url']));
					}
					$this->message_output = sprintf($this->output_templates['header'] . $this->output_templates['news']['frame'] . $this->output_templates['footer'], 'news', count($message_output_items), $message_output_items);
					break;

				case 'music': // 发送音乐消息
					$this->message_output = sprintf($this->output_templates['header'] . $this->output_templates['music'] . $this->output_templates['footer'], 'music', $title, $description, $music_url, $hq_music_url, $thumb_media_id);
					break;

				case 'video': // 发送视频消息
					$this->message_output = sprintf($this->output_templates['header'] . $this->output_templates['video'] . $this->output_templates['footer'], 'video', $media_id, $title, $description);
					break;

				default: // 发送文本、图片、语音消息（即当$this->output_type为text/image/voice时）
					$this->message_output = sprintf($this->output_templates['header'] . $this->output_templates[$this->output_type] . $this->output_templates['footer'], $this->output_type, $content);
			}

			// 输出消息；由于微信API限制，只可用echo，不可用return
			echo $this->message_output;
		}

		//检查签名
		private function checkSignature()
		{
			$signature = $this->input->get('signature');
			$timestamp = $this->input->get('timestamp');
			$nonce = $this->input->get('nonce');
			$token = $this->token;

			$tmpArr = array($token, $timestamp, $nonce);

			sort($tmpArr, SORT_STRING);
			$tmpStr = implode($tmpArr);
			$tmpStr = sha1($tmpStr);

			if ($tmpStr == $signature):
				return true;
			else:
				return false;
			endif;
		}

		//验证开发者身份
		private function valid()
		{
			$echoStr = $this->input->get('echostr');
			//验证签名，可选项
			if ($this->checkSignature()):
				echo $echoStr;
				exit;
			endif;
		}
	}
	// END Wechat Class

/* End of file Wechat.php */
/* Location: ./application/libraries/wechat.php */