<?php
/*
Plugin Name: ACF PHP Registered Fields
Description: Advanced Custom Fields registered with PHP
Version: 0.1
Author: Wells Peterson
License: GPL
*/

class ACF_PHP_Registered_Fields {
	
	function __construct() {
		add_action('admin_head', array($this, 'php_fields_page_style'));
		add_action('admin_menu', array($this, 'admin_menu'));
	}
	
	function admin_menu() {
		add_submenu_page('edit.php?post_type=acf', 'PHP Registered Fields', 'Custom Fields (PHP)', 'manage_options', 'acf-php-fields', array($this, 'php_fields_page') );
	}
	
	function php_fields_page_style() {
	
	?>
<style type="text/css">
	.wp-box { margin-bottom:20px; }
	.wp-box .title h3.group-title { line-height:20px; }
	.wp-box .widefat.fields, .wp-box .widefat.location-rule {
		border-bottom:0 none;
		border-left:0 none;
		border-right:0 none;
		border-radius:0px;
	}
	.wp-box .widefat.fields th {
		padding: 6px 6px 8px 8px;
		border-bottom-color:#ddd;
	}
	.wp-box td table.widefat { border-top:0 none; }
	.wp-box td table.widefat.repeater-subfields {
		border-top:1px solid #dfdfdf;
	}
	.wp-box table.acf_input tbody tr td { padding:8px; }
	.wp-box table.acf_input tbody tr td.label { width: auto; max-width:180px; }
	.wp-box table.acf_input .fields td.label { width:auto; max-width:160px; }
	
	table.acf_input .fields table td {
		max-width:200px;
	}
	.wp-box table.acf_input tbody tr td.table {
		border-top-color:#eaeaea;
		padding:0;
	}
	.wp-box table.acf_input tbody table.fields td {
		padding:8px 8px;
		border-top-color:#eee;
	}
	.wp-box table.acf_input tbody table.fields td.repeater.table { max-width:760px; }
	.wp-box table.acf_input tbody table.fields tr:first-child td { border-top-color:transparent; }
	.wp-box .widefat tr.after-table td { border-top-color:#e9e9e9; }
	.group-id { padding:7px 10px 0 0; }
	table.widefat table th.check-column { padding:8px 0 6px; }
	.wrap h2 {
		margin-bottom:25px;
		padding-top:9px;
	}
	table.fields tr:hover,
	table.acf_input .fields tr:hover td.label { background:#fff; color:#2f2f2f; }
	table.acf_input .fields tr:hover table td { background:#f5f5f5; color:#3f3f3f; }
	table.acf_input .fields tr:hover td code { color:#2f2f2f; text-shadow:0 1px 0 #eee; }
	table.acf_input .fields tr:hover table td.label { background:#f2f2f2; text-shadow:0 1px 0 #fff; }
	.third {
		float:left;
		display:block;
		clear:none;
		width:33%;
	}
	
</style>
<?php

	}
	
	function php_fields_page() {
		
		wp_enqueue_style('acf-global');
		$groups = $GLOBALS['acf_register_field_group'];
		?>
		
<div class="wrap">
<div id="icon-acf" class="icon32">
	<br>
</div>	
<h2>ACF PHP-Registered Fields</h2>

<?php 
	foreach($groups as $group) : 
		$options = $group['options'];
		$location = $group['location'][0];
		$id = $group['id'];
		$title = $group['title'];
		$menu_order = $group['menu_order'];
		$fields = $group['fields'];
		unset($group['options']);
		unset($group['location']);
		unset($group['id']);
		unset($group['title']);
		unset($group['menu_order']);
		unset($group['fields']);
		$types = array();
		foreach($fields as $feeld){
			$types[] = $feeld['type'];	
		}
		if ( in_array('repeater', $types) ){ 
			$has_repeater = true;
		}
?>

<div class="wp-box">
	<div class="title">
		<small class="alignright group-id"><?php echo $id; ?></small>
		<h3 class="group-title"><?php echo $title; ?></h3>
	</div>
	<table class="acf_input widefat">
		
		<tr>
			<td class="label"><label><?php _e('Options'); ?></label></td>
			<td>
				<div class="third"><?php echo '<b>Position:</b> <code>' . $options['position'] . '</code>'; ?></div>
				<div class="third"><?php echo '<b>Layout:</b> <code>' . $options['layout'] . '</code>'; ?></div>
				<div class="third"><?php echo '<b>Hide on screen:</b> <code>'; implode(', ', $options['hide_on_screen']); echo '</code>'; ?></div>
			</td>
		</tr>
		
		<tr>
			<td class="label"><label><?php _e('Location'); ?></label></td>
			<td class="table">
				<table class="location-rule widefat">
						<thead>
							<tr>
								<th>Param</th>
								<th>Operator</th>
								<th>Value</th>
								<th>Order no</th>
								<th>Group no</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($location as $rule) : ?>
							<tr>
								<td><code><?php echo $rule['param']; ?></code></td>
								<td><code><?php echo $rule['operator']; ?></code></td>
								<td><code><?php echo $rule['value']; ?></code></td>
								<td><code><?php echo $rule['order_no']; ?></code></td>
								<td><code><?php echo $rule['group_no']; ?></code></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</td>
		</tr>
		
		<tr class="after-table">
			<td class="label"><label><?php _e('Menu Order'); ?></label></td>
			<td><?php echo $menu_order; ?></td>
		</tr>
		
		<tr>
			<td class="label"><label><?php _e('Fields'); ?></label></td>
			<td class="table">		
				<table id="acf-php-fields_<?php echo $id ?>" class="widefat fields">
					<thead>
						<tr>
							<th>Label / Instructions</th>
							<th>Name</th>
							<th>Type</th>
							<th>Key</th>
							<?php if ( $has_repeater ) { ?>
								<th>Subfields</th>
							<?php } ?>
							<th>Other</th>
						</tr>
					</thead>
					<tbody class="fields">
						
						<?php foreach($fields as $field) : ?>
						
							<tr class="active">
								<td class="label">
									<b><?php echo $field['label']; ?></b>
									<br>
									<p class="description"><?php echo $field['instructions']; ?></p>
								</td>
								<td>
									<code><?php echo $field['name']; ?></code>
								</td>
								<td>
									<code><?php echo $field['type']; ?></code>
								</td>
								<td>
									<code><?php echo $field['key']; ?></code>
								</td>
								<?php foreach( array('label', 'name', 'instructions', 'key') as $k ) :
									unset($field[$k]);
								endforeach; ?>
								
								<?php if ( $has_repeater ) { ?>
								
									<?php if ( 'repeater' === $field['type'] ) { ?>
											
										<td class="repeater table">	
											<table class="widefat repeater-subfields">
												<thead>
													<tr>
														<th>Label / Instructions</th>
														<th>Name</th>
														<th>Type</th>
														<th>Key</th>
														<th>Other</th>
													</tr>
												</thead>
												<tbody class="subfields">
									
													<?php foreach($field['sub_fields'] as $subfield) : ?>									
														<tr>
															<td class="label">
																<b><?php echo $subfield['label']; ?></b>
																<br>
																<p class="description"><?php echo $subfield['instructions']; ?></p>
															</td>
															<td>
																<code><?php echo $subfield['name']; ?></code>
															</td>
															<td>
																<code><?php echo $subfield['type']; ?></code>
															</td>
															<td>
																<code><?php echo $subfield['key']; ?></code>
															</td>
															
															<?php foreach( array('label', 'name', 'instructions', 'key') as $k ) :
																unset($subfield[$k]);
															endforeach; ?>
															
															<?php 
																unset($field['sub_fields']);
																unset($field['type']);
															?>
															
															<td>
															<?php if ( ! empty($subfield) ) {
																foreach($subfield as $k => $v) :
																	if ( is_array($v) ) {
																		$v = implode('</code>, <code>', $v);
																	}
																	if ( ! empty($v) ) {
																		echo '' . $k . ': <code>' . $v . '</code><br>';
																	}
																endforeach;	
															} ?>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</td>
									
									<?php } ?>
								
								<?php }
								else {
									unset($field['type']);	
								} ?>
								<td>
									<?php
									if ( ! empty($field) ) {
										foreach($field as $k => $v) :
											if ( is_array($v) ) {
												$v = implode('</code>, <code>', $v);
											}
											if ( ! empty($v) ) {
												echo '' . $k . ': <code>' . $v . '</code><br>';
											}
										endforeach;	
									}
									?>
								</td>
							
							</tr>
							
						<?php endforeach; //fields ?>
						
					</tbody>
				</table>
			</td>
		</tr>
		
		<?php if ( ! empty($group) ) { ?>
			<tr class="after-table">
				<td class="label"><label><?php _e('Extra'); ?></label></td>
				<td>
				<?php 
					foreach($group as $k => $v) :
						if ( is_array($v) ) {
							$v = implode('</code>, <code>', $v);
						}
						if ( ! empty($v) ) {
							echo '<b>' . $k . ':</b> <code>' . $v . '</code><br>';
						}
					endforeach;
				?>
				</td>
			</tr>
		<?php } // end if ! empty ?>
	</table>
	
</div><!-- wp-box -->

<?php endforeach; // groups as group ?>
	
</div><!-- wrap -->

<?php
	
	}	


}

new ACF_PHP_Registered_Fields;

?>