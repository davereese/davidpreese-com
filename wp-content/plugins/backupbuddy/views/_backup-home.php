<?php

pb_backupbuddy::$ui->title( 'Backup Site' . ' ' . pb_backupbuddy::video( '9ZHWGjBr84s', __('Backups page tutorial', 'it-l10n-backupbuddy' ), false ) );

/*
wp_enqueue_style('dashboard');
wp_print_styles('dashboard');
wp_enqueue_script('dashboard');
wp_print_scripts('dashboard');
*/
wp_enqueue_script( 'thickbox' );
wp_print_scripts( 'thickbox' );
wp_print_styles( 'thickbox' );
?>
<script type="text/javascript">
	jQuery(document).ready(function() {
		
		jQuery( '.pb_backupbuddy_backuplaunch' ).click( function() {
			var url = jQuery(this).attr( 'href' );
			url = url + '&after_destination=' + jQuery( '#pb_backupbuddy_backup_remotedestination' ).val();
			url = url + '&delete_after=' + jQuery( '#pb_backupbuddy_backup_deleteafter' ).val();
			window.location.href = url;
			return false;
		});
		
		jQuery( '.pb_backupbuddy_hoveraction_send' ).click( function(e) {
			tb_show( 'BackupBuddy', '<?php echo pb_backupbuddy::ajax_url( 'destination_picker' ); ?>&callback_data=' + jQuery(this).attr('rel') + '&sending=1&action_verb=to%20send%20to&TB_iframe=1&width=640&height=455', null );
			return false;
		});
		
		
		// Click label for after backup remote send.
		jQuery( '#pb_backupbuddy_afterbackupremote' ).click( function(e) {
			var checkbox = jQuery( '#pb_backupbuddy_afterbackupremote_box' );
			checkbox.prop('checked', !checkbox[0].checked);
			
			if ( checkbox[0].checked ) { // Only show if just checked.
				afterbackupremote();
			}
			return false;
		});
		
		
		// Click checkbox for after backup remote send.
		jQuery( '#pb_backupbuddy_afterbackupremote_box' ).click( function(e) {
			var checkbox = jQuery( '#pb_backupbuddy_afterbackupremote_box' );
			if ( checkbox[0].checked ) { // Only show if just checked.
				afterbackupremote();
			}
		});
		
		
		jQuery( '.pb_backupbuddy_hoveraction_hash' ).click( function(e) {
			tb_show( 'BackupBuddy', '<?php echo pb_backupbuddy::ajax_url( 'hash' ); ?>&callback_data=' + jQuery(this).attr('rel') + '&TB_iframe=1&width=640&height=455', null );
			return false;
		});
		
		
		
		jQuery( '.pb_backupbuddy_hoveraction_note' ).click( function(e) {
			
			var existing_note = jQuery(this).parents( 'td' ).find('.pb_backupbuddy_notetext').text();
			if ( existing_note == '' ) {
				existing_note = 'My first backup';
			}
			
			var note_text = prompt( '<?php _e( 'Enter a short descriptive note to apply to this archive for your reference. (175 characters max)', 'it-l10n-backupbuddy' ); ?>', existing_note );
			if ( ( note_text == null ) || ( note_text == '' ) ) {
				// User cancelled.
			} else {
				jQuery( '.pb_backupbuddy_backuplist_loading' ).show();
				jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'set_backup_note' ); ?>', { backup_file: jQuery(this).attr('rel'), note: note_text }, 
					function(data) {
						data = jQuery.trim( data );
						jQuery( '.pb_backupbuddy_backuplist_loading' ).hide();
						if ( data != '1' ) {
							alert( '<?php _e('Error', 'it-l10n-backupbuddy' );?>: ' + data );
						}
						javascript:location.reload(true);
					}
				);
			}
			return false;
		});
		
		
		
	});
	
	function pb_backupbuddy_selectdestination( destination_id, destination_title, callback_data, delete_after ) {
		
		if ( ( callback_data != '' ) && ( callback_data != 'delayed_send' ) ) {
			jQuery.post( '<?php echo pb_backupbuddy::ajax_url( 'remote_send' ); ?>', { destination_id: destination_id, destination_title: destination_title, file: callback_data, trigger: 'manual', delete_after: delete_after }, 
				function(data) {
					data = jQuery.trim( data );
					if ( data.charAt(0) != '1' ) {
						alert( '<?php _e('Error starting remote send', 'it-l10n-backupbuddy' ); ?>:' + "\n\n" + data );
					} else {
						if ( delete_after == true ) {
							var delete_alert = "<?php _e( 'The local backup will be deleted upon successful transfer as selected.', 'it-l10n-backupbuddy' ); ?>";
						} else {
							var delete_alert = '';
						}
						alert( "<?php _e('Your file has been scheduled to be sent now. It should arrive shortly.', 'it-l10n-backupbuddy' ); ?> <?php _e( 'You will be notified by email if any problems are encountered.', 'it-l10n-backupbuddy' ); ?>" + " " + delete_alert + "\n\n" + data.slice(1) );
						/* Try to ping server to nudge cron along since sometimes it doesnt trigger as expected. */
						jQuery.post( '<?php echo admin_url('admin-ajax.php'); ?>',
							function(data) {
							}
						);
					}
				}
			);
		} else if ( callback_data == 'delayed_send' ) { // Specified a destination to send to later.
			/*
			if ( delete_after == true ) {
				var delete_alert_text = "<?php _e( 'The local backup will be deleted upon successful transfer as selected.', 'it-l10n-backupbuddy' ); ?>";
			} else {
				var delete_alert_text = '';
			}
			alert( 'delayed' + delete_alert_text );
			*/
			jQuery( '#pb_backupbuddy_backup_remotedestination' ).val( destination_id );
			jQuery( '#pb_backupbuddy_backup_deleteafter' ).val( delete_after );
			jQuery( '#pb_backupbuddy_backup_remotetitle' ).html( 'Destination: "' + destination_title + '".' );
			jQuery( '#pb_backupbuddy_backup_remotetitle' ).slideDown();
		} else {
			//window.location.href = '<?php echo pb_backupbuddy::page_url(); ?>&custom=remoteclient&destination_id=' + destination_id;
			window.location.href = '<?php
			if ( is_network_admin() ) {
				echo network_admin_url( 'admin.php' );
			} else {
				echo admin_url( 'admin.php' );
			}
			?>?page=pb_backupbuddy_backup&custom=remoteclient&destination_id=' + destination_id;
		}
	}
	
	
	function afterbackupremote() {
		tb_show( 'BackupBuddy', '<?php echo pb_backupbuddy::ajax_url( 'destination_picker' ); ?>&callback_data=delayed_send&sending=1&action_verb=to%20send%20to&TB_iframe=1&width=640&height=455', null );
	}
	
	
</script>

<style> 
	.profile_box {
		background: #ECECEC;
		margin: 0;
		display: block;
		border-radius: 5px;
		padding: 10px 10px 0px 10px;
		margin-bottom: 40px;
		border-radius: 5px;
		border: 1px solid #d6d6d6;
		border-top: 1px solid #ebebeb;
		box-shadow: 0px 3px 0px 0px #aaaaaa;
		box-shadow: 0px 3px 0px 0px #CFCFCF;
		font-size: auto;
		//min-height: 65px;
	}
	.profile_text {
		display: block;
		float: left;
		line-height: 26px;
		//margin-right: 8px;
		margin-left: 10px;
		font-weight: bold;
	}
	.profile_type {
		display: block;
		float: left;
		line-height: 26px;
		margin-right: 10px;
		//width: 68px;
		color: #aaa;
	}
	.profile_divide {
		border-right: 1px solid #ebebeb;
		display: block;
		float: left;
		width: 1px;
		height: 100%;
	}
	.profile_item {
		display: block;
		background: #fff;
		border: 1px solid #e7e7e7;
		border-top: 1px solid #ebebeb;
		border-bottom: 1px solid #c9c9c9;
		border-radius: 4px 0 0 4px;
		padding: 15px;
		margin-bottom: 13px;
		text-decoration: none;
		color: #252525;
		line-height: 2;
		font-size: medium;
		height: 25px;
		
		float: left;
		margin-right: 10px;
	}

	.profile_item:hover {
		color: #da2828;
		cursor: pointer;
	}

	.profile_item_selected {
		border-bottom: 3px solid #da2828;
		margin-bottom: 10px;
	}

	.profile_choose {
		font-size: 20px;
		font-family: "HelveticaNeue-Light","Helvetica Neue Light","Helvetica Neue",sans-serif;
		padding: 5px 0 15px 5px;
		color: #464646;
	}
	.backupbuddyFileTitle {
		color: #0084CB;
		font-size: 1.2em;
	}
</style>


<br>



<div class="profile_box">
	<div class="profile_choose">
		<?php _e( 'Choose a backup profile to run:', 'it-l10n-backupbuddy' ); ?>
	</div>
	<!-- div class="profile_item" style="border-bottom: 4px solid #c04343; height: 23px;">
		<span class="profile_type">Defaults</span>
		<span class="profile_divide"></span>
		<span class="profile_text">Global Default Settings</span>
	</div -->
	
	<?php
	foreach( pb_backupbuddy::$options['profiles'] as $profile_id => $profile ) {
		if ( $profile['type'] == 'defaults' ) { continue; } // Skip showing defaults here...
		?>
		<a class="profile_item" href="<?php echo pb_backupbuddy::page_url(); ?>&backupbuddy_backup=<?php echo $profile_id; ?>" title="Create this <?php echo $profile['type']; ?> backup">
			<span class="profile_type"><?php
				if ( $profile['type'] == 'db' ) {
					_e( 'Database', 'it-l10n-backupbuddy' );
				} elseif ( $profile['type'] == 'full' ) {
					_e( 'Full', 'it-l10n-backupbuddy' );
				} elseif( $profile['type'] == 'files' ) {
					_e( 'Files', 'it-l10n-backupbuddy' );
				} else {
					echo 'unknown(' . htmlentities( $profile['type'] ). ')';
				}
			?></span>
			<span class="profile_divide"></span>
			<span class="profile_text"><?php echo htmlentities( $profile['title'] ); ?></span>
		</a>
		<?php
	}
	?>
	<br style="clear: both;">
	
	<!-- Remote send after successful backup? -->
	<div style="clear: both; padding-left: 4px;">
		<input type="checkbox" name="pb_backupbuddy_afterbackupremote" id="pb_backupbuddy_afterbackupremote_box"> <label id="pb_backupbuddy_afterbackupremote" for="pb_backupbuddy_afterbackupremote">Send to remote destination as part of backup process. <span id="pb_backupbuddy_backup_remotetitle"></span></label>
		
		<input type="hidden" name="remote_destination" id="pb_backupbuddy_backup_remotedestination">
		<input type="hidden" name="delete_after" id="pb_backupbuddy_backup_deleteafter">
		
	</div>
	<br style="clear: both;">
	
</div>


<br style="clear: both;"><br>


<?php
pb_backupbuddy::flush();


/********** START TABS **********/

echo '<br>';
pb_backupbuddy::$ui->start_tabs(
	'backup_locations',
	array(
		array(
			'title'		=>		'Local Backups',
			'slug'		=>		'local',
			'css'		=>		'margin-top: -11px;',
		),
		array(
			'title'		=>		'Recently Made Backups',
			'slug'		=>		'recent_backups',
			'css'		=>		'margin-top: -11px;',
		),
	),
	'width: 100%;'
);






pb_backupbuddy::$ui->start_tab( 'local' );
echo '<br>';
$listing_mode = 'default';
require_once( '_backup_listing.php' );

echo '<br><br>';
echo '<a href="';
if ( is_network_admin() ) {
	echo network_admin_url( 'admin.php' );
} else {
	echo admin_url( 'admin.php' );
}
echo '?page=pb_backupbuddy_destinations" class="button button-primary">View & Manage remote destination files</a>';

pb_backupbuddy::$ui->end_tab();








pb_backupbuddy::$ui->start_tab( 'recent_backups' );

?>
<br>
<h3 style="
		margin: 6px 0 10px 0px;
		font-weight: 200;
		font-size: 20px;
		font-family: " helveticaneue-light","helvetica="" neue="" light","helvetica="" neue",sans-serif;="" color:="" #464646;="" "="">Most recent backups (including scheduled, transferred, or deleted):</h3>
<br>
<?php

$backups_list = glob( pb_backupbuddy::$options['log_directory'] . 'fileoptions/*.txt' );

if ( ! is_array( $backups_list ) ) {
	$backups_list = array();
}

if ( count( $backups_list ) == 0 ) {
	_e( 'No backups have been created recently.', 'it-l10n-backupbuddy' );
} else {
	$log_directory = WP_CONTENT_DIR . '/uploads/pb_' . pb_backupbuddy::settings( 'slug' ) . '/';
	
	// Backup type.
	$pretty_type = array(
		'full'	=>	'Full',
		'db'	=>	'Database',
		'files' =>	'Files',
	);
	
	$recent_backup_count_cap = 5; // Max number of recent backups to list.
	$backups = array();
	foreach( $backups_list as $backup_fileoptions ) {
		
		require_once( pb_backupbuddy::plugin_path() . '/classes/fileoptions.php' );
		$backup = new pb_backupbuddy_fileoptions( $backup_fileoptions, $read_only = true );
		if ( true !== ( $result = $backup->is_ok() ) ) {
			pb_backupbuddy::status( 'error', __('Unable to access fileoptions data file.', 'it-l10n-backupbuddy' ) . ' Error: ' . $result );
			continue;
		}
		$backup = &$backup->options;
		
		if ( !isset( $backup['serial'] ) || ( $backup['serial'] == '' ) ) {
			continue;
		}
		if ( $backup['finish_time'] > $backup['start_time'] ) {
			$status = '<span class="pb_label pb_label-success">Completed</span>';
		} elseif ( $backup['finish_time'] == -1 ) {
			$status = '<span class="pb_label pb_label-warning">Cancelled</span>';
		} else {
			$status = '<span class="pb_label pb_label-warning">In progress or timed out</span>';
		}
		$status .= '<br>';
		
		// Log link (if log file exists still).
		/*
		$serial_file = $log_directory . 'status-' . $backup['serial'] . '_' . pb_backupbuddy::$options['log_serial'] . '.txt';
		if ( file_exists( $serial_file ) ) {
			$status .= '<a title="' . __( 'Backup Process Status Log', 'it-l10n-backupbuddy' ) . '" href="' . pb_backupbuddy::ajax_url( 'view_status_log' ) . '&serial=' . $backup['serial'] . '&#038;TB_iframe=1&#038;width=640&#038;height=600" class="thickbox">Status Log</a> | ';
		}
		*/
		
		// Technical details link.
		$status .= '<div class="row-actions">';
		$status .= '<a title="' . __( 'Backup Process Technical Details', 'it-l10n-backupbuddy' ) . '" href="' . pb_backupbuddy::ajax_url( 'integrity_status' ) . '&serial=' . $backup['serial'] . '&#038;TB_iframe=1&#038;width=640&#038;height=600" class="thickbox">View Details</a>';
		$status .= '</div>';
		
		// Calculate finish time (if finished).
		if ( $backup['finish_time'] > 0 ) {
			$finish_time = pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $backup['finish_time'] ) ) . '<br><span class="description">' . pb_backupbuddy::$format->time_ago( $backup['finish_time'] ) . ' ago</span>';
		} else { // unfinished.
			$finish_time = '<i>Unfinished</i>';
		}
		
		$backupTitle = '<span class="backupbuddyFileTitle" style="color: #000;" title="' . basename( $backup['archive_file'] ) . '">' . pb_backupbuddy::$format->date( pb_backupbuddy::$format->localize_time( $backup['start_time'] ), 'l, F j, Y - g:i:s a' ) . ' (' . pb_backupbuddy::$format->time_ago( $backup['start_time'] ) . ' ago)</span><br><span class="description">' . basename( $backup['archive_file'] ) . '</span>';
		
		if ( isset( $backup['profile'] ) ) {
			$backupType = '<div>
				<span style="color: #AAA; float: left;">' . pb_backupbuddy::$format->prettify( $backup['profile']['type'], $pretty_type ) . '</span>
				<span style="display: inline-block; float: left; height: 15px; border-right: 1px solid #EBEBEB; margin-left: 6px; margin-right: 6px;"></span>'
				. $backup['profile']['title'] .
			'</div>';
		} else {
			$backupType = '<span class="description">Unknown</span>';
		}
		
		if ( isset( $backup['archive_size'] ) && ( $backup['archive_size'] > 0 ) ) {
			$archive_size = pb_backupbuddy::$format->file_size( $backup['archive_size'] );
		} else {
			$archive_size = 'n/a';
		}
		
		// Append to list.
		$backups[ $backup['serial'] ] = array(
			array( basename( $backup['archive_file'] ), $backupTitle ),
			$backupType,
			$archive_size,
			ucfirst( $backup['trigger'] ),
			$status,
			'start_timestamp' => $backup['start_time'], // Used by array sorter later to put backups in proper order.
		);
		
	}

	$columns = array(
		__('Backups (Start Time)', 'it-l10n-backupbuddy' ),
		__('Type | Profile', 'it-l10n-backupbuddy' ),
		__('File Size', 'it-l10n-backupbuddy' ),
		__('Trigger', 'it-l10n-backupbuddy' ),
		__('Status', 'it-l10n-backupbuddy' ),
	);

	function pb_backupbuddy_aasort (&$array, $key) {
		$sorter=array();
		$ret=array();
		reset($array);
		foreach ($array as $ii => $va) {
		    $sorter[$ii]=$va[$key];
		}
		asort($sorter);
		foreach ($sorter as $ii => $va) {
		    $ret[$ii]=$array[$ii];
		}
		$array=$ret;
	}

	pb_backupbuddy_aasort( $backups, 'start_timestamp' ); // Sort by multidimensional array with key start_timestamp.
	$backups = array_reverse( $backups ); // Reverse array order to show newest first.
	
	$backups = array_slice( $backups, 0, $recent_backup_count_cap ); // Only display most recent X number of backups in list.
	
	pb_backupbuddy::$ui->list_table(
		$backups,
		array(
			'action'		=>	pb_backupbuddy::page_url(),
			'columns'		=>	$columns,
			'css'			=>	'width: 100%;',
		)
	);
	
	echo '<div class="alignright actions">';
	pb_backupbuddy::$ui->note( 'Hover over items above for additional options.' );
	echo '</div>';
	
} // end if recent backups exist.


pb_backupbuddy::$ui->end_tab();




pb_backupbuddy::$ui->end_tabs();


/********** END TABS **********/











echo '<br style="clear: both;"><br><br><br>';
?>





<?php
// Handles thickbox auto-resizing. Keep at bottom of page to avoid issues.
if ( !wp_script_is( 'media-upload' ) ) {
	wp_enqueue_script( 'media-upload' );
	wp_print_scripts( 'media-upload' );
}
?>