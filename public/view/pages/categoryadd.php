				<h3 style="padding-left:150px;"><?php echo __($content['title']); ?></h3>
				
					<?php 
					if(isset($content['addedTxt'])){ 
						echo '<h4 style="color:#0000ff;margin-left: 26px;">'.__($content['addedTxt']).'</h4>'; 
					}
					if(isset($content['errorMsg'])){ 
						echo '<h4 style="color:#ff0000;margin-left: 26px;">'.__($content['errorMsg']).'</h4>'; 
					}?>
				<p>
					<form action="" id="prodloadd" method="post" enctype="multipart/form-data">
						<input type="hidden" id="id" name="id" value="<?php echo $content['post']->__get('id');?>"/>
						<fieldset><legend><h3><?php echo __('Categories'); ?></h3></legend>
							<div class="fm-opt">
								<label for="name"><?php echo __('Categories'); ?>:</label>
								<select id="id" name="id">
									<?php 
									if(is_array($content['categories'])){
										foreach ($content['categories'] as $value) {
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
					<form action="" id="categoryadd" method="post" >
						<input type="hidden" id="id" name="id" value="<?php echo $content['post']->__get('id');?>"/>
						<fieldset><legend><h3><?php echo __('Categories'); ?></h3></legend>
							<div class="fm-opt">
								<label for="name" class="required"><?php echo __('Category name'); ?>:</label>
								<input type="text" id="name" name="name" value="<?php echo $content['post']->__get('name');?>" style="background-color:#3e3e4a; color:#FFF;"/>
							</div>
							<div class="fm-opt">
								<label for="active"><?php echo __('Active'); ?>:</label>
								<select id="active" name="active" style="background-color:#3e3e4a; color:#FFF;">
									<?php echo Functions::selectContent(array(1=>__('Active'),0=>__('Inactive')), $content['post']->__get('active')); ?>
								 </select>
							</div>
						</fieldset>
						<input type="submit" value="<?php echo __('Save'); ?>" id="save" style="margin: 20px 120px 20px;"/>
					</form>
				</p>
				