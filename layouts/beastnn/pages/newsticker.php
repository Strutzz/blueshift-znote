<?php require_once 'engine/init.php'; include 'layout/overall/header.php';

if (!user_logged_in() && !is_admin($user_data)) {
	header("Location: index.php");
	exit();
}

$updateCache = false;
if (user_logged_in()) {
	if (is_admin($user_data)) {
		// variables
		$status = true;
		if (isset($_POST['changelogId'])) $changelogId = (int)$_POST['changelogId'];
		else $status = false;
		if (isset($_POST['changelogText'])) $changelogText = getValue($_POST['changelogText']);
		else $status = false;

		if (isset($_POST['action'])) $action = (int)$_POST['action'];
		else $action = 0;
		// POST delete
		if (isset($_POST['delete'])) {
			$delete = isset($_POST['delete']) ? (int)$_POST['delete'] : 0;
			if ($delete && $action == 1) {
				mysql_delete("DELETE FROM `znote_changelog` WHERE `id`='$delete' LIMIT 1;");
				echo "<h2>News ticker deleted.!</h2>";
				$updateCache = true;
			}
		} else {
			if ($status) {
				// POST update
				if ($changelogId > 0) {
					mysql_update("UPDATE `znote_changelog` SET `text`='$changelogText' WHERE `id`='$changelogId' LIMIT 1;");
					echo "<h2>News ticker updated!</h2>";
					$updateCache = true;
				} else {
					// POST create
					$time = time();
					mysql_insert("INSERT INTO `znote_changelog` (`text`, `time`, `report_id`, `status`) VALUES ('$changelogText', '$time', '0', '35');");
					echo "<h2>New ticker posted!</h2>";
					$updateCache = true;
				}
			}
		}
		if ($action === 2) {
			$old = mysql_select_single("SELECT `text` FROM `znote_changelog` WHERE `id`='$changelogId' LIMIT 1;");
		}
		// HTML to create or update
		?>
		<h3>Add/Update News Tickers</h3>
		<form action="" method="POST">
			<input name="changelogId" type="hidden" value="<?php echo ($action === 2) ? $changelogId : 0; ?>">
			<textarea rows="7" cols="40" maxlength="254" class="form-control" name="changelogText"><?php echo ($action === 2) ? $old['text'] : ''; ?></textarea><br/>
			<input type="submit" class="btn btn-success" value="Submit">
		</form>
		<?php
	}
}
?>

<h1>News Tickers</h1>
<?php
$cache = new Cache('engine/cache/changelog');
if ($updateCache === true) {
	$changelogs = mysql_select_multi("SELECT `id`, `text`, `time`, `report_id`, `status` FROM `znote_changelog` ORDER BY `id` DESC;");
	
	$cache->setContent($changelogs);
	$cache->save();
} else {
	$changelogs = $cache->load();
}
if (isset($changelogs) && !empty($changelogs) && $changelogs !== false) {
	?>
	<table class="table table-striped table-bordered table-condensed">
		<tr class="yellow">
			<td>News Tickers</td>
			<?php
			if (user_logged_in())
				if (is_admin($user_data)) {
					echo "<td>Delete</td><td>Update</td>";
				}
			?>
		</tr>
		<?php
		foreach ($changelogs as $changelog) {
		?>
		<tr>
			<td><b><?php echo getClock((isset($changelog['time'])) ? $changelog['time'] : 0, true, true); ?></b><br><?php echo $changelog['text']; ?></td>
			<?php
			if (user_logged_in())
				if (is_admin($user_data)) {
					?>
					<td>
						<form action="" method="POST">
							<input name="delete" type="hidden" value="<?php echo $changelog['id']; ?>">
							<input name="action" type="hidden" value="1">
							<input type="submit" class="btn btn-danger" value="DELETE">	
						</form>
					</td>
					<td>
						<form action="" method="POST">
							<input name="changelogId" type="hidden" value="<?php echo $changelog['id']; ?>">
							<input name="action" type="hidden" value="2">
							<input type="submit" class="btn btn-info" value="UPDATE">	
						</form>
					</td>
					<?php
				}
			?>
		</tr>
		<?php
		}
		?>
	</table>
	<?php
} else {
	?>
	<h2>Currently no newstickers has been posted.</h2>
	<?php
}
include 'layout/overall/footer.php'; ?>