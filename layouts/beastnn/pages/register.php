<?php
logged_in_redirect();

if (empty($_POST) === false) {

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $config['google_secret'], 
        'response' => $_POST['g-recaptcha-response']
    );

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $googleResult = file_get_contents($url, false, $context);
    if ($googleResult === FALSE) { /* Handle error */ }

    $googleResult = json_decode($googleResult);

    if (! $googleResult->success) {
        $errors[] = 'Recaptcha is required';
    }

	// $_POST['']
	$required_fields = array('username', 'password', 'password_again', 'email');
	foreach($_POST as $key=>$value) {
		if (empty($value) && in_array($key, $required_fields) === true) {
			$errors[] = 'You need to fill in all fields.';
			break 1;
		}
	}
	
	// check errors (= user exist, pass long enough
	if (empty($errors) === true) {
		/* Token used for cross site scripting security */
		if (!Token::isValid($_POST['token'])) {
			$errors[] = 'Token is invalid.';
		}

		if ($config['use_captcha']) {
			include_once 'captcha/securimage.php';
			$securimage = new Securimage();
			if ($securimage->check($_POST['captcha_code']) == false) {
			  $errors[] = 'Captcha image verification was submitted wrong.';
			}
		}
		
		if (user_exist($_POST['username']) === true) {
			$errors[] = 'Sorry, that username already exist.';
		}
		
		// Don't allow "default admin names in config.php" access to register.
		$isNoob = in_array(strtolower($_POST['username']), $config['page_admin_access']) ? true : false;
		if ($isNoob) {
			$errors[] = 'This account name is blocked for registration.';
		}
		if (preg_match("/^[a-zA-Z0-9]+$/", $_POST['username']) == false) {
			$errors[] = 'Your account name can only contain characters a-z, A-Z and 0-9.';
		}
		// name restriction
		$resname = explode(" ", $_POST['username']);
		foreach($resname as $res) {
			if(in_array(strtolower($res), $config['invalidNameTags'])) {
				$errors[] = 'Your username contains a restricted word.';
			}
			else if(strlen($res) == 1) {
				$errors[] = 'Too short words in your name.';
			}
		}
		// end name restriction
		if (strlen($_POST['password']) < 6) {
			$errors[] = 'Your password must be at least 6 characters.';
		}
		if (strlen($_POST['password']) > 100) {
			$errors[] = 'Your password must be less than 100 characters.';
		}
		if ($_POST['password'] !== $_POST['password_again']) {
			$errors[] = 'Your passwords do not match.';
		}
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
			$errors[] = 'A valid email address is required.';
		}
		if (user_email_exist($_POST['email']) === true) {
			$errors[] = 'That email address is already in use.';
		}
		if (validate_ip(getIP()) === false && $config['validate_IP'] === true) {
			$errors[] = 'Failed to recognize your IP address. (Not a valid IPv4 address).';
		}
	}
}

?>

<?php
if (isset($_GET['success']) && empty($_GET['success'])) {
	if ($config['mailserver']['register']) {
		?>
		<h1>Email authentication required</h1>
		<p>We have sent you an email with an activation link to your submitted email address.</p>
		<p>If you can't find the email within 5 minutes, check your junk/trash inbox as it may be mislocated there.</p>
		<?php
	}
} elseif (isset($_GET['authenticate']) && empty($_GET['authenticate'])) {
	// Authenticate user, fetch user id and activation key
	$auid = (isset($_GET['u']) && (int)$_GET['u'] > 0) ? (int)$_GET['u'] : false;
	$akey = (isset($_GET['k']) && (int)$_GET['k'] > 0) ? (int)$_GET['k'] : false;
	// Find a match
	$user = mysql_select_single("SELECT `id` FROM `znote_accounts` WHERE `account_id`='$auid' AND `activekey`='$akey' AND `active`='0' LIMIT 1;");
	if ($user !== false) {
		$user = $user['id'];
		// Enable the account to login
		mysql_update("UPDATE `znote_accounts` SET `active`='1' WHERE `id`='$user' LIMIT 1;");
		echo '<h1>Congratulations!</h1> <p>Your account has been created. You may now login to create a character.</p>';
	} else {
		echo '<h1>Authentication failed</h1> <p>Either the activation link is wrong, or your account is already activated.</p>';
	}
} else {
	if (empty($_POST) === false && empty($errors) === true) {
		if ($config['log_ip']) {
			znote_visitor_insert_detailed_data(1);
		}
		
		//Register
		$register_data = array(
			'name'		=>	$_POST['username'],
			'password'	=>	$_POST['password'],
			'email'		=>	$_POST['email'],
			'created'	=>	time(),
			'ip'		=>	getIPLong(),
			'flag'		=> 	$_POST['flag']
		);
		
		user_create_account($register_data, $config['mailserver']);
		if (!$config['mailserver']['debug']) redirect('register&success');
		exit();
		//End register
		
	}
}
?>

<div class="content bg-image overflow-hidden" style="background-image: url('layouts/beastn/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Create Account</h1>
        <h2 class="h5 text-white-op animated zoomIn">Create your account in order to log in.</h2>
    </div>
</div>

<div class="content">
    <div class="row">
        <div class="col-lg-12">

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Your account has been created, you can now <a href="<?php echo url('login') ?>">login</a>.
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
                    <h3 class="block-title">new account form</h3>
                </div>
                <div class="block-content">
                    <form class="form-horizontal push-10-t push-10" action="" method="post">
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material">
                                    <input class="form-control" type="text" name="username" placeholder="Enter your account name.."  autocomplete="off">
                                    <label for="login2-username">Account Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material">
                                    <input class="form-control" type="password" name="password" placeholder="Enter your password.." autocomplete="off">
                                    <label for="login2-password">Password</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material">
                                    <input class="form-control" type="password" name="password_again" placeholder="Enter your password again.." autocomplete="off">
                                    <label for="login2-password">Password Again</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material">
                                    <input class="form-control" type="text" name="email" placeholder="Enter your email.."  autocomplete="off">
                                    <label for="login2-username">Email</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <div class="form-material">
                                    <select name="flag" class="form-control">
                                        <option value="">(Please choose)</option><option value="af"> Afghanistan </option><option value="al"> Albania </option><option value="dz"> Algeria </option><option value="as"> American Samoa </option><option value="ad"> Andorra </option><option value="ao"> Angola </option><option value="ai"> Anguilla </option><option value="aq"> Antarctica </option><option value="ag"> Antigua and Barbuda </option><option value="ar"> Argentina </option>
                                        <option value="am"> Armenia </option><option value="aw"> Aruba </option><option value="au"> Australia </option><option value="at"> Austria </option><option value="az"> Azerbaijan </option><option value="bs"> Bahamas </option><option value="bh"> Bahrain </option><option value="bd"> Bangladesh </option><option value="bb"> Barbados </option><option value="by"> Belarus </option><option value="be"> Belgium </option><option value="bz"> Belize </option><option value="bj"> Benin </option><option value="bm"> Bermuda </option><option value="bt"> Bhutan </option><option value="bo"> Bolivia </option><option value="ba"> Bosnia and Herzegowina </option><option value="bw"> Botswana </option><option value="bv"> Bouvet Island </option><option value="br"> Brazil </option><option value="io"> British Indian Ocean Territory </option><option value="bn"> Brunei Darussalam </option><option value="bg"> Bulgaria </option><option value="bf"> Burkina Faso </option><option value="bi"> Burundi </option>
                                        <option value="kh"> Cambodia </option><option value="cm"> Cameroon </option><option value="ca"> Canada </option><option value="cv"> Cape Verde </option><option value="ky"> Cayman Islands </option><option value="cf"> Central African Republic </option><option value="td"> Chad </option><option value="cl"> Chile </option><option value="cn"> China </option><option value="cx"> Christmas Island </option><option value="cc"> Cocos Islands </option><option value="co"> Colombia </option><option value="km"> Comoros </option><option value="cd"> Congo </option><option value="cg"> Congo </option><option value="ck"> Cook Islands </option><option value="cr"> Costa Rica </option><option value="ci"> Cote DIvoire </option><option value="hr"> Croatia </option><option value="cu"> Cuba </option><option value="cy"> Cyprus </option><option value="cz"> Czech Republic </option><option value="dk"> Denmark </option><option value="dj"> Djibouti </option><option value="dm"> Dominica </option>
                                        <option value="do"> Dominican Republic </option><option value="tp"> East Timor </option><option value="ec"> Ecuador </option><option value="eg"> Egypt </option><option value="sv"> El Salvador </option><option value="gq"> Equatorial Guinea </option><option value="er"> Eritrea </option><option value="ee"> Estonia </option><option value="et"> Ethiopia </option><option value="fk"> Falkland Islands </option><option value="fo"> Faroe Islands </option><option value="fj"> Fiji </option><option value="fi"> Finland </option><option value="fr"> France </option><option value="gf"> French Guiana </option><option value="pf"> French Polynesia </option><option value="tf"> French Southern Territories </option><option value="ga"> Gabon </option><option value="gm"> Gambia </option><option value="ge"> Georgia </option><option value="de"> Germany </option><option value="gh"> Ghana </option><option value="gi"> Gibraltar </option><option value="gr"> Greece </option>
                                        <option value="gl"> Greenland </option><option value="gd"> Grenada </option><option value="gp"> Guadeloupe </option><option value="gu"> Guam </option><option value="gt"> Guatemala </option><option value="gn"> Guinea </option><option value="gw"> Guinea-Bissau </option><option value="gy"> Guyana </option><option value="ht"> Haiti </option><option value="hm"> Heard and Mc Donald Islands </option><option value="hn"> Honduras </option><option value="hk"> Hong Kong </option><option value="hu"> Hungary </option><option value="is"> Iceland </option><option value="in"> India </option><option value="id"> Indonesia </option><option value="ir"> Iran </option><option value="iq"> Iraq </option><option value="ie"> Ireland </option><option value="il"> Israel </option><option value="it"> Italy </option><option value="jm"> Jamaica </option><option value="jp"> Japan </option><option value="jo"> Jordan </option><option value="kz"> Kazakhstan </option><option value="ke"> Kenya </option>
                                        <option value="ki"> Kiribati </option><option value="kr"> Korea </option><option value="kp"> Korea </option><option value="kw"> Kuwait </option><option value="kg"> Kyrgyzstan </option><option value="la"> Lao Peoples Democratic Republic </option><option value="lv"> Latvia </option><option value="lb"> Lebanon </option><option value="ls"> Lesotho </option><option value="lr"> Liberia </option><option value="ly"> Libyan Arab Jamahiriya </option><option value="li"> Liechtenstein </option><option value="lt"> Lithuania </option><option value="lu"> Luxembourg </option><option value="mo"> Macau </option><option value="mk"> Macedonia </option><option value="mg"> Madagascar </option><option value="mw"> Malawi </option><option value="my"> Malaysia </option><option value="mv"> Maldives </option><option value="ml"> Mali </option><option value="mt"> Malta </option><option value="mh"> Marshall Islands </option><option value="mq"> Martinique </option>
                                        <option value="mr"> Mauritania </option><option value="mu"> Mauritius </option><option value="yt"> Mayotte </option><option value="mx"> Mexico </option><option value="fm"> Micronesia </option><option value="md"> Moldova </option><option value="mc"> Monaco </option><option value="mn"> Mongolia </option><option value="ms"> Montserrat </option><option value="ma"> Morocco </option><option value="mz"> Mozambique </option><option value="mm"> Myanmar </option><option value="na"> Namibia </option><option value="nr"> Nauru </option><option value="np"> Nepal </option><option value="nl"> Netherlands </option><option value="an"> Netherlands Antilles </option><option value="nc"> New Caledonia </option><option value="nz"> New Zealand </option><option value="ni"> Nicaragua </option><option value="ne"> Niger </option><option value="ng"> Nigeria </option><option value="nu"> Niue </option><option value="nf"> Norfolk Island </option><option value="mp"> Northern Mariana Islands </option>
                                        <option value="no"> Norway </option><option value="om"> Oman </option><option value="pk"> Pakistan </option><option value="pw"> Palau </option><option value="pa"> Panama </option><option value="pg"> Papua New Guinea </option><option value="py"> Paraguay </option><option value="pe"> Peru </option><option value="ph"> Philippines </option><option value="pn"> Pitcairn </option><option value="pl"> Poland </option><option value="pt"> Portugal </option><option value="pr"> Puerto Rico </option><option value="qa"> Qatar </option><option value="re"> Reunion </option><option value="ro"> Romania </option><option value="ru"> Russian Federation </option><option value="rw"> Rwanda </option><option value="kn"> Saint Kitts and Nevis </option><option value="lc"> Saint Lucia </option><option value="ws"> Samoa </option><option value="sm"> San Marino </option><option value="st"> Sao Tome and Principe </option><option value="sa"> Saudi Arabia </option><option value="sn"> Senegal </option>
                                        <option value="sc"> Seychelles </option><option value="sl"> Sierra Leone </option><option value="sg"> Singapore </option><option value="sk"> Slovakia </option><option value="si"> Slovenia </option><option value="sb"> Solomon Islands </option><option value="so"> Somalia </option><option value="za"> South Africa </option><option value="es"> Spain </option><option value="lk"> Sri Lanka </option><option value="sh"> St. Helena </option><option value="pm"> St. Pierre and Miquelon </option><option value="sd"> Sudan </option><option value="sr"> Suriname </option><option value="sj"> Svalbard and Jan Mayen Islands </option><option value="sz"> Swaziland </option><option value="se"> Sweden </option><option value="ch"> Switzerland </option><option value="sy"> Syrian Arab Republic </option><option value="tw"> Taiwan </option><option value="tj"> Tajikistan </option><option value="tz"> Tanzania </option>
                                        <option value="th"> Thailand </option><option value="tg"> Togo </option><option value="tk"> Tokelau </option><option value="to"> Tonga </option><option value="tt"> Trinidad and Tobago </option><option value="tn"> Tunisia </option><option value="tr"> Turkey </option><option value="tm"> Turkmenistan </option><option value="tc"> Turks and Caicos Islands </option><option value="tv"> Tuvalu </option><option value="ug"> Uganda </option><option value="ua"> Ukraine </option><option value="ae"> United Arab Emirates </option><option value="gb"> United Kingdom </option><option value="us"> United States </option><option value="uy"> Uruguay </option><option value="uz"> Uzbekistan </option><option value="vu"> Vanuatu </option><option value="va"> Vatican </option><option value="ve"> Venezuela </option><option value="vn"> Viet Nam </option><option value="vg"> Virgin Islands (British) </option><option value="vi"> Virgin Islands (US) </option>
                                        <option value="wf"> Wallis and Futuna Islands </option><option value="eh"> Western Sahara </option><option value="ye"> Yemen </option><option value="yu"> Yugoslavia </option><option value="zm"> Zambia </option><option value="zw"> Zimbabwe </option>
                                    </select>
                                    <label for="login2-username">Country</label>
                                </div>
                            </div>
                        </div>

                        <div class="g-recaptcha" data-sitekey="<?php echo $config['google_sitekey']; ?>" style="margin-bottom: 10px;"></div>
               
                        <div class="form-group">
                            <div class="col-xs-12">
                                <?php Token::create(); ?>
                                <p>By clicking the "Register" button you are agreeing to the <a href="#">server rules.</a></p>
                                <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-arrow-right push-5-r"></i> Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src='https://www.google.com/recaptcha/api.js'></script>
