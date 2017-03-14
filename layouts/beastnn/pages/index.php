<?php 
	if (!isset($_GET['page'])) {
		$page = 0;
	} else {
		$page = (int)$_GET['page'];
	}

	if ($config['UseChangelogTicker']) {
		//////////////////////
		// Changelog ticker //
		// Load from cache
		$changelogCache = new Cache('engine/cache/changelog');
		$changelogs = $changelogCache->load();

		if (isset($changelogs) && !empty($changelogs) && $changelogs !== false) {
			?>
			<table id="changelogTable">
				<tr class="yellow">
					<td colspan="2">Latest Changelog Updates (<a href="changelog.php">Click here to see full changelog</a>)</td>
				</tr>
				<?php
				for ($i = 0; $i < count($changelogs) && $i < 5; $i++) {
					?>
					<tr>
						<td><?php echo getClock($changelogs[$i]['time'], true, true); ?></td>
						<td><?php echo $changelogs[$i]['text']; ?></td>
					</tr>
					<?php
				}
				?>
			</table>
			<?php
		}
	}
	
	$cache = new Cache('engine/cache/news');
	if ($cache->hasExpired()) {
		$news = fetchAllNews();
		$cache->setContent($news);
		$cache->save();
	} else {
		$news = $cache->load();
	}
	
	// Design and present the list
	if ($news) {
		
		$total_news = count($news);
		$row_news = $total_news / $config['news_per_page'];
		$page_amount = ceil($total_news / $config['news_per_page']);
		$current = $config['news_per_page'] * $page;

		function TransformToBBCode($string) {
			$tags = array(
				'[center]{$1}[/center]' => '<center>$1</center>',
				'[b]{$1}[/b]' => '<b>$1</b>',
				'[size={$1}]{$2}[/size]' => '<font size="$1">$2</font>',
				'[img]{$1}[/img]'    => '<a href="$1" target="_BLANK"><img src="$1" alt="image" style="width: 100%"></a>',
				'[link]{$1}[/link]'    => '<a href="$1">$1</a>',
				'[link={$1}]{$2}[/link]'   => '<a href="$1" target="_BLANK">$2</a>',
				'[color={$1}]{$2}[/color]' => '<font color="$1">$2</font>',
				'[*]{$1}[/*]' => '<li>$1</li>',
				'[youtube]{$1}[/youtube]' => '<div class="youtube"><div class="aspectratio"><iframe src="//www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe></div></div>',
			);
			foreach ($tags as $tag => $value) {
				$code = preg_replace('/placeholder([0-9]+)/', '(.*?)', preg_quote(preg_replace('/\{\$([0-9]+)\}/', 'placeholder$1', $tag), '/'));
				$string = preg_replace('/'.$code.'/i', $value, $string);
			}
			return $string;
		}
		for ($i = $current; $i < $current + $config['news_per_page']; $i++) {
			if (isset($news[$i])) {
				?>
				<table id="news">
					<tr class="yellow">
						<td class="zheadline"><?php echo getClock($news[$i]['date'], true) .' by <a href="characterprofile.php?name='. $news[$i]['name'] .'">'. $news[$i]['name'] .'</a> - <b>'. TransformToBBCode($news[$i]['title']) .'</b>'; ?></td>
					</tr>
					<tr>
						<td>
							<p><?php echo TransformToBBCode(nl2br($news[$i]['text'])); ?></p>
						</td>
					</tr>
				</table>
				<?php
			} 
		}


		echo '<select name="newspage" onchange="location = this.options[this.selectedIndex].value;">';

		for ($i = 0; $i < $page_amount; $i++) {

			if ($i == $page) {

				echo '<option value="index.php?page='.$i.'" selected>Page '.$i.'</option>';

			} else {

				echo '<option value="index.php?page='.$i.'">Page '.$i.'</option>';
			}
		}
		
		echo '</select>';
	}
?>

<div class="content bg-image overflow-hidden" style="background-image: url('layouts/beastn/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Latest News</h1>
        <h2 class="h5 text-white-op animated zoomIn">Here you will find the latest news about Blueshift.<br>Come back often to stay informed about important changes in the game.</h2>
    </div>
</div>


<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <!-- Main Dashboard Chart -->
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Welcome to ZnoteAAC version X.</h3>
                </div>
                <div class="block-content block-content-full bg-gray-lighter">
                   <?php if ($news): ?>

                    <?php else: ?>
                        This is a placeholder, to get rid of it, simply just create a news record.
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>