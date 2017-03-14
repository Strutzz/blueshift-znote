<?php
$cache = new Cache('engine/cache/support');
if ($cache->hasExpired()) {
    // Fetch all staffs in-game.
    $staffs = support_list();
    // Fetch group ids and names from config.php
    $groups = $config['ingame_positions'];
    // Loops through groups, separating each group element into an ID variable and name variable
    foreach ($groups as $group_id => $group_name) {
        // Loops through list of staffs
        if (!empty($staffs))
        foreach ($staffs as $staff) {
            if ($staff['group_id'] == $group_id) $srtGrp[$group_name][] = $staff;
        }
    }
    if (!empty($srtGrp)) {
        $cache->setContent($srtGrp);
        $cache->save();
    }
} else {
    $srtGrp = $cache->load();
}
$writeHeader = true;
?>

<div class="content bg-image overflow-hidden" style="background-image: url('layouts/beastn/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Support in-game</h1>
        <h2 class="h5 text-white-op animated zoomIn"></h2>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-lg-12">

            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">support</h3>
                </div>
                <div class="block-content">
                    <table class="table">
                        <tr class="yellow">
                            <th width="30%">Group</th>
                            <th width="40%">Name</th>
                            <th width="30%">Status</th>
                        </tr>
                        <?php if (!empty($srtGrp)) { foreach (array_reverse($srtGrp) as $grpName => $grpList) { ?>
                            <?php foreach ($grpList as $char) {
                                if ($char['name'] != $config['website_char']) {
                                    echo '<tr>';
                                    echo "<td width='30%'>". $grpName ."</td>";
                                    echo '<td width="40%"><a href="/?subtopic=characterprofile&name='. $char['name'] .'">'. $char['name'] .'</a></td>';
                                    echo "<td width='30%'>". online_id_to_name($char['online']) ."</td>";
                                    echo '</tr>';
                                }
                            }?>
                        <?php } } ?>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>



