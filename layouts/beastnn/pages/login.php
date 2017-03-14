<?php
require_once 'engine/init.php';
logged_in_redirect();

if (empty($_POST) === false) {
	if ($config['log_ip']) {
		znote_visitor_insert_detailed_data(5);
	}
	$username = $_POST['username'];
	$password = $_POST['password'];
	//data_dump($_POST, false, "POST");
	if (empty($username) || empty($password)) {
		$errors[] = 'You need to enter a username and password.';
	} else if (strlen($username) > 32 || strlen($password) > 64) {
			$errors[] = 'Username or password is too long.';
	} else if (user_exist($username) === false) {
		$errors[] = 'Failed to authorize your account, are the details correct, have you <a href=\'register.php\'>register</a>ed?';
	} /*else if (user_activated($username) === false) {
		$errors[] = 'You havent activated your account! Please check your email. <br>Note it may appear in your junk/spam box.';
	} */else if (!Token::isValid($_POST['token'])) {
		Token::debug($_POST['token']);
		$errors[] = 'Token is invalid.';
	} else {
		
		// Starting loging
		if ($config['TFSVersion'] == 'TFS_02' || $config['TFSVersion'] == 'TFS_10') $login = user_login($username, $password);
		else if ($config['TFSVersion'] == 'TFS_03') $login = user_login_03($username, $password);
		else $login = false;
		if ($login === false) {
			$errors[] = 'Username and password combination is wrong.';
		} else {
			// Check if user have access to login
			$status = false;
			if ($config['mailserver']['register']) {
				$authenticate = mysql_select_single("SELECT `id` FROM `znote_accounts` WHERE `account_id`='$login' AND `active`='1' LIMIT 1;");
				if ($authenticate !== false) {
					$status = true;
				} else {
					$errors[] = "Your account is not activated. An email should have been sent to you when you registered. Please find it and click the activation link to activate your account.";
				}
			} else $status = true;
			
			if ($status) {
				setSession('user_id', $login);
			
				// if IP is not set (etc acc created before Znote AAC was in use)
				$znote_data = user_znote_account_data($login);
				if ($znote_data['ip'] == 0) {
					$update_data = array(
					'ip' => getIPLong(),
					);
					user_update_znote_account($update_data);
				}
				
				// Send them to myaccount.php
				redirect('myaccount');
			}
		}
	}
}

?>


<div class="content bg-image overflow-hidden" style="background-image: url('layouts/beastn/assets/img/photos/photo3@2x.jpg');">
    <div class="push-50-t push-15">
        <h1 class="h2 text-white animated zoomIn">Login</h1>
        <h2 class="h5 text-white-op animated zoomIn">View and edit your Blueshift account.</h2>
    </div>
</div>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-lg-12">

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
                                <?php Token::create(); ?>
                                <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-arrow-right push-5-r"></i> Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>