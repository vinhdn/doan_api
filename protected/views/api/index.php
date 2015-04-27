<?php
$this->pageTitle=Yii::app()->name . ' - API';
?>

<div id='catelogy'>
<h3>Đăng nhập</h3>
<a href='http://localhost/yiid/api/api/login'>http://localhost/yiid/api/api/login</a>
</br>
@params:
<ul>
	<li>fb_id - long</li>
	<li>email - string</li>
</ul>
</div>
<div id='catelogy'>
<h3>Câu chuyện</h3>
<h5>Câu chuyện mới nhất</h5>
<a href='http://localhost/yiid/api/api/getstory'>http://localhost/yiid/api/api/getstory</a>
</br>
@params:
<ul>
	<li>user_id - long</li>
	<li>fb_id - long</li>
</ul>
</div>
<div id='catelogy'>
	<h3>Chiến dịch</h3>
	<ul>
		<li>
			<h4>Danh sách các chiến dịch</h4>
			<a href='http://localhost/yiid/api/api/getcd'>http://localhost/yiid/api/api/getcd</a>
			</br>
			@params:
			<ul>
				<li>user_id - long</li>
				<li>fb_id - long</li>
			</ul>
		</li>
		<li>
			<h4>Danh sách các chiến dịch đã đăng ký</h4>
			<a href='http://localhost/yiid/api/api/getcddk'>http://localhost/yiid/api/api/getcddk</a>
			</br>
			@params:
			<ul>
				<li>user_id - long</li>
				<li>fb_id - long</li>
			</ul>
		</li>
		<li>
			<h4>Đăng ký chiến dịch</h4>
			<a href='http://localhost/yiid/api/api/dkcd'>http://localhost/yiid/api/api/dkcd</a>
			</br>
			@params:
			<ul>
				<li>user_id - long</li>
				<li>fb_id - long</li>
				<li>id_chiendich - long</li>
			</ul>
		</li>
	</ul>
</div>