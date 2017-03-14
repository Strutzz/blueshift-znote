<?php 

if ($config['log_ip']) {
	znote_visitor_insert_detailed_data(3);
}

// Fetch highscore type
$type = (isset($_GET['type'])) ? (int)getValue($_GET['type']) : 7;
if ($type > 9) $type = 7;

// Fetch highscore vocation
$vocation = (isset($_GET['vocation'])) ? (int)getValue($_GET['vocation']) : -1;
if ($vocation > 8) $vocation = -1;

// Fetch highscore page
$page = getValue(@$_GET['page']);
if (!$page || $page == 0) $page = 1;
else $page = (int)$page;

$highscore = $config['highscore'];

$rows = $highscore['rows'];
$rowsPerPage = $highscore['rowsPerPage'];

function skillName($type) {
	$types = array(
		1 => "Club",
		2 => "Sword",
		3 => "Axe",
		4 => "Distance",
		5 => "Shield",
		6 => "Fish",
		7 => "Experience", // Hardcoded
		8 => "Magic Level", // Hardcoded
		9 => "Fist", // Since 0 returns false I will make 9 = 0. :)
	);
	return $types[(int)$type];
}

function pageCheck($index, $page, $rowPerPage) {
    return ($index < ($page * $rowPerPage) && $index >= ($page * $rowPerPage) - $rowPerPage) ? true : false;
}

$cache = new Cache('engine/cache/highscores');
if ($cache->hasExpired()) {
	$scores = fetchAllScores($rows, $config['TFSVersion'], $highscore['ignoreGroupId'], $vocation);
	
	$cache->setContent($scores);
	$cache->save();
} else {
	$scores = $cache->load();
}

 ?>

<div class="content bg-image overflow-hidden" style="background-image: url('layouts/beastn/assets/img/photos/photo3@2x.jpg');">
<div class="push-50-t push-15">
    <h1 class="h2 text-white animated zoomIn">Highscores</h1>
    <h2 class="h5 text-white-op animated zoomIn">View highscores for <?php echo skillName($type) .", ". (($vocation < 0) ? 'any vocation' : vocation_id_to_name($vocation)) ?>.</h2>
</div>
</div>

<div class="content">
    <div class="row">
        <div class="col-lg-12">

            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">highscores</h3>
                </div>
                <div class="block-content">
                    <form action="" method="GET">
                        <input type="hidden" name="subtopic" value="highscores">
                        <div class="form-group">
                            <select name="type" class="form-control">
                                <option value="7" <?php if ($type == 7) echo "selected"; ?>>Experience</option>
                                <option value="8" <?php if ($type == 8) echo "selected"; ?>>Magic</option>
                                <option value="5" <?php if ($type == 5) echo "selected"; ?>>Shield</option>
                                <option value="2" <?php if ($type == 2) echo "selected"; ?>>Sword</option>
                                <option value="1" <?php if ($type == 1) echo "selected"; ?>>Club</option>
                                <option value="3" <?php if ($type == 3) echo "selected"; ?>>Axe</option>
                                <option value="4" <?php if ($type == 4) echo "selected"; ?>>Distance</option>
                                <option value="6" <?php if ($type == 6) echo "selected"; ?>>Fish</option>
                                <option value="9" <?php if ($type == 9) echo "selected"; ?>>Fist</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="vocation" class="form-control">
                                <option value="-1" <?php if ($vocation < 0) echo "selected"; ?>>Any vocation</option>

                                <?php
                                foreach (config('vocations') as $v_id => $v_name) {
                                    $selected = ($vocation == $v_id) ? " selected" : NULL;

                                    echo '<option value="'. $v_id .'"'. $selected .'>'. $v_name .'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                        <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-arrow-right push-5-r"></i> View</button>
                        </div>
                    </form>

                    <?php if ($scores): ?>
                        <table id="highscoresTable" class="table table-striped table-hover">
                            <tr class="yellow">
                                <td>Rank</td>
                                <td>Name</td>
                                <td>Vocation</td>
                                <td>Level</td>
                                <?php if ($type === 7) echo "<td>Points</td>"; ?>
                            </tr>
                            <?php
                            
                            for ($i = 0; $i < count($scores[$type]); $i++) {
                                if (pageCheck($i, $page, $rowsPerPage) && $scores[$type]) {
                                    $profile_data = user_character_data($scores[$type][$i]['id'], 'account_id');

                                    $account_data = user_znote_account_data($profile_data['account_id'], 'flag');
                                    if ($config['country_flags'] === true && count($account_data['flag']) > 1) $flag = '<img src="flags/' . $account_data['flag'] . '.png">  ';
                                    else $flag = '';
                                    ?>
                                    <tr>
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php echo $flag; ?><a href="/?subtopic=characterprofile&name=<?php echo $scores[$type][$i]['name']; ?>"><?php echo $scores[$type][$i]['name']; ?></a></td>
                                        <td><?php echo vocation_id_to_name($scores[$type][$i]['vocation']); ?></td>
                                        <td><?php echo $scores[$type][$i]['value']; ?></td>
                                        <?php if ($type === 7) echo "<td>". $scores[$type][$i]['experience'] ."</td>"; ?>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </table>

<?php
$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (isset($_GET['page'])) {
    $page = $_GET['page'];
    if ($page < 1) {
        redirect('highscores');
    }
} else {
    $page = 1;
}

function pageUrl($url, $page) {
     $query = parse_url($url, PHP_URL_QUERY);

    $url_parts = parse_url($url);

    parse_str($url_parts['query'], $params);

    $params['page'] = $page;

    $url_parts['query'] = http_build_query($params);

    return $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . '?' . $url_parts['query'];
}

if ($page >= 1 && isset($_GET['page'])) {
    $nextPage = pageUrl($url, $page + 1);
    $previousPage = pageUrl($url, $page - 1);
} else {
    $nextPage = $url .= '&page=' . ($page + 1);
    $previousPage = $url .= '&page=' . ($page - 1);
}
?>

<nav aria-label="...">
<ul class="pager">
<li class="previous <?php echo ($page <= 1) ? 'disabled' : '' ; ?>"><a href="<?php echo $previousPage; ?>"><span aria-hidden="true">&larr;</span> Previous</a></li>
<li class="next"><a href="<?php echo $nextPage ?>">Next <span aria-hidden="true">&rarr;</span></a></li>
</ul>
</nav>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>
