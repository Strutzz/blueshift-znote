<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>Blueshift OT</title>

        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="layouts/beastn/assets/img/favicons/favicon.png">

        <link rel="icon" type="image/png" href="layouts/beastn/assets/img/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="icon" type="image/png" href="layouts/beastn/assets/img/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="layouts/beastn/assets/img/favicons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="layouts/beastn/assets/img/favicons/favicon-160x160.png" sizes="160x160">
        <link rel="icon" type="image/png" href="layouts/beastn/assets/img/favicons/favicon-192x192.png" sizes="192x192">

        <link rel="apple-touch-icon" sizes="57x57" href="layouts/beastn/assets/img/favicons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="layouts/beastn/assets/img/favicons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="layouts/beastn/assets/img/favicons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="layouts/beastn/assets/img/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="layouts/beastn/assets/img/favicons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="layouts/beastn/assets/img/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="layouts/beastn/assets/img/favicons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="layouts/beastn/assets/img/favicons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="layouts/beastn/assets/img/favicons/apple-touch-icon-180x180.png">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Web fonts -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,600,700%7COpen+Sans:300,400,400italic,600,700">

        <!-- Page JS Plugins CSS -->
        <link rel="stylesheet" href="layouts/beastn/assets/js/plugins/slick/slick.min.css">
        <link rel="stylesheet" href="layouts/beastn/assets/js/plugins/slick/slick-theme.min.css">

        <!-- Bootstrap and OneUI CSS framework -->
        <link rel="stylesheet" href="layouts/beastn/assets/css/bootstrap.min.css">
        <link rel="stylesheet" id="css-main" href="layouts/beastn/assets/css/oneui.css">

        <!-- You can include a specific file from css/themes/ folder to alter the default color theme of the template. eg: -->
        <!-- <link rel="stylesheet" id="css-theme" href="layouts/beastn/assets/css/themes/flat.min.css"> -->
        <!-- END Stylesheets -->
    </head>
    <body>
        <!-- Page Container -->
        <!--
            Available Classes:

            'enable-cookies'             Remembers active color theme between pages (when set through color theme list)

            'sidebar-l'                  Left Sidebar and right Side Overlay
            'sidebar-r'                  Right Sidebar and left Side Overlay
            'sidebar-mini'               Mini hoverable Sidebar (> 991px)
            'sidebar-o'                  Visible Sidebar by default (> 991px)
            'sidebar-o-xs'               Visible Sidebar by default (< 992px)

            'side-overlay-hover'         Hoverable Side Overlay (> 991px)
            'side-overlay-o'             Visible Side Overlay by default (> 991px)

            'side-scroll'                Enables custom scrolling on Sidebar and Side Overlay instead of native scrolling (> 991px)

            'header-navbar-fixed'        Enables fixed header
        -->
        <div id="page-container" class="sidebar-l sidebar-o side-scroll header-navbar-fixed">
            <!-- Side Overlay-->
            <!-- END Side Overlay -->

            <!-- Sidebar -->
            <nav id="sidebar">
                <!-- Sidebar Scroll Container -->
                <div id="sidebar-scroll">
                    <!-- Sidebar Content -->
                    <!-- Adding .sidebar-mini-hide to an element will hide it when the sidebar is in mini mode -->
                    <div class="sidebar-content">
                        <!-- Side Header -->
                        <div class="side-header side-content bg-white-op">
                            <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                            <button class="btn btn-link text-gray pull-right hidden-md hidden-lg" type="button" data-toggle="layout" data-action="sidebar_close">
                                <i class="fa fa-times"></i>
                            </button>
                            <!-- Themes functionality initialized in App() -> uiHandleTheme() -->
                            <a class="h5 text-white" href="index.html">
							<img src="layouts/beastn/images/logo.png" alt="Mountain View" style="width:70px;height:77px;">
							<span class="h4 font-w600 sidebar-mini-hide">Blueshift OT</span>
                            </a>
                        </div>
                        <!-- END Side Header -->

                        <!-- Side Content -->
                        <div class="side-content">
                            <ul class="nav-main">
                                <li>
                                    <a class="active" href="/"><i class="si si-globe"></i><span class="sidebar-mini-hide">Latest News</span></a>
                                </li>
                                <li class="nav-main-heading"><span class="sidebar-mini-hide">Account</span></li>
                                <?php if (! user_logged_in()): ?>
                                    <li>
                                        <a href="/?subtopic=register"><i class="si si-user"></i><span class="sidebar-mini-hide">Create Account</span></a>
                                        <a href="/?subtopic=login"><i class="si si-login"></i><span class="sidebar-mini-hide">Login</span></a>
                                    </li>
                                <?php else: ?>
                                    <li>
                                        <a href="/?subtopic=myaccount"><i class="si si-user"></i><span class="sidebar-mini-hide">My Account</span></a>
                                        <a href="/?subtopic=logout"><i class="si si-logout"></i><span class="sidebar-mini-hide">Logout</span></a>
                                    </li>
                                <?php endif; ?>
                                <li class="nav-main-heading"><span class="sidebar-mini-hide">Community</span></li>
                                <li>
                                    <a href="/forums"><i class="fa fa-comments-o"></i><span class="sidebar-mini-hide">Forum</span></a>
                                </li>
                                <li>
                                    <a href="/?subtopic=onlinelist"><i class="si si-users"></i><span class="sidebar-mini-hide">Who is Online?</span></a>
                                </li>
                                <li>
                                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="si si-badge"></i><span class="sidebar-mini-hide">Highscores</span></a>
                                    <ul>
                                        <li>
                                            <a href="/?subtopic=highscores&type=7">Experience</a>
                                        </li>
                                        <li>
                                            <a href="/?subtopic=highscores&type=8">Magic Level</a>
                                        </li>
                                        <li>
                                            <a href="/?subtopic=highscores&type=5">Shielding</a>
                                        </li>
                                        <li>
                                            <a href="/?subtopic=highscores&type=2">Sword Fighting</a>
                                        </li>
                                        <li>
                                            <a href="/?subtopic=highscores&type=1">Club Fighting</a>
                                        </li>
                                        <li>
                                            <a href="/?subtopic=highscores&type=3">Axe Fighting</a>
                                        </li>
                                        <li>
                                            <a href="/?subtopic=highscores&type=9">Fist Fighting</a>
                                        </li>
                                        <li>
                                            <a href="/?subtopic=highscores&type=4">Distance</a>
                                        </li>
                                        <li>
                                            <a href="/?subtopic=highscores&type=6">Fishing</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="/?subtopic=serverinfo"><i class="si si-question"></i><span class="sidebar-mini-hide">Server Info</span></a>
                                </li>
                                <li>
                                    <a href="/?subtopic=support"><i class="si si-support"></i><span class="sidebar-mini-hide">Support</span></a>
                                </li>
                                <li class="nav-main-heading"><span class="sidebar-mini-hide">Guilds</span></li>
                                <li>
                                      <a href="/?subtopic=guilds"><i class="si si-list"></i><span class="sidebar-mini-hide">Guild List</span></a>
                                      <!-- <a href="#"><i class="si si-fire"></i><span class="sidebar-mini-hide">Guild Wars</span></a> -->
                                </li>
                                <li class="nav-main-heading"><span class="sidebar-mini-hide">Store</span></li>
                                <li>
                                      <a href="<?php echo url('buypoints') ?>"><i class="si si-credit-card"></i><span class="sidebar-mini-hide">Buy Points</span></a>
                                      <a href="<?php echo url('shop') ?>"><i class="si si-basket"></i><span class="sidebar-mini-hide">Shop Offers</span></a>
                                </li>
                            </ul>
                        </div>
                        <!-- END Side Content -->
                    </div>
                    <!-- Sidebar Content -->
                </div>
                <!-- END Sidebar Scroll Container -->
            </nav>
            <!-- END Sidebar -->

            <!-- Header -->
            <header id="header-navbar" class="content-mini content-mini-full">
                <!-- Header Navigation Right -->
                <ul class="nav-header pull-right">
                    <!-- <li>
                        <div class="btn-group">
                            <button class="btn btn-default btn-image dropdown-toggle" data-toggle="dropdown" type="button">
                                <img src="layouts/beastn/assets/img/avatars/avatar10.jpg" alt="Avatar">
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-header">Profile</li>
                                <li>
                                    <a tabindex="-1" href="base_pages_inbox.html">
                                        <i class="si si-envelope-open pull-right"></i>
                                        <span class="badge badge-primary pull-right">3</span>Inbox
                                    </a>
                                </li>
                                <li>
                                    <a tabindex="-1" href="base_pages_profile.html">
                                        <i class="si si-user pull-right"></i>
                                        <span class="badge badge-success pull-right">1</span>Profile
                                    </a>
                                </li>
                                <li>
                                    <a tabindex="-1" href="javascript:void(0)">
                                        <i class="si si-settings pull-right"></i>Settings
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li class="dropdown-header">Actions</li>
                                <li>
                                    <a tabindex="-1" href="base_pages_lock.html">
                                        <i class="si si-lock pull-right"></i>Lock Account
                                    </a>
                                </li>
                                <li>
                                    <a tabindex="-1" href="base_pages_login.html">
                                        <i class="si si-logout pull-right"></i>Log out
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li> -->
                </ul>
                <!-- END Header Navigation Right -->

                <!-- Header Navigation Left -->
                <ul class="nav-header pull-left">
                    <li class="hidden-md hidden-lg">
                        <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                        <button class="btn btn-default" data-toggle="layout" data-action="sidebar_toggle" type="button">
                            <i class="fa fa-navicon"></i>
                        </button>
                    </li>
                    <li class="hidden-xs hidden-sm">
                        <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                        <button class="btn btn-default" data-toggle="layout" data-action="sidebar_mini_toggle" type="button">
                            <i class="fa fa-ellipsis-v"></i>
                        </button>
                    </li>
                    <li class="visible-xs">
                        <!-- Toggle class helper (for .js-header-search below), functionality initialized in App() -> uiToggleClass() -->
                        <button class="btn btn-default" data-toggle="class-toggle" data-target=".js-header-search" data-class="header-search-xs-visible" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </li>
                    <li class="js-header-search header-search">
                        <form class="form-horizontal" action="" method="get">
                            <input type="hidden" name="subtopic" value="characterprofile">
                            <div class="form-material form-material-primary input-group remove-margin-t remove-margin-b">
                                <input class="form-control" type="text" name="name" placeholder="Search character..">
                                <span class="input-group-addon"><i class="si si-magnifier"></i></span>
                            </div>
                        </form>
                    </li>
                </ul>
                <!-- END Header Navigation Left -->
            </header>
            <!-- END Header -->

            <!-- Main Container -->
            <main id="main-container">
                <?php 
                    if (! isset($_GET['subtopic']) || ! file_exists($layout->path('pages/'.$_GET['subtopic'].'.php'))) {
                        require_once $layout->path('pages/index.php'); 
                    } else {
                        require_once $layout->path('pages/'.$_GET['subtopic'].'.php'); 
                    }
                ?>
            </main>
            <!-- END Main Container -->

            <!-- Footer -->
            <footer id="page-footer" class="content-mini content-mini-full font-s12 bg-gray-lighter clearfix">
                <div class="pull-right">
                    
                </div>
                <div class="pull-left">
                    Powered by <a class="font-w600" href="#">ZnoteAAC X</a>
                </div>
            </footer>
            <!-- END Footer -->
        </div>
        <!-- END Page Container -->

        <!-- Apps Modal -->
        <!-- Opens from the button in the header -->
        <div class="modal fade" id="apps-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-sm modal-dialog modal-dialog-top">
                <div class="modal-content">
                    <!-- Apps Block -->
                    <div class="block block-themed block-transparent">
                        <div class="block-header bg-primary-dark">
                            <ul class="block-options">
                                <li>
                                    <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                                </li>
                            </ul>
                            <h3 class="block-title">Apps</h3>
                        </div>
                        <div class="block-content">
                            <div class="row text-center">
                                <div class="col-xs-6">
                                    <a class="block block-rounded" href="base_pages_dashboard.html">
                                        <div class="block-content text-white bg-default">
                                            <i class="si si-speedometer fa-2x"></i>
                                            <div class="font-w600 push-15-t push-15">Backend</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xs-6">
                                    <a class="block block-rounded" href="frontend_home.html">
                                        <div class="block-content text-white bg-modern">
                                            <i class="si si-rocket fa-2x"></i>
                                            <div class="font-w600 push-15-t push-15">Frontend</div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END Apps Block -->
                </div>
            </div>
        </div>
        <!-- END Apps Modal -->

        <!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
        <script src="layouts/beastn/assets/js/core/jquery.min.js"></script>
        <script src="layouts/beastn/assets/js/core/bootstrap.min.js"></script>
        <script src="layouts/beastn/assets/js/core/jquery.slimscroll.min.js"></script>
        <script src="layouts/beastn/assets/js/core/jquery.scrollLock.min.js"></script>
        <script src="layouts/beastn/assets/js/core/jquery.appear.min.js"></script>
        <script src="layouts/beastn/assets/js/core/jquery.countTo.min.js"></script>
        <script src="layouts/beastn/assets/js/core/jquery.placeholder.min.js"></script>
        <script src="layouts/beastn/assets/js/core/js.cookie.min.js"></script>
        <script src="layouts/beastn/assets/js/app.js"></script>

        <!-- Page Plugins -->
        <script src="layouts/beastn/assets/js/plugins/slick/slick.min.js"></script>
        <script src="layouts/beastn/assets/js/plugins/chartjs/Chart.min.js"></script>

        <!-- Page JS Code -->
        <script src="layouts/beastn/assets/js/pages/base_pages_dashboard.js"></script>
        <script>
            jQuery(function () {
                // Init page helpers (Slick Slider plugin)
                App.initHelpers('slick');
            });
        </script>
    </body>
</html>