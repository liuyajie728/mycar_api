<div id=content>
<?php
	if(isset($error)){echo $error;}
	$attributes = array('class' => 'form-user-create form-horizontal', 'role' => 'form');
	echo form_open_multipart(base_url('user/create'), $attributes);
?>
		<fieldset>
			<div class="form-group">
				<label class="col-sm-2 control-label" for=nickname>昵称</label>
				<div class="col-sm-10">
					<input class=form-control name=nickname type=text value="<?php echo set_value('nickname'); ?>" placeholder="昵称" required>
					<?php echo form_error('nickname'); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for=userfile>头像</label>
				<div class="col-sm-10">
					<input class=form-control name=userfile type=file value="<?php echo set_value('userfile'); ?>" placeholder="头像">
					<?php echo form_error('userfile'); ?>
				</div>
			</div>
		</fieldset>
		<button class="btn btn-primary">保存</button>
	</form>
</div>