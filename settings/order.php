<?php

new Conferencer_Settings_Order();
class Conferencer_Settings_Order {
	static $priority_post_types = array(
		'track' => "Tracks",
		'room' => "Rooms",
		'speaker' => "Speakers",
		'sponsor' => "Sponsors",
		'sponsor_level' => "Sponsor Levels",
		'company' => "Companies",
	);
	
	function __construct() {
		add_action('admin_init', array(&$this, 'save'));
		add_action('admin_menu', array(&$this, 'admin_menu'));
	}
	
	function admin_menu() {
		add_submenu_page(
			'conferencer',
			"Re-ordering",
			"Re-ordering",
			'edit_posts',
			'conferencer_reordering',
			array(&$this, 'page')
		);
	}
	
	function page() {
		if (!current_user_can('edit_posts')) wp_die("You do not have sufficient permissions to access this page.");
		?>
		
		<div id="conferencer_settings" class="wrap">
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
				<?php wp_nonce_field('nonce_conferencer_settings_order'); ?>

				<h2>Re-order posts</h2>
				<p>Drag rows up and down to re-order posts' priority.  Be sure to save when you are done.</p>

				<div class="post_types">
					<?php foreach (self::$priority_post_types as $slug => $heading) {
						$query = new WP_Query(array(
							'post_type' => $slug,
							'posts_per_page' => -1, // get all
						));

						$posts = $query->posts;
						uasort($posts, array('Conferencer', 'order_sort'));
						?>

						<?php if (count($posts)) { ?>
							<div class="post_type">
								<h4><?php echo $heading; ?></h4>
								<table class="levels" cellspacing="0">
									<tbody class="rows">
										<?php foreach ($posts as $post) { ?>
											<tr class="ui-state-default">
												<td>
													<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
													<input class="post-id" type="hidden" name="conferencer_<?php echo $slug; ?>_id[]" value="<?php echo $post->ID; ?>" />
												</td>

												<td><?php echo $post->post_title; ?></td>

												<td><a href="<?php echo admin_url('post.php?action=edit&post='.$post->ID); ?>">edit</a></td>
											</tr>
										<?php } ?>
									</tbody>
								</table>
							</div> <!-- .post_type -->
						<?php } // if?>
					<?php } // foreach ?>
				</div> <!-- .post_types -->

				<p class="submit"><input type="submit" class="button-primary" name="conferencer_reordering" value="Save Changes" /></p>
			</form>
		</div>
		
		<?php
	}
	
	function save() {
		if (isset($_POST['conferencer_reordering']) && check_admin_referer('nonce_conferencer_settings_order')) {
			foreach (self::$priority_post_types as $slug => $heading) {
				if (isset($_POST['conferencer_'.$slug.'_id'])) {
					foreach ($_POST['conferencer_'.$slug.'_id'] as $order => $id) {
						update_post_meta(intVal($id), 'conferencer_order', $order);
					}
				}
			}

			Conferencer::add_admin_message("Settings Saved");
			header("Location: ".$_SERVER['REQUEST_URI']);
			die;
		}
	}
}