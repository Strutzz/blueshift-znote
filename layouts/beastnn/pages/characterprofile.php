<?php 
 
if ($config['log_ip']) 
{
	znote_visitor_insert_detailed_data(4);
}

if (isset($_GET['name']) === true && empty($_GET['name']) === false) 
{
	$name = getValue($_GET['name']);
	$user_id = user_character_exist($name);
	
	if ($user_id !== false) 
	{	
		if ($config['TFSVersion'] == 'TFS_10') 
		{
			$profile_data = user_character_data($user_id, 'account_id', 'name', 'level', 'group_id', 'vocation', 'health', 'healthmax', 'experience', 'mana', 'manamax', 'sex', 'lastlogin');
			$profile_data['online'] = user_is_online_10($user_id);
			
			if ($config['Ach']) 
			{
				$achievementPoints = mysql_select_single("SELECT SUM(`value`) AS `sum` FROM `player_storage` WHERE `key` LIKE '30___' AND `player_id`=(int)$user_id");
			}
			
		} 
		else 
		{
			$profile_data = user_character_data($user_id, 'name', 'account_id', 'level', 'group_id', 'vocation', 'health', 'healthmax', 'experience', 'mana', 'manamax', 'lastlogin', 'online', 'sex');
		}
		
		$profile_znote_data = user_znote_character_data($user_id, 'created', 'hide_char', 'comment');
		$account_data = user_znote_account_data($profile_data['account_id'], 'flag');
		
		$guild_exist = false;
		
		if (get_character_guild_rank($user_id) > 0) 
		{
			$guild_exist = true;
			$guild = get_player_guild_data($user_id);
			$guild_name = get_guild_name($guild['guild_id']);
		}
		
		?>

<div class="content bg-image overflow-hidden" style="background-image: url('layouts/beastn/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">View Character</h1>
        <h2 class="h5 text-white-op animated zoomIn">Detailed information about player <?php echo $profile_data['name']; ?> </h2>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">General Information</h3>
                </div>
                <div class="block-content">
                    <table class="table">
                        <tr>
                            <td width="20%">Name</td><td><?php echo $profile_data['name'] ?></td>
                        </tr>
                        <?php if ($config['country_flags']): ?>
                            <tr>
                                <td>Country</td><td><?php echo '<img src="flags/' . $account_data['flag'] . '.png">'; ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Sex</td><td><?php echo ($profile_data['sex'] == 1) ? 'Male' : 'Female'; ?></td>
                        </tr>
                        <tr>
                            <td>Level</td><td><?php echo $profile_data['level']; ?></td>
                        </tr>
                        <tr>
                            <td>Vocation</td><td><?php echo vocation_id_to_name($profile_data['vocation']); ?></td>
                        </tr>
                        <?php if ($guild_exist): ?>
                            <tr>    
                                <td>Guild</td><td><?php echo $guild['rank_name']; ?> </b> of <a href="/?subtopic=guilds&name=<?php echo $guild_name; ?>"><?php echo $guild_name; ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if (!empty($profile_znote_data['comment'])): ?>
                            <tr>
                            <td>Comment</td><td><textarea name="profile_comment_textarea" cols="70" rows="10" readonly="readonly" class="span12"><?php echo $profile_znote_data['comment']; ?></textarea></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Status</td>
                            <td>
                            <?php 
                            if ($config['TFSVersion'] == 'TFS_10') 
                            {
                                if ($profile_data['online']) 
                                {
                                    echo '<font class="profile_font" name="profile_font_online" color="green"><b>ONLINE</b></font>';
                                } 
                                else 
                                {
                                    echo '<font class="profile_font" name="profile_font_online" color="red"><b>OFFLINE</b></font>';
                                }
                            } 
                            else 
                            {
                                if ($profile_data['online']) 
                                {
                                    echo '<font class="profile_font" name="profile_font_online" color="green"><b>ONLINE</b></font>';
                                } 
                                else 
                                {
                                    echo '<font class="profile_font" name="profile_font_online" color="red"><b>OFFLINE</b></font>';
                                }
                            }
                            ?>
                            </td>
                        </tr>
                        <?php if ($profile_data['group_id'] > 1): ?>
                            <tr>
                                <td>Position</td><td><?php echo group_id_to_name($profile_data['group_id']); ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td>Last Login</td><td><?php echo ($profile_data['lastlogin'] != 0) ? getClock($profile_data['lastlogin'], true, true) : 'Never.'; ?></td>
                        </tr>
                        <tr>
                            <td>Created</td><td><?php echo getClock($profile_znote_data['created'], true); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Deaths</h3>
                </div>
                <div class="block-content">
                    <table class="table">
                        <?php
                            if ($config['TFSVersion'] == 'TFS_02') 
                            {
                                $array = user_fetch_deathlist($user_id);
                                if ($array) 
                                {
                                ?>
                                    <ul>
                                    <?php
                                    // Design and present the list
                                    foreach ($array as $value) 
                                    { ?>
                                        <li>
                                        <?php
                                        $value['time'] = getClock($value['time'], true);
                                        
                                        if ($value['is_player'] == 1) 
                                        {
                                            $value['killed_by'] = 'player: <a href="characterprofile.php?name='. $value['killed_by'] .'">'. $value['killed_by'] .'</a>';
                                        } 
                                        else 
                                        {
                                            $value['killed_by'] = 'monster: '. $value['killed_by'] .'.';
                                        }
                                        
                                        echo '['. $value['time'] .'] Killed at level '. $value['level'] .' by '. $value['killed_by']; ?>
                                        </li>
                                    <?php
                                    }
                                    ?>
                                    </ul>
                                    <?php
                                } 
                                else 
                                {
                                    echo '<b><font color="green">This player has never died.</font></b>';
                                }
                            } 
                            else if ($config['TFSVersion'] == 'TFS_10') 
                            {
                                $deaths = mysql_select_multi("SELECT 
                                    `player_id`, `time`, `level`, `killed_by`, `is_player`, 
                                    `mostdamage_by`, `mostdamage_is_player`, `unjustified`, `mostdamage_unjustified` 
                                    FROM `player_deaths` 
                                    WHERE `player_id`=$user_id ORDER BY `time` DESC LIMIT 10;");

                                if ($deaths)
                                { 
                                    foreach ($deaths as $d) 
                                    {
                                        ?>
                                        <li>
                                            <?php echo "<b>".getClock($d['time'], true, true)."</b>";
                                            $lasthit = ($d['is_player']) ? "<a href='characterprofile.php?name=".$d['killed_by']."'>".$d['killed_by']."</a>" : $d['killed_by'];
                                            echo ": Killed at level ".$d['level']." by $lasthit";
                                            if ($d['unjustified']) 
                                            {echo " <font color='red' style='font-style: italic;'>(unjustified)</font>";}
                                        
                                            $mostdmg = ($d['mostdamage_by'] !== $d['killed_by']) ? true : false;
                                            
                                            if ($mostdmg) 
                                            {
                                                $mostdmg = ($d['mostdamage_is_player']) ? "<a href='characterprofile.php?name=".$d['mostdamage_by']."'>".$d['mostdamage_by']."</a>" : $d['mostdamage_by'];
                                                echo "<br>and by $mostdmg.";
                                                
                                                if ($d['mostdamage_unjustified']) 
                                                { echo " <font color='red' style='font-style: italic;'>(unjustified)</font>"; }
                                            } 
                                            else 
                                            { echo " <b>(soloed)</b>"; }
                                            ?>
                                        </li>
                                        <?php
                                    }
                                }
                                else 
                                {
                                    echo '<b><font color="green">This player has never died.</font></b>'; 
                                }
                            } 
                            else if ($config['TFSVersion'] == 'TFS_03') 
                            {
                                //mysql_select_single("SELECT * FROM players WHERE name='TEST DEBUG';");
                                $array = user_fetch_deathlist03($user_id);
                                
                                if ($array) 
                                {?>
                                    <ul>
                                        <?php
                                        // Design and present the list
                                        foreach ($array as $value) 
                                        { ?>
                                            <li>
                                            <?php
                                            $value[3] = user_get_killer_id(user_get_kid($value['id']));
                                            
                                            if ($value[3] !== false && $value[3] >= 1) 
                                            {
                                                $namedata = user_character_data((int)$value[3], 'name');
                                                
                                                if ($namedata !== false) 
                                                {
                                                    $value[3] = $namedata['name'];
                                                    $value[3] = 'player: <a href="characterprofile.php?name='. $value[3] .'">'. $value[3] .'</a>';
                                                } 
                                                else 
                                                {
                                                    $value[3] = 'deleted player.';
                                                }
                                            } 
                                            else 
                                            {
                                                $value[3] = user_get_killer_m_name(user_get_kid($value['id']));
                                                
                                                if ($value[3] === false) 
                                                { $value[3] = 'deleted player.'; }
                                            }
                                            
                                            echo '['. getClock($value['date'], true) .'] Killed at level '. $value['level'] .' by '. $value[3];
                                            echo '</li>';
                                        }
                                        ?>
                                    </ul>
                                    <?php
                                } 
                                else { echo '<b><font color="green">This player has never died.</font></b>'; }
                            }
                        ?>  
                    </table>
                </div>
            </div>

            <?php if (user_character_hide($profile_data['name']) != 1 && user_character_list_count(user_character_account_id($name)) > 1): ?>
            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">Characters</h3>
                </div>
                <div class="block-content">
                    <table class="table">
                        <?php 
                            $characters = user_character_list(user_character_account_id($profile_data['name']));

                            if ($characters && count($characters) > 0) 
                            {
                                ?>
                                    <tr class="yellow">
                                        <th>Name:</th>
                                        <th>Level:</th>
                                        <th>Vocation:</th>
                                        <th>Last login:</th>
                                        <th>Status:</th>
                                    </tr>
                                    
                                    <?php
                                    // Design and present the list
                                    foreach ($characters as $char) 
                                    {
                                        if ($char['name'] != $profile_data['name']) 
                                        {
                                            if (hide_char_to_name(user_character_hide($char['name'])) != 'hidden') 
                                            { ?>
                                                <tr>
                                                    <td><a href="/?subtopic=characterprofile&name=<?php echo $char['name']; ?>"><?php echo $char['name']; ?></a></td>
                                                    <td><?php echo (int)$char['level']; ?></td>
                                                    <td><?php echo $char['vocation']; ?></td>
                                                    <td><?php echo $char['lastlogin']; ?></td>
                                                    <td><?php echo $char['online']; ?></td>
                                                </tr>
                                            <?php
                                            }
                                        }
                                    }
                                ?>
                                <?php
                            } 
                        ?>
                    </table>
                </div>
            </div>
            <?php endif; ?>

        </div> 
    </div> 
</div>
		
		<?php
	} 
	else 
	{
        ?>
           <div class="content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-danger">Character <strong><?php echo htmlentities(strip_tags($name, ENT_QUOTES)); ?></strong> do not exist</div>
                    </div>
                </div>
            </div> 
        <?php
	}
} 
else 
{
    redirect('index');
}
?>
