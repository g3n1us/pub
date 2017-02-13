{extends file="s3:layouts/editor.tpl"}
{block name=bodyclasses}{{if $user}}logged_in navbar-fixed sidebar-nav fixed-nav  pace-done{{/if}}{/block}
{block name=body}
	<div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>
    <header class="navbar">
        <div class="container-fluid">
            <button class="navbar-toggler mobile-toggler hidden-lg-up" type="button">☰</button>
            <a class="navbar-brand" href="#"></a>
            <ul class="nav navbar-nav hidden-md-down pull-xs-left">
                <li class="nav-item b-r-1">
                    <a class="nav-link navbar-toggler layout-toggler" href="#">☰</a>
                </li>

            </ul>
            <form class="form-inline pull-xs-left p-x-2 hidden-md-down">
                <i class="fa fa-search"></i>
                <input class="form-control" type="text" placeholder="Are you looking for something?">
            </form>
            <ul class="nav navbar-nav pull-right hidden-md-down">
                <li class="nav-item dropdown p-r-2">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="img/flags/United-Kingdom.png" class="img-flag" alt="English" height="24">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-xs-center">
                            <strong>Choose language</strong>
                        </div>
                        <a class="dropdown-item" href="#">
                            <img src="img/flags/Poland.png" class="img-flag" alt="Polish" height="24">Polish</a>
                        <a class="dropdown-item" href="#">
                            <img src="img/flags/United-Kingdom.png" class="img-flag" alt="English" height="24">English</a>
                        <a class="dropdown-item" href="#">
                            <img src="img/flags/Spain.png" class="img-flag" alt="Español" height="24">Español</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="icon-bell"></i><span class="tag tag-pill tag-danger">5</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="icon-list"></i></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#"><i class="icon-location-pin"></i></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link nav-pill" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-settings"></i>
                        <span class="tag tag-pill tag-danger">9</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header text-xs-center">
                            <strong>Account</strong>
                        </div>
                        <a class="dropdown-item" href="#"><i class="fa fa-bell-o"></i> Updates<span class="tag tag-info">42</span></a>
                        <a class="dropdown-item" href="#"><i class="fa fa-envelope-o"></i> Messages<span class="tag tag-success">42</span></a>
                        <a class="dropdown-item" href="#"><i class="fa fa-tasks"></i> Tasks<span class="tag tag-danger">42</span></a>
                        <a class="dropdown-item" href="#"><i class="fa fa-comments"></i> Comments<span class="tag tag-warning">42</span></a>
                        <div class="dropdown-header text-xs-center">
                            <strong>Settings</strong>
                        </div>
                        <a class="dropdown-item" href="#"><i class="fa fa-user"></i> Profile</a>
                        <a class="dropdown-item" href="#"><i class="fa fa-wrench"></i> Settings</a>
                        <a class="dropdown-item" href="#"><i class="fa fa-usd"></i> Payments<span class="tag tag-default">42</span></a>
                        <a class="dropdown-item" href="#"><i class="fa fa-file"></i> Projects<span class="tag tag-primary">42</span></a>
                        <div class="divider"></div>
                        <a class="dropdown-item" href="#"><i class="fa fa-shield"></i> Lock Account</a>
                        <a class="dropdown-item" href="#"><i class="fa fa-lock"></i> Logout</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link navbar-toggler aside-toggle" href="#">☰</a>
                </li>

            </ul>
        </div>
    </header>
    <div class="sidebar">

        <div class="sidebar-header">
            <img src="img/avatars/8.jpg" class="img-avatar" alt="Avatar">
            <div>
                <strong>JOHN DOE</strong>
            </div>
            <div class="text-muted">
                <small>Founder &amp; CEO</small>
            </div>
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <button type="button" class="btn btn-link">
                    <i class="icon-settings"></i>
                </button>
                <button type="button" class="btn btn-link">
                    <i class="icon-speech"></i>
                    <span class="tag tag-warning tag-pill">5</span>
                </button>
                <button type="button" class="btn btn-link">
                    <i class="icon-user"></i>
                </button>
            </div>
        </div>

        <nav class="sidebar-nav open">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.html"><i class="icon-speedometer"></i> Dashboard <span class="tag tag-info">NEW</span></a>
                </li>

                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-puzzle"></i> Components</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link" href="components-buttons.html"><i class="icon-puzzle"></i> Buttons</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="components-social-buttons.html"><i class="icon-puzzle"></i> Social Buttons</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="components-cards.html"><i class="icon-puzzle"></i> Cards</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="components-forms.html"><i class="icon-puzzle"></i> Forms</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="components-switches.html"><i class="icon-puzzle"></i> Switches</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="components-tables.html"><i class="icon-puzzle"></i> Tables</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-dropdown open">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-star"></i> Icons</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link" href="icons-font-awesome.html"><i class="icon-star"></i> Font Awesome</a>
                        </li>
                        <li class="nav-item open">
                            <a class="nav-link active" href="icons-simple-line-icons.html"><i class="icon-star"></i> Simple Line Icons</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-energy"></i> Plugins</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link" href="plugins-calendar.html"><i class="icon-calendar"></i> Calendar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="plugins-notifications.html"><i class="icon-info"></i> Notifications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="plugins-sliders.html"><i class="icon-equalizer"></i> Sliders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="plugins-tables.html"><i class="icon-list"></i> Tables</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="forms.html"><i class="icon-note"></i> Forms <span class="tag tag-danger">NEW</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="widgets.html"><i class="icon-calculator"></i> Widgets <span class="tag tag-info">NEW</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="charts.html"><i class="icon-pie-chart"></i> Charts</a>
                </li>
                <li class="divider"></li>
                <li class="nav-title">Extras</li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-star"></i> Pages</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item">
                            <a class="nav-link" href="pages-login.html" target="_top"><i class="icon-star"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages-register.html" target="_top"><i class="icon-star"></i> Register</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages-404.html" target="_top"><i class="icon-star"></i> Error 404</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="pages-500.html" target="_top"><i class="icon-star"></i> Error 500</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-layers"></i> UI Kits</a>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-speech"></i> Invoicing</a>
                            <ul class="nav-dropdown-items">
                                <li class="nav-item">
                                    <a class="nav-link" href="UIkits-invoicing-invoice.html"><i class="icon-speech"></i> Invoice</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav-dropdown-items">
                        <li class="nav-item nav-dropdown">
                            <a class="nav-link nav-dropdown-toggle" href="#"><i class="icon-speech"></i> Email</a>
                            <ul class="nav-dropdown-items">
                                <li class="nav-item">
                                    <a class="nav-link" href="UIkits-email-inbox.html"><i class="icon-speech"></i> Inbox</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="UIkits-email-message.html"><i class="icon-speech"></i> Message</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="UIkits-email-compose.html"><i class="icon-speech"></i> Compose</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
    </div>
    <!-- Main content -->
    <main class="main">

        <!-- Breadcrumb -->
        <ol class="breadcrumb m-b-0">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item"><a href="#">Admin</a>
            </li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>

        <div class="container-fluid">
            <div class="animated fadeIn">
                <div class="card card-default">
                    <div class="card-header">
                        <i class="fa fa-picture-o"></i> Simple Line Icons
                    </div>
                    <div class="card-block">
                        <div class="row text-xs-center">
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-user icons h4 d-block m-t-2"></i>icon-user
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-people icons h4 d-block m-t-2"></i>icon-people
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-user-female icons h4 d-block m-t-2"></i>icon-user-female
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-user-follow icons h4 d-block m-t-2"></i>icon-user-follow
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-user-following icons h4 d-block m-t-2"></i>icon-user-following
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-user-unfollow icons h4 d-block m-t-2"></i>icon-user-unfollow
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-login icons h4 d-block m-t-2"></i>icon-login
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-logout icons h4 d-block m-t-2"></i>icon-logout
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-emotsmile icons h4 d-block m-t-2"></i>icon-emotsmile
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-phone icons h4 d-block m-t-2"></i>icon-phone
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-call-end icons h4 d-block m-t-2"></i>icon-call-end
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-call-in icons h4 d-block m-t-2"></i>icon-call-in
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-call-out icons h4 d-block m-t-2"></i>icon-call-out
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-map icons h4 d-block m-t-2"></i>icon-map
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-location-pin icons h4 d-block m-t-2"></i>icon-location-pin
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-direction icons h4 d-block m-t-2"></i>icon-direction
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-directions icons h4 d-block m-t-2"></i>icon-directions
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-compass icons h4 d-block m-t-2"></i>icon-compass
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-layers icons h4 d-block m-t-2"></i>icon-layers
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-menu icons h4 d-block m-t-2"></i>icon-menu
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-list icons h4 d-block m-t-2"></i>icon-list
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-options-vertical icons h4 d-block m-t-2"></i>icon-options-vertical
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-options icons h4 d-block m-t-2"></i>icon-options
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-arrow-down icons h4 d-block m-t-2"></i>icon-arrow-down
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-arrow-left icons h4 d-block m-t-2"></i>icon-arrow-left
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-arrow-right icons h4 d-block m-t-2"></i>icon-arrow-right
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-arrow-up icons h4 d-block m-t-2"></i>icon-arrow-up
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-arrow-up-circle icons h4 d-block m-t-2"></i>icon-arrow-up-circle
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-arrow-left-circle icons h4 d-block m-t-2"></i>icon-arrow-left-circle
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-arrow-right-circle icons h4 d-block m-t-2"></i>icon-arrow-right-circle
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-arrow-down-circle icons h4 d-block m-t-2"></i>icon-arrow-down-circle
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-check icons h4 d-block m-t-2"></i>icon-check
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-clock icons h4 d-block m-t-2"></i>icon-clock
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-plus icons h4 d-block m-t-2"></i>icon-plus
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-close icons h4 d-block m-t-2"></i>icon-close
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-trophy icons h4 d-block m-t-2"></i>icon-trophy
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-screen-smartphone icons h4 d-block m-t-2"></i>icon-screen-smartphone
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-screen-desktop icons h4 d-block m-t-2"></i>icon-screen-desktop
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-plane icons h4 d-block m-t-2"></i>icon-plane
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-notebook icons h4 d-block m-t-2"></i>icon-notebook
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-mustache icons h4 d-block m-t-2"></i>icon-mustache
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-mouse icons h4 d-block m-t-2"></i>icon-mouse
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-magnet icons h4 d-block m-t-2"></i>icon-magnet
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-energy icons h4 d-block m-t-2"></i>icon-energy
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-disc icons h4 d-block m-t-2"></i>icon-disc
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-cursor icons h4 d-block m-t-2"></i>icon-cursor
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-cursor-move icons h4 d-block m-t-2"></i>icon-cursor-move
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-crop icons h4 d-block m-t-2"></i>icon-crop
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-chemistry icons h4 d-block m-t-2"></i>icon-chemistry
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-speedometer icons h4 d-block m-t-2"></i>icon-speedometer
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-shield icons h4 d-block m-t-2"></i>icon-shield
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-screen-tablet icons h4 d-block m-t-2"></i>icon-screen-tablet
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-magic-wand icons h4 d-block m-t-2"></i>icon-magic-wand
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-hourglass icons h4 d-block m-t-2"></i>icon-hourglass
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-graduation icons h4 d-block m-t-2"></i>icon-graduation
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-ghost icons h4 d-block m-t-2"></i>icon-ghost
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-game-controller icons h4 d-block m-t-2"></i>icon-game-controller
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-fire icons h4 d-block m-t-2"></i>icon-fire
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-eyeglass icons h4 d-block m-t-2"></i>icon-eyeglass
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-envelope-open icons h4 d-block m-t-2"></i>icon-envelope-open
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-envelope-letter icons h4 d-block m-t-2"></i>icon-envelope-letter
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-bell icons h4 d-block m-t-2"></i>icon-bell
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-badge icons h4 d-block m-t-2"></i>icon-badge
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-anchor icons h4 d-block m-t-2"></i>icon-anchor
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-wallet icons h4 d-block m-t-2"></i>icon-wallet
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-vector icons h4 d-block m-t-2"></i>icon-vector
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-speech icons h4 d-block m-t-2"></i>icon-speech
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-puzzle icons h4 d-block m-t-2"></i>icon-puzzle
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-printer icons h4 d-block m-t-2"></i>icon-printer
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-present icons h4 d-block m-t-2"></i>icon-present
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-playlist icons h4 d-block m-t-2"></i>icon-playlist
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-pin icons h4 d-block m-t-2"></i>icon-pin
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-picture icons h4 d-block m-t-2"></i>icon-picture
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-handbag icons h4 d-block m-t-2"></i>icon-handbag
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-globe-alt icons h4 d-block m-t-2"></i>icon-globe-alt
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-globe icons h4 d-block m-t-2"></i>icon-globe
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-folder-alt icons h4 d-block m-t-2"></i>icon-folder-alt
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-folder icons h4 d-block m-t-2"></i>icon-folder
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-film icons h4 d-block m-t-2"></i>icon-film
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-feed icons h4 d-block m-t-2"></i>icon-feed
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-drop icons h4 d-block m-t-2"></i>icon-drop
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-drawer icons h4 d-block m-t-2"></i>icon-drawer
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-docs icons h4 d-block m-t-2"></i>icon-docs
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-doc icons h4 d-block m-t-2"></i>icon-doc
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-diamond icons h4 d-block m-t-2"></i>icon-diamond
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-cup icons h4 d-block m-t-2"></i>icon-cup
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-calculator icons h4 d-block m-t-2"></i>icon-calculator
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-bubbles icons h4 d-block m-t-2"></i>icon-bubbles
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-briefcase icons h4 d-block m-t-2"></i>icon-briefcase
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-book-open icons h4 d-block m-t-2"></i>icon-book-open
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-basket-loaded icons h4 d-block m-t-2"></i>icon-basket-loaded
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-basket icons h4 d-block m-t-2"></i>icon-basket
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-bag icons h4 d-block m-t-2"></i>icon-bag
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-action-undo icons h4 d-block m-t-2"></i>icon-action-undo
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-action-redo icons h4 d-block m-t-2"></i>icon-action-redo
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-wrench icons h4 d-block m-t-2"></i>icon-wrench
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-umbrella icons h4 d-block m-t-2"></i>icon-umbrella
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-trash icons h4 d-block m-t-2"></i>icon-trash
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-tag icons h4 d-block m-t-2"></i>icon-tag
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-support icons h4 d-block m-t-2"></i>icon-support
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-frame icons h4 d-block m-t-2"></i>icon-frame
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-size-fullscreen icons h4 d-block m-t-2"></i>icon-size-fullscreen
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-size-actual icons h4 d-block m-t-2"></i>icon-size-actual
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-shuffle icons h4 d-block m-t-2"></i>icon-shuffle
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-share-alt icons h4 d-block m-t-2"></i>icon-share-alt
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-share icons h4 d-block m-t-2"></i>icon-share
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-rocket icons h4 d-block m-t-2"></i>icon-rocket
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-question icons h4 d-block m-t-2"></i>icon-question
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-pie-chart icons h4 d-block m-t-2"></i>icon-pie-chart
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-pencil icons h4 d-block m-t-2"></i>icon-pencil
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-note icons h4 d-block m-t-2"></i>icon-note
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-loop icons h4 d-block m-t-2"></i>icon-loop
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-home icons h4 d-block m-t-2"></i>icon-home
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-grid icons h4 d-block m-t-2"></i>icon-grid
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-graph icons h4 d-block m-t-2"></i>icon-graph
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-microphone icons h4 d-block m-t-2"></i>icon-microphone
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-music-tone-alt icons h4 d-block m-t-2"></i>icon-music-tone-alt
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-music-tone icons h4 d-block m-t-2"></i>icon-music-tone
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-earphones-alt icons h4 d-block m-t-2"></i>icon-earphones-alt
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-earphones icons h4 d-block m-t-2"></i>icon-earphones
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-equalizer icons h4 d-block m-t-2"></i>icon-equalizer
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-like icons h4 d-block m-t-2"></i>icon-like
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-dislike icons h4 d-block m-t-2"></i>icon-dislike
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-control-start icons h4 d-block m-t-2"></i>icon-control-start
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-control-rewind icons h4 d-block m-t-2"></i>icon-control-rewind
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-control-play icons h4 d-block m-t-2"></i>icon-control-play
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-control-pause icons h4 d-block m-t-2"></i>icon-control-pause
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-control-forward icons h4 d-block m-t-2"></i>icon-control-forward
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-control-end icons h4 d-block m-t-2"></i>icon-control-end
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-volume-1 icons h4 d-block m-t-2"></i>icon-volume-1
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-volume-2 icons h4 d-block m-t-2"></i>icon-volume-2
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-volume-off icons h4 d-block m-t-2"></i>icon-volume-off
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-calendar icons h4 d-block m-t-2"></i>icon-calendar
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-bulb icons h4 d-block m-t-2"></i>icon-bulb
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-chart icons h4 d-block m-t-2"></i>icon-chart
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-ban icons h4 d-block m-t-2"></i>icon-ban
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-bubble icons h4 d-block m-t-2"></i>icon-bubble
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-camrecorder icons h4 d-block m-t-2"></i>icon-camrecorder
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-camera icons h4 d-block m-t-2"></i>icon-camera
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-cloud-download icons h4 d-block m-t-2"></i>icon-cloud-download
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-cloud-upload icons h4 d-block m-t-2"></i>icon-cloud-upload
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-envelope icons h4 d-block m-t-2"></i>icon-envelope
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-eye icons h4 d-block m-t-2"></i>icon-eye
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-flag icons h4 d-block m-t-2"></i>icon-flag
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-heart icons h4 d-block m-t-2"></i>icon-heart
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-info icons h4 d-block m-t-2"></i>icon-info
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-key icons h4 d-block m-t-2"></i>icon-key
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-link icons h4 d-block m-t-2"></i>icon-link
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-lock icons h4 d-block m-t-2"></i>icon-lock
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-lock-open icons h4 d-block m-t-2"></i>icon-lock-open
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-magnifier icons h4 d-block m-t-2"></i>icon-magnifier
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-magnifier-add icons h4 d-block m-t-2"></i>icon-magnifier-add
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-magnifier-remove icons h4 d-block m-t-2"></i>icon-magnifier-remove
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-paper-clip icons h4 d-block m-t-2"></i>icon-paper-clip
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-paper-plane icons h4 d-block m-t-2"></i>icon-paper-plane
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-power icons h4 d-block m-t-2"></i>icon-power
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-refresh icons h4 d-block m-t-2"></i>icon-refresh
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-reload icons h4 d-block m-t-2"></i>icon-reload
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-settings icons h4 d-block m-t-2"></i>icon-settings
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-star icons h4 d-block m-t-2"></i>icon-star
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-symbol-female icons h4 d-block m-t-2"></i>icon-symbol-female
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-symbol-male icons h4 d-block m-t-2"></i>icon-symbol-male
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-target icons h4 d-block m-t-2"></i>icon-target
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-credit-card icons h4 d-block m-t-2"></i>icon-credit-card
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-paypal icons h4 d-block m-t-2"></i>icon-paypal
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-tumblr icons h4 d-block m-t-2"></i>icon-social-tumblr
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-twitter icons h4 d-block m-t-2"></i>icon-social-twitter
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-facebook icons h4 d-block m-t-2"></i>icon-social-facebook
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-instagram icons h4 d-block m-t-2"></i>icon-social-instagram
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-linkedin icons h4 d-block m-t-2"></i>icon-social-linkedin
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-pinterest icons h4 d-block m-t-2"></i>icon-social-pinterest
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-github icons h4 d-block m-t-2"></i>icon-social-github
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-gplus icons h4 d-block m-t-2"></i>icon-social-gplus
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-reddit icons h4 d-block m-t-2"></i>icon-social-reddit
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-skype icons h4 d-block m-t-2"></i>icon-social-skype
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-dribbble icons h4 d-block m-t-2"></i>icon-social-dribbble
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-behance icons h4 d-block m-t-2"></i>icon-social-behance
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-foursqare icons h4 d-block m-t-2"></i>icon-social-foursqare
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-soundcloud icons h4 d-block m-t-2"></i>icon-social-soundcloud
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-spotify icons h4 d-block m-t-2"></i>icon-social-spotify
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-stumbleupon icons h4 d-block m-t-2"></i>icon-social-stumbleupon
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-youtube icons h4 d-block m-t-2"></i>icon-social-youtube
                            </div>
                            <div class="col-xs-6 col-sm-4 col-md-3">
                                <i class="icon-social-dropbox icons h4 d-block m-t-2"></i>icon-social-dropbox
                            </div>
                        </div>
                        <!--/.row-->
                    </div>
                </div>
            </div>
        </div>
        <!-- /.conainer-fluid -->
    </main>

    <aside class="aside-menu">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#timeline" role="tab"><i class="icon-list"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#messages" role="tab"><i class="icon-speech"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#settings" role="tab"><i class="icon-settings"></i></a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="timeline" role="tabpanel">
                <div class="callout m-a-0 p-y-h text-muted text-xs-center bg-faded text-uppercase">
                    <small><b>Today</b>
                    </small>
                </div>
                <hr class="transparent m-x-1 m-y-0">
                <div class="callout callout-warning m-a-0 p-y-1">
                    <div class="avatar pull-xs-right">
                        <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                    </div>
                    <div>Meeting with
                        <strong>Lucas</strong>
                    </div>
                    <small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; 1 - 3pm</small>
                    <small class="text-muted"><i class="icon-location-pin"></i>&nbsp; Palo Alto, CA</small>
                </div>
                <hr class="m-x-1 m-y-0">
                <div class="callout callout-info m-a-0 p-y-1">
                    <div class="avatar pull-xs-right">
                        <img src="img/avatars/4.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                    </div>
                    <div>Skype with
                        <strong>Megan</strong>
                    </div>
                    <small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; 4 - 5pm</small>
                    <small class="text-muted"><i class="icon-social-skype"></i>&nbsp; On-line</small>
                </div>
                <hr class="transparent m-x-1 m-y-0">
                <div class="callout m-a-0 p-y-h text-muted text-xs-center bg-faded text-uppercase">
                    <small><b>Tomorrow</b>
                    </small>
                </div>
                <hr class="transparent m-x-1 m-y-0">
                <div class="callout callout-danger m-a-0 p-y-1">
                    <div>New UI Project -
                        <strong>deadline</strong>
                    </div>
                    <small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; 10 - 11pm</small>
                    <small class="text-muted"><i class="icon-home"></i>&nbsp; creativeLabs HQ</small>
                    <div class="avatars-stack m-t-h">
                        <div class="avatar avatar-xs">
                            <img src="img/avatars/2.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                        <div class="avatar avatar-xs">
                            <img src="img/avatars/3.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                        <div class="avatar avatar-xs">
                            <img src="img/avatars/4.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                        <div class="avatar avatar-xs">
                            <img src="img/avatars/5.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                        <div class="avatar avatar-xs">
                            <img src="img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                    </div>
                </div>
                <hr class="m-x-1 m-y-0">
                <div class="callout callout-success m-a-0 p-y-1">
                    <div>
                        <strong>#10 Startups.Garden</strong>Meetup</div>
                    <small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; 1 - 3pm</small>
                    <small class="text-muted"><i class="icon-location-pin"></i>&nbsp; Palo Alto, CA</small>
                </div>
                <hr class="m-x-1 m-y-0">
                <div class="callout callout-primary m-a-0 p-y-1">
                    <div>
                        <strong>Team meeting</strong>
                    </div>
                    <small class="text-muted m-r-1"><i class="icon-calendar"></i>&nbsp; 4 - 6pm</small>
                    <small class="text-muted"><i class="icon-home"></i>&nbsp; creativeLabs HQ</small>
                    <div class="avatars-stack m-t-h">
                        <div class="avatar avatar-xs">
                            <img src="img/avatars/2.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                        <div class="avatar avatar-xs">
                            <img src="img/avatars/3.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                        <div class="avatar avatar-xs">
                            <img src="img/avatars/4.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                        <div class="avatar avatar-xs">
                            <img src="img/avatars/5.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                        <div class="avatar avatar-xs">
                            <img src="img/avatars/6.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                        <div class="avatar avatar-xs">
                            <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                        <div class="avatar avatar-xs">
                            <img src="img/avatars/8.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                        </div>
                    </div>
                </div>
                <hr class="m-x-1 m-y-0">
            </div>
            <div class="tab-pane p-a-1" id="messages" role="tabpanel">
                <div class="message">
                    <div class="p-y-1 p-b-3 m-r-1 pull-left">
                        <div class="avatar">
                            <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            <span class="avatar-status tag-success"></span>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted">Lukasz Holeczek</small>
                        <small class="text-muted pull-right m-t-q">1:52 PM</small>
                    </div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                    <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
                <hr>
                <div class="message">
                    <div class="p-y-1 p-b-3 m-r-1 pull-left">
                        <div class="avatar">
                            <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            <span class="avatar-status tag-success"></span>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted">Lukasz Holeczek</small>
                        <small class="text-muted pull-right m-t-q">1:52 PM</small>
                    </div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                    <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
                <hr>
                <div class="message">
                    <div class="p-y-1 p-b-3 m-r-1 pull-left">
                        <div class="avatar">
                            <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            <span class="avatar-status tag-success"></span>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted">Lukasz Holeczek</small>
                        <small class="text-muted pull-right m-t-q">1:52 PM</small>
                    </div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                    <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
                <hr>
                <div class="message">
                    <div class="p-y-1 p-b-3 m-r-1 pull-left">
                        <div class="avatar">
                            <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            <span class="avatar-status tag-success"></span>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted">Lukasz Holeczek</small>
                        <small class="text-muted pull-right m-t-q">1:52 PM</small>
                    </div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                    <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
                <hr>
                <div class="message">
                    <div class="p-y-1 p-b-3 m-r-1 pull-left">
                        <div class="avatar">
                            <img src="img/avatars/7.jpg" class="img-avatar" alt="admin@bootstrapmaster.com">
                            <span class="avatar-status tag-success"></span>
                        </div>
                    </div>
                    <div>
                        <small class="text-muted">Lukasz Holeczek</small>
                        <small class="text-muted pull-right m-t-q">1:52 PM</small>
                    </div>
                    <div class="text-truncate font-weight-bold">Lorem ipsum dolor sit amet</div>
                    <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt...</small>
                </div>
            </div>
            <div class="tab-pane p-a-1" id="settings" role="tabpanel">
                <h6>Settings</h6>
                <div class="aside-options">
                    <div class="clearfix m-t-2">
                        <small><b>Option 1</b>
                        </small>
                        <label class="switch switch-text switch-pill switch-success switch-sm pull-right">
                            <input type="checkbox" class="switch-input" checked="">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                    <div>
                        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small>
                    </div>
                </div>
                <div class="aside-options">
                    <div class="clearfix m-t-1">
                        <small><b>Option 2</b>
                        </small>
                        <label class="switch switch-text switch-pill switch-success switch-sm pull-right">
                            <input type="checkbox" class="switch-input">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                    <div>
                        <small class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</small>
                    </div>
                </div>
                <div class="aside-options">
                    <div class="clearfix m-t-1">
                        <small><b>Option 3</b>
                        </small>
                        <label class="switch switch-text switch-pill switch-success switch-sm pull-right">
                            <input type="checkbox" class="switch-input">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>
                <div class="aside-options">
                    <div class="clearfix m-t-1">
                        <small><b>Option 4</b>
                        </small>
                        <label class="switch switch-text switch-pill switch-success switch-sm pull-right">
                            <input type="checkbox" class="switch-input" checked="">
                            <span class="switch-label" data-on="On" data-off="Off"></span>
                            <span class="switch-handle"></span>
                        </label>
                    </div>
                </div>
                <hr>
                <h6>System Utilization</h6>
                <div class="text-uppercase m-b-q m-t-2">
                    <small><b>CPU Usage</b>
                    </small>
                </div>
                <progress class="progress progress-xs progress-info m-a-0" value="25" max="100">25%</progress>
                <small class="text-muted">348 Processes. 1/4 Cores.</small>
                <div class="text-uppercase m-b-q m-t-h">
                    <small><b>Memory Usage</b>
                    </small>
                </div>
                <progress class="progress progress-xs progress-warning m-a-0" value="70" max="100">70%</progress>
                <small class="text-muted">11444GB/16384MB</small>
                <div class="text-uppercase m-b-q m-t-h">
                    <small><b>SSD 1 Usage</b>
                    </small>
                </div>
                <progress class="progress progress-xs progress-danger m-a-0" value="95" max="100">95%</progress>
                <small class="text-muted">243GB/256GB</small>
                <div class="text-uppercase m-b-q m-t-h">
                    <small><b>SSD 2 Usage</b>
                    </small>
                </div>
                <progress class="progress progress-xs progress-success m-a-0" value="10" max="100">10%</progress>
                <small class="text-muted">25GB/256GB</small>
            </div>
        </div>
    </aside>

    <footer class="footer">
        <span class="text-left">
            <a href="http://bootstrapmaster.com">Real</a> © 2016 creativeLabs.
        </span>
        <span class="pull-right">
            Powered by <a href="http://genesisui.com">GenesisUI</a>
        </span>
    </footer>


{/block}