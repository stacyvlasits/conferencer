<fieldset id="conference_options">
	<table>
		<?php foreach($this->options as $name => $option) { ?>
			<?php
				$name = "conferencer_$name";
				$value = isset($$name) ? $$name : get_post_meta($post->ID, $name, true);
			?>
			
			<tr>
				<td class="label">
					<label for="<?php echo $name; ?>">
						<?php echo $option['label']; ?>
					</label>
				</td>
				
				<td class="input">
					<?php if ($option['type'] == 'text') { ?>
						<input
							class="text"
							type="text"
							name="<?php echo $name; ?>"
							id="<?php echo $name; ?>"
							value="<?php echo htmlentities($value); ?>"
						/>
					<?php } else if ($option['type'] == 'int') { ?>
						<input
							class="int"
							type="text"
							name="<?php echo $name; ?>"
							id="<?php echo $name; ?>"
							value="<?php echo htmlentities($value); ?>"
						/>
					<?php } else if ($option['type'] == 'date-time') { ?>
						<?php // TODO: put in a good JS picker, and use for time slots ?>
						<input
							class="date"
							type="text"
							name="<?php echo $name; ?>"
							id="<?php echo $name; ?>"
							value="<?php echo htmlentities($value); ?>"
						/>
					<?php } else if ($option['type'] == 'money') { ?>
						<input
							class="money"
							type="text"
							name="<?php echo $name; ?>"
							id="<?php echo $name; ?>"
							value="<?php echo htmlentities($value); ?>"
						/>
					<?php } else if ($option['type'] == 'select') { ?>
						<select
							name="<?php echo $name; ?>"
							id="<?php echo $name; ?>"
						>
							<option value=""></option>
							<?php foreach ($option['options'] as $optionValue => $text) { ?>
								<option
									value="<?php echo $optionValue; ?>"
									<?php if ($optionValue == $value) echo 'selected'; ?>>
									<?php echo $text; ?>
								</option>
							<?php } ?>
						</select>
					<?php } else if ($option['type'] == 'multi-select') { ?>
						<?php
							$multivalues = unserialize($value);
							if (!$multivalues || !is_array($multivalues)) $multivalues = array(null);
						?>
						<ul>
							<?php foreach ($multivalues as $multivalue) {?>
								<li>
									<select name="<?php echo $name; ?>[]">
										<option value=""></option>
										<?php foreach ($option['options'] as $optionValue => $text) { ?>
											<option
												value="<?php echo $optionValue; ?>"
												<?php if ($optionValue == $multivalue) echo 'selected'; ?>
											>
												<?php echo $text; ?>
											</option>
										<?php } ?>
									</select>
								</li>
							<?php } ?>
						</ul>
						<a class="add-another" href="#">add another</a>
					<?php } else if ($option['type'] == 'boolean') { ?>
						<input
							type="checkbox"
							name="<?php echo $name; ?>"
							id="<?php echo $name; ?>"
							<?php if (get_post_meta($post->ID, $name, true)) echo 'checked'; ?>
						/>
					<?php } else echo 'unknown option type'; ?>
				</td>
			</tr>
		<?php } ?>
	</table>
</fieldset>