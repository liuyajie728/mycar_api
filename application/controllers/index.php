<?php
	if (!defined('BASEPATH')) exit('此文件不可被直接访问');
	
	/**
	* Index Class
	*
	* @author Kamas 'Iceberg' Lau <kamaslau@outlook.com>
	* @copyright SenseStrong <www.sensestrong.com>
	*/
	class Index extends CI_Controller
	{
		protected $appid = 'wx920a184018cc7654'; // appid，微信公众平台提供，静态
		protected $appsecret = '1ce56c3e71ff076f6f78ce5e845449e6'; // appsecret，微信公众平台提供，静态，可通过微信公众平台后台重置更换
		protected $token = 'lyj'; // token，通过微信公众平台后台设置

		public $access_token; // access_token，通过相应API从微信公众平台获取

		public $message_input; // 接收到的消息
		public $message_output; // 发送出去的消息
		public $input_type = 'text'; //接收到的消息类型，默认为文本消息
		public $output_type = 'news'; //发送出的消息类型，默认为图文消息
		public $user_name; // 用户ID
		public $account_name; // 微信公众号ID

		public $api_urls = array( // 微信公众平台API网址
			'access_token_get' => 'https://api.weixin.qq.com/cgi_bin/token?grant_type=client_credential&appid=%s&secret=%s', // 获取access_token
			'menu_create' => 'https://api.weixin.qq.com/cgi_bin/menu/create?access_token=%s', // 创建自定义菜单
			'menu_get' => 'https://api.weixin.qq.com/cgi_bin/menu/get?access_token=%s', // 获取自定义菜单
			'menu_delete' => 'https://api.weixin.qq.com/cgi_bin/menu/delete?access_token=%s' // 删除自定义菜单
		);

		public $output_templates = array( // 发送消息模板
			'header' => '<?xml version="1.0" encoding="UTF-8"?>
							<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[%s]]></MsgType>', // 通用模板头部
			'footer' => '<FuncFlag>0</FuncFlag></xml>', // 通用模板尾部
			'text' => '<Content><![CDATA[%s]]></Content>', // 文本消息模板
			'image' => '<Image><MediaId><![CDATA[%s]]></MediaId></Image>', // 图片消息模板
			'voice' => '<Voice><MediaId><![CDATA[%s]]></MediaId></Voice>', // 语音消息模板（必须上传）
			'music' => '<Music>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<MusicUrl><![CDATA[%s]]></MusicUrl>
							<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
							<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
						</Music>', // 音乐模板（可外链）
			'video' => '<Video>
							<MediaId><![CDATA[%s]]></MediaId>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
						</Video>', // 视频消息模板
			'news' => array( // 图文消息模板
				'frame' => '<ArticleCount>%s</ArticleCount><Articles>%s</Articles>', // 图文消息开头
				'item' => '<item>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<PicUrl><![CDATA[%s]]></PicUrl>
							<Url><![CDATA[%s]]></Url>
						</item>' // 图文消息项
			)
		);

		public function __construct()
		{
			parent::__construct();

			// 验证信息来自微信
			if (!$this->checkSignature()):
				echo '仅用于微信公众平台开发!';
				exit;
			endif;
			
			// 验证开发者身份
			if ($this->input->get('echostr')):
				$this->valid();
				exit;
			endif;
			
			// 接收用户发来的各类型信息
			$message_input = file_get_contents('php://input');

			//验证收到的消息不为空
			if (!empty($message_input)):
				$this->message_input = $message_input;
			else:
				echo '接收到的消息为空!';
				exit;
			endif;
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
		
		// 处理消息消息
		public function index()
		{
			//解析信息
			$input = simplexml_load_string($this->message_input, 'SimpleXMLElement', LIBXML_NOCDATA); // 解析收到的用户消息XML对象为PHP对象
			$this->user_name = $input->FromUserName; // 接收到的消息发送方ID
			$this->account_name = $input->ToUserName; // 接收到的消息接收方ID，即公众号ID
			$type = $input->MsgType; // 接收到的消息类型

			//默认回复类型及回复语
			if ($type == 'text'):
				$this->message_input = trim($input->Content);
				$this->output_type = 'text';
				//$content = '呃哦，攻城狮没有教过我这该怎么回答唉[衰]……不如咱换个话题[偷笑]？';
				$content = '接收到的内容为：' . $this->message_input;
				$this->reply($content);
			endif;
			
			//地理位置
			if ($type == 'location'):
				//解析地理位置
				$weidu = $input->Location_X; // 维度
				$jingdu = $input->Location_Y; // 经度
		
				//发送文本消息
				$this->output_type = 'text';
				$content = '您现在的地理位置是' . $weidu . '（纬度）' . $jingdu . '（经度）,';
				//$target_weidu = '36.106683';
				//$target_jingdu = '120.463263';
				$routeurl = 'http://api.map.baidu.com/direction?origin='. $weidu .','. $jingdu .'&amp;destination=天宝国际银座&amp;mode=driving&amp;region=青岛&amp;output=html&amp;src=yourCompanyName|yourAppName&amp;coord_type=gcj02'; // 微信获取到的坐标是高德地图的gcj02纠偏坐标
				$content .= '<a href="'. $routeurl .'">我的车所在地</a>';
				$this->reply($content);
			endif;

			//用户订阅或退订
			if ($type == 'event'):
				$event = $input->Event;
				//用户订阅
				if ($event == 'subscribe'):
					$this->output_type = 'text';
					$content = '成功关注帅男刘亚杰！';
					$this->reply($content);

				//用户退订，无法向用户发送任何信息，只可做内部操作
				elseif ($event == 'unsubscribe'):
					exit;
				endif;
			endif;
		}
		
		//回复消息到用户
		public function reply($content)
		{
			//生成准备回复的消息头部及尾部
			$header = sprintf($this->output_templates['header'], $this->user_name, $this->account_name, time(), $this->output_type);
			$footer = $this->output_templates['footer'];

			// 组装内容模板及内容以生成消息体
			switch($this->output_type)
			{
				case 'news': // 发送图文消息
					$news_items = array();
					//拼接图文消息模板
					foreach($content as $news_item)
					{
						array_push($message_output_items, sprintf($this->output_templates['news']['item'], $news_item['title'], $news_item['description'], $news_item['picurl'], $news_item['url']));
					}
					$content = sprintf($this->output_templates['news']['frame'], count($news_items), $news_items);
					break;
				case 'music': // 发送音乐消息
					$content = sprintf($this->output_templates['music'], $content['title'], $content['description'], $content['music_url'], $content['hq_music_url'], $content['thumb_media_id']);
					break;

				case 'video': // 发送视频消息
					$content = sprintf($this->output_templates['video'], $content['media_id'], $content['title'], $content['description']);
					break;

				default: // 发送文本、图片、语音消息（即当$this->output_type为text/image/voice时）
					$content = sprintf($this->output_templates[$this->output_type], $content);
			}

			// 组装消息头尾部及消息体，并输出；由于微信API限制，只可用echo方式，不可用return
			$this->message_output = $header . $content . $footer;
			echo $this->message_output;
		}
	}

/* End of file index.php */
/* Location: ./application/controllers/index.php */