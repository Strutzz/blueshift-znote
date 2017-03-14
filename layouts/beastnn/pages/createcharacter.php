<?php 
protect_page();

if (empty($_POST) === false) {
	// $_POST['']
	$required_fields = array('name', 'selected_town');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'You need to fill in all fields.';
			break 1;
		}
	}
	
	// check errors (= user exist, pass long enough
	if (empty($errors) === true) {
		if (!Token::isValid($_POST['token'])) {
			$errors[] = 'Token is invalid.';
		}
		$_POST['name'] = validate_name($_POST['name']);
		if ($_POST['name'] === false) {
			$errors[] = 'Your name can not contain more than 2 words.';
		} else {
			if (user_character_exist($_POST['name']) !== false) {
				$errors[] = 'Sorry, that character name already exist.';
			}
			if (!preg_match("/^[a-zA-Z_ ]+$/", $_POST['name'])) {
				$errors[] = 'Your name may only contain a-z, A-Z and spaces.';
			}
			if (strlen($_POST['name']) < $config['minL'] || strlen($_POST['name']) > $config['maxL']) {
				$errors[] = 'Your character name must be between ' . $config['minL'] . ' - ' . $config['maxL'] . ' characters long.';
			}
			// name restriction
			$resname = explode(" ", $_POST['name']);
			foreach($resname as $res) {
				if(in_array(strtolower($res), $config['invalidNameTags'])) {
					$errors[] = 'Your username contains a restricted word.';
				}
				else if(strlen($res) == 1) {
					$errors[] = 'Too short words in your name.';
				}
			}
			// Validate vocation id
			if (!in_array((int)$_POST['selected_vocation'], $config['available_vocations'])) {
				$errors[] = 'Permission Denied. Wrong vocation.';
			}
			// Validate town id
			if (!in_array((int)$_POST['selected_town'], $config['available_towns'])) {
				$errors[] = 'Permission Denied. Wrong town.';
			}
			// Validate gender id
			if (!in_array((int)$_POST['selected_gender'], array(0, 1))) {
				$errors[] = 'Permission Denied. Wrong gender.';
			}
			if (vocation_id_to_name($_POST['selected_vocation']) === false) {
				$errors[] = 'Failed to recognize that vocation, does it exist?';
			}
			if (town_id_to_name($_POST['selected_town']) === false) {
				$errors[] = 'Failed to recognize that town, does it exist?';
			}
			if (gender_exist($_POST['selected_gender']) === false) {
				$errors[] = 'Failed to recognize that gender, does it exist?';
			}
			// Char count
			$char_count = user_character_list_count($session_user_id);
			if ($char_count >= $config['max_characters']) {
				$errors[] = 'Your account is not allowed to have more than '. $config['max_characters'] .' characters.';
			}
			if (validate_ip(getIP()) === false && $config['validate_IP'] === true) {
				$errors[] = 'Failed to recognize your IP address. (Not a valid IPv4 address).';
			}
		}
	}
}
?>

<div class="content bg-image overflow-hidden" style="background-image: url('layouts/default/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Create Character</h1>
        <h2 class="h5 text-white-op animated zoomIn"></h2>
    </div>
</div>

<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
	// 
} else {
	if (empty($_POST) === false && empty($errors) === true) {
		if ($config['log_ip']) {
			znote_visitor_insert_detailed_data(2);
		}
		//Register
		$character_data = array(
			'name'		=>	format_character_name($_POST['name']),
			'account_id'=>	$session_user_id,
			'vocation'	=>	$_POST['selected_vocation'],
			'town_id'	=>	$_POST['selected_town'],
			'sex'		=>	$_POST['selected_gender'],
			'lastip'	=>	getIPLong(),
			'created'	=>	time()
		);
		
		user_create_character($character_data);
		header('Location: /?page=createcharacter&success');
		exit();
		//End register
		
	}

    $available_towns = $config['available_towns'];
}
?>

<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Congratulations! Your character has been created. See you in-game!
                </div>
            <?php endif; ?>

            <?php if (empty($errors) === false): ?>
                <div class="alert alert-danger">
                    <strong>Following errors has occured:</strong>
                    <?php echo output_errors($errors); ?>
                </div>
            <?php endif; ?>

            <div class="block block-themed">
                <div class="block-header bg-primary">
                    <h3 class="block-title">create character form</h3>
                </div>
                <div class="block-content">
                    <form class="form-horizontal push-10-t push-10" action="" method="post">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material">
                                    <input class="form-control" type="text" name="name" placeholder="Enter your character name.."  autocomplete="off">
                                    <label for="login2-username">Name</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material">
                                    <select name="selected_vocation" class="form-control">
                                        <?php foreach ($config['available_vocations'] as $id) { ?>
                                            <option value="<?php echo $id; ?>">
                                                <?php echo vocation_id_to_name($id); ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                    <label for="login2-username">Vocation</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material">
                                    <select name="selected_gender" class="form-control">
                                        <option value="1">Male(boy)</option>
                                        <option value="0">Female(girl)</option>
                                    </select>
                                    <label for="login2-username">Gender</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material">
                                    <select name="selected_town" class="form-control">
                                        <?php 
                                        foreach ($available_towns as $tid): 
                                            ?>
                                            <option value="<?php echo $tid; ?>"><?php echo town_id_to_name($tid); ?></option>
                                            <?php 
                                        endforeach; 
                                        ?>
                                    </select>
                                    <label for="login2-username">Town</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <?php Token::create(); ?>
                                <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-arrow-right push-5-r"></i> Create Character</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

