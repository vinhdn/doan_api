<div class="breadcrumbs">
	<ul>
		<?php foreach($this->crumbs as $crumb) { ?>
			<li>
				<?php if(isset($crumb['url'])) { ?>
					<a href="<?php echo $crumb['url'] ?>"><?php echo $crumb['name'] ?></a> 
                	<i class="icon-angle-right"></i>
				<?php } else {?>
					<a href="javascript:void(0);"><?php echo $crumb['name'] ?></a> 
                	<i class="icon-angle-right"></i>
				<?php }?>
            </li>
		<?php }?>
	</ul>  
</div>