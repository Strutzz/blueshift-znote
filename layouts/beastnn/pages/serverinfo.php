<div class="content bg-image overflow-hidden" style="background-image: url('layouts/beastn/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Server Information</h1>
        <h2 class="h5 text-white-op animated zoomIn">Here you will find all basic information about Blueshift.</h2>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-lg-12">

            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">server information</h3>
                </div>
                <div class="block-content">
<?php

// Check if PATH is correct
$lua_path = getConfigLua();
if ($lua_path && is_array($lua_path)) {
    if (!file_exists($config['server_path'].'/data/XML/stages.xml')) {
        echo 'Couldn\'t locate stages.xml';
        return;
    }

    $stages_path = simplexml_load_file($config['server_path'].'/data/XML/stages.xml');
    echo '<h2>Server rates</h2>';
    if ($stages_path->config['enabled'] != 0) {
        // Stages are beeing used
        echo "<table class='table table-striped table-hover'>
        <tbody><tr class='yellow'><td>Minium level</td><td>Maximun level</td><td>Multiplier</td></tr>";

        foreach ($stages_path->children()->stage as $stages) {

            if($stages['maxlevel'] === NULL) {
                echo '<tr><td><center>'.$stages['minlevel'].'</center></td><td><center>Infinite</center></td><td><center>x'.$stages['multiplier'].'</center></td></tr>';
            } else {
                echo '<tr><td><center>'.$stages['minlevel'].'</center></td><td><center>'.$stages['maxlevel'].'</center></td><td><center>x'.$stages['multiplier'].'</center></td></tr>';
            }
        }
        echo '</tbody></table>';

    } else {
        // Not using stages
        echo "<table class='table table-striped table-hover'>
        <tbody><tr class='yellow'><td>Experience rate</td></tr>
        <tr><td><center>x".$lua_path['rateExp']."</center></td></tr>
        </tbody></table>";
    }
    echo "<table class='table table-striped table-hover'>
        <tbody><tr class='yellow'><td>Skills rate</td><td>Magic rate</td><td>Loot rate</td></tr>
        <tr><td><center>x".$lua_path['rateSkill']."</center></td><td><center>x".$lua_path['rateMagic']."</center></td><td><center>x".$lua_path['rateLoot']."</center></td></tr>
        </tbody></table>";

    // General info
    $information = array(
        'World type'             => $lua_path['worldType'],
        'Protection level'       => $lua_path['protectionLevel'],
        'Kills to red skull'     => $lua_path['killsToRedSkull'],
        'Kills to black skull'   => $lua_path['killsToBlackSkull'],
        'Remove ammo'            => $lua_path['removeAmmoWhenUsingDistanceWeapon'],
        'Remove runes'           => $lua_path['removeChargesFromRunes'],
        'Time to decrease frags' => $lua_path['timeToDecreaseFrags'],
        'House rent period'      => $lua_path['houseRentPeriod'],
        'AFK Kickout minutes'    => $lua_path['kickIdlePlayerAfterMinutes'],
        'Location'               => $lua_path['location'],
        'Owner name'             => $lua_path['ownerName']
    );

    echo "<h2>Server general information</h2><ul>";
    foreach ($information as $key => $value) {
        echo "<li>".$key." - <b>".$value."</b></li>";
    }

    echo '</ul>';

} else {
    echo '<div class="alert alert-danger">Cannot find the file <strong>config.lua</strong></div>';
} ?>

                </div>
            </div>

        </div>
    </div>
</div>