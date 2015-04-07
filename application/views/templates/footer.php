		</div>
		<footer id=footer>
			<p>&copy;<?php echo date('Y'); ?> <a title="<?php echo $title; ?>" href="<?php echo base_url(); ?>"><?php echo $title; ?></a></p>
		</footer>
		<div class=hide>
			<img alt="大众点评团cookie-stuffing" src="http://c.duomai.com/track.php?site_id=62176&aid=299&euid=&t=http%3A%2F%2Ft.dianping.com%2F">
			<img alt="当当cookie-stuffing" src="http://c.duomai.com/track.php?site_id=62176&aid=64&euid=&t=http%3A%2F%2Fwww.dangdang.com%2F">
			<img alt="一号店cookie-stuffing" src="http://c.duomai.com/track.php?site_id=62176&aid=58&euid=&t=http%3A%2F%2Fwww.yhd.com%2F">
			<img alt="糯米cookie-stuffing" src="http://c.duomai.com/track.php?site_id=62176&aid=399&euid=&t=http%3A%2F%2Fwww.nuomi.com%2F">
			<img alt="携程cookie-stuffing" src="http://c.duomai.com/track.php?site_id=62176&aid=83&euid=&t=http%3A%2F%2Fwww.ctrip.com%2F">
			<img alt="拉手cookie-stuffing" src="http://c.duomai.com/track.php?site_id=62176&aid=195&euid=&t=http%3A%2F%2Fwww.lashou.com%2F">
			<img alt="美团cookie-stuffing" src="http://c.duomai.com/track.php?site_id=62176&aid=349&euid=&t=http%3A%2F%2Fm.meituan.com%2F">
		</div>
		<script>
			//隐藏微信底部导航栏
			document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
				WeixinJSBridge.call('hideToolbar');
			});
			
			$(function(){
				//表格可排序
				$('.sortable').tablesorter();
			});
		</script>
	</body>
<!-- 内容结束 -->
</html>