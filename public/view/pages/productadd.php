				<h3 style="padding-left:150px;"><?php echo __($content['title']); ?></h3>
				
					<?php 
					if(isset($content['addedTxt'])){ 
						echo '<h4 style="color:#0000ff;margin-left: 26px;">'.__($content['addedTxt']).'</h4>'; 
					}
					if(isset($content['errorMsg'])){ 
						echo '<h4 style="color:#ff0000;margin-left: 26px;">'.__($content['errorMsg']).'</h4>'; 
					}?>
				<p>
					<form action="" id="categoryadd" method="post" enctype="multipart/form-data">
						<input type="hidden" id="id" name="id" value="<?php echo $content['post']->__get('id');?>"/>
						<fieldset><legend><h3><?php echo __('Product'); ?></h3></legend>
							<div class="fm-opt">
								<label for="name"><?php echo __('Products'); ?>:</label>
								<select id="id" name="id">
									<?php 
									if(is_array($content['products'])){
										foreach ($content['products'] as $value) {
											echo '<option value="'.$value['id'].'"';
											if($value['id'] == $content['post']->__get('id')){
												echo ' selected ';
											}
											echo '>'.$value['name'].'</option>';
										}
									} ?>
								</select>
							</div>
						</fieldset>
						<input type="submit" value="<?php echo __('Load'); ?>" id="save" style="margin: 20px 120px 20px;"/>
					</form>
				</p>
				<p>
					<form action="" id="categoryadd" method="post" enctype="multipart/form-data">
						<input type="hidden" id="id" name="id" value="<?php echo $content['post']->__get('id');?>"/>
						<fieldset><legend><h3><?php echo __('Product'); ?></h3></legend>
							<div class="fm-opt">
								<label for="name" class="required"><?php echo __('Product name'); ?>:</label>
								<input type="text" id="name" name="name" value="<?php echo $content['post']->__get('name');?>" />
							</div>
							<div class="fm-opt">
								<label for="active"><?php echo __('Active'); ?>:</label>
								<select id="active" name="active">
									<?php echo Functions::selectContent(array(1=>__('Active'),0=>__('Inactive')), $content['post']->__get('active')); ?>
								</select>
							</div>
							<div class="fm-opt">
							<label for="category[]"><?php echo __('Category'); ?>:</label>
								<select multiple name="category[]">
									<?php foreach ($content['category'] as $value) {
										echo '<option value="'.$value['id'].'"';
										if(is_array($content['post']->__get('category'))){
											if(in_array($value['id'], $content['post']->__get('category'))){
												echo ' selected ';
											}
										}
										echo '>'.$value['name'].'</option>';
									}?>
								</select>
							</div>
							<div class="fm-opt">
							<label for="info"><?php echo __('Product info'); ?>:</label>
								<textarea rows="4" cols="50" id="info" name="info"><?php echo $content['post']->__get('info'); ?></textarea>
							</div>
							<div class="fm-opt">
							<label for="image"><?php echo __('Product image'); ?>:</label>
								<input type="file" name="image" id="image"/>
							</div>
						</fieldset>
						<input type="submit" value="<?php echo __('Save'); ?>" id="save" style="margin: 20px 120px 20px;"/>
					</form>
					<div id="image_div" name="image_div" align="center"><img class="imagethb" id="image" name="image" src="../../prodimages/<?php echo $content['post']->__get('image'); ?>" alt="" /></div>
				</p>
				<script>
$(document).ready(function() {
    $('.imagethb').each(function() {
		var maxWidth = 130; // Max width for the image
		var maxHeight = 230;    // Max height for the image
		var ratio = 0;  // Used for aspect ratio
		var width = $(this).width();    // Current image width
		var height = $(this).height();  // Current image height
		// Check if the current width is larger than the max
		if(width > maxWidth){
			ratio = maxWidth / width;   // get ratio for scaling image
			$(this).css("width", maxWidth); // Set new width
			$(this).css("height", height * ratio);  // Scale height based on ratio
			height = height * ratio;    // Reset height to match scaled image
		}
		// Check if current height is larger than max
		if(height > maxHeight){
			ratio = maxHeight / height; // get ratio for scaling image
			$(this).css("height", maxHeight);   // Set new height
			$(this).css("width", width * ratio);    // Scale width based on ratio
			width = width * ratio;    // Reset width to match scaled image
		}
	});
});
</script>