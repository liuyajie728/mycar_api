<div id=content>
	<p>您仅需提供常用手机号即可成为哎油会员！</p>
<?php
	if(isset($error)){echo $error;}
	$attributes = array('class' => 'form-sms-send form-horizontal', 'role' => 'form');
	echo form_open(base_url('sms/send'), $attributes);
?>
		<fieldset>
			<div class="form-group">
				<div class="col-sm-10">
					<input class=form-control name=mobile type=text value="<?php echo set_value('mobile'); ?>" placeholder="手机号" required autofocus>
					<a id=sms_send class="btn btn-primary" href="#">验证</a>
					<?php echo form_error('mobile'); ?>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-10">
					<input class=form-control name=captcha type=text value="<?php echo set_value('captcha'); ?>" placeholder="验证码" required disabled>
					<?php echo form_error('captcha'); ?>
				</div>
			</div>
		</fieldset>
		<button class="btn btn-primary" disabled>确定</button>
	</form>
</div>
<script>
	$(function(){
				$('#sms_send').click(function(){
					// 获取mobile字段值，设置sms_send按钮为不可用状态
					var mobile = $('[name=mobile]').val();
					$('#sms_send').text('发送中').attr('disabled');
					
					// 提交待使用短信发送的内容到服务器
					alert(mobile);
					
					// 获取短信发送状态
						// 若失败，
						
						// 若成功，激活并将焦点移到captcha字段
						$('[name=captcha]').removeAttr('disabled').focus();
					return false;
				});
			});
</script>