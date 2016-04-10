				<h3 style="padding-left:150px;"><?php echo __('Productlist'); ?></h3>
				<p>
					<form action="" id="categoryadd" method="post">
						<select id="active" name="active" class="active">
							<option value="-1"><?php echo __('All'); ?></option>
							<option value="1"><?php echo __('Active'); ?></option>
							<option value="0"><?php echo __('Inactive'); ?></option>
						 </select>
						<input type="submit" value="<?php echo __('Filter'); ?>" id="login_button"/>
					</form>
				</p>
				<p>
					<?php if(isset($content['table'])) echo $content['table']; ?>
				</p>
