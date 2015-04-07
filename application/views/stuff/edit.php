<ol class=breadcrumb>
	<li><a href="<?php echo base_url(); ?>">首页</a></li>
	<li><a href="<?php echo base_url('stuff'); ?>">员工</a></li>
	<li class=active>编辑</li>
</ol>
<div id=content>
	<?php 
		foreach ($stuffs as $stuff):
		if(isset($error)){echo $error;}//若有错误提示信息则显示
		$attributes = array('class' => 'form-stuff-edit form-horizontal', 'role' => 'form');
		echo form_open(base_url('stuff/edit/'.$stuff['stuff_id']), $attributes);
	?>
		<fieldset>
			<input name=stuff_id type=hidden value="<?php echo $stuff['stuff_id']; ?>">
			<div class="form-group">
				<label class="col-sm-2 control-label" for=lastname>姓</label>
				<div class="col-sm-10">
					<input class=form-control name=lastname type=text value="<?php echo $stuff['lastname']; ?>" required>
					<?php echo form_error('lastname'); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for=firstname>名</label>
				<div class="col-sm-10">
					<input class=form-control name=firstname type=text value="<?php echo $stuff['firstname']; ?>" required>
					<?php echo form_error('firstname'); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for=level>权限</label>
				<div class="col-sm-10">
					<select class=form-control name=level required>
						<option value="0"<?php echo set_select('level', '0'); ?>>未授权</option>
						<option value="1"<?php echo set_select('level', '1'); ?><?php echo ($stuff['level'] == '1')?' selected':NULL; ?>>员工</option>
						<option value="2"<?php echo set_select('level', '2'); ?><?php echo ($stuff['level'] == '2')?' selected':NULL; ?>>财务</option>
						<option value="3"<?php echo set_select('level', '3'); ?><?php echo ($stuff['level'] == '3')?' selected':NULL; ?>>收银</option>
						<option value="4"<?php echo set_select('level', '4'); ?><?php echo ($stuff['level'] == '4')?' selected':NULL; ?>>经理</option>
						<?php if($this->session->userdata('level') > '5'): ?>
						<option value="5"<?php echo set_select('level', '5'); ?><?php echo ($stuff['level'] == '5')?' selected':NULL; ?>>门店管理员</option>
						<?php endif;?>
						<?php if($this->session->userdata('level') > '6'): ?>
						<option value="6"<?php echo set_select('level', '6'); ?><?php echo ($stuff['level'] == '6')?' selected':NULL; ?>>品牌管理员</option>
						<?php endif;?>
						<?php if($this->session->userdata('level') > '7'): ?>
						<option value="7"<?php echo set_select('level', '7'); ?><?php echo ($stuff['level'] == '7')?' selected':NULL; ?>>公司管理员</option>
						<?php endif;?>
						<?php if($this->session->userdata('level') == '9'): ?>
						<option value="8"<?php echo set_select('level', '8'); ?><?php echo ($stuff['level'] == '8')?' selected':NULL; ?>>系统管理员</option>
						<option value="9"<?php echo set_select('level', '9'); ?><?php echo ($stuff['level'] == '9')?' selected':NULL; ?>>超级管理员</option>
						<?php endif;?>
					</select>
					<?php echo form_error('level'); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for=gender>性别</label>
				<div class="col-sm-10">
					<label class="radio-inline"><input type=radio name="gender" value="-"<?php echo set_radio('gender', '-'); ?> required>请选择</label>
					<label class="radio-inline"><input type=radio name="gender" value="0"<?php echo set_radio('gender', '0'); ?> required<?php echo ($stuff['gender'] == '0')?' checked':NULL; ?>>女士</label>
					<label class="radio-inline"><input type=radio name="gender" value="1"<?php echo set_radio('gender', '1'); ?> required<?php echo ($stuff['gender'] == '1')?' checked':NULL; ?>>先生</label>
					<?php echo form_error('gender'); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for=dob>生日</label>
				<div class="col-sm-10">
					<input class=form-control name=dob type=date placeholder="YYYY-MM-DD" value="<?php echo $stuff['dob']; ?>" required>
					<?php echo form_error('dob'); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for=mobile>手机号</label>
				<div class="col-sm-10">
					<input class=form-control name=mobile type=tel size=11 pattern="\d{11}" placeholder="目前支持中国大陆地区手机号码" value="<?php echo $stuff['mobile']; ?>" required>
					<?php echo form_error('mobile'); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for=email>Email（选填）</label>
				<div class="col-sm-10">
					<input class=form-control name=email type=email value="<?php echo $stuff['email']; ?>">
					<?php echo form_error('email'); ?>
				</div>
			</div>
		</fieldset>
		<button class="btn btn-primary">保存</button>
	</form>
	<?php endforeach; ?>
</div>
<script>
	$(function(){
		$('form').submit(function(){
			$('input[type=date]').datepicker(
				{minDate: "-80Y", maxDate: "-16Y"}
			);
			var gender = $('input[name=gender]').val();
			if(gender == '-')
			{
				alert('请选择性别');
				return false;
			}
		});
	});
</script>