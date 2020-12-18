<div class="custom-header" id="#">
        <nav class="navbar navbar-expand-lg navbar-fixed-top" >
            <div class="container-fluid">
                <a class="navbar-brand" href="<?=$url?>">
                    <img src="<?=$url?>img/logotest.png" class="logo-size d-md-none d-lg-none d-xl-block d-sm-block">
                    <img src="<?=$url?>img/biglogo.png" class="logo-size-small d-md-block d-lg-block d-xl-none d-sm-none d-none">
                </a>
                <button class="navbar-toggler custom-toggler" id="hamburger" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                  <i class="fas fa-bars fa-2x"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarsExample05">
                    <ul class="navbar-nav custom-nav-text">
                        <li class="nav-item">
                            <a href="<?=$url?>" class="nav-link ">Featured Palettes</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=$url?>palettes" class="nav-link">Palettes</a>
                        </li>
                        <?php if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) { ?>
                            <li class="nav-item">
                                <a href="<?=$url?>saved" class="nav-link">Saved Palettes</a>
                            </li> 
                        <?php } ?>
                        <li class="nav-item">
                            <a href="<?=$url?>submit" class="nav-link">Submit</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-auto custom-nav-text height-nav">
                        <?php if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) { ?>
                            <?php
                            $userrankid = $user['rank'];
                            $rankPullUser = $pdo->prepare("SELECT * FROM rank WHERE id = '$userrankid'");
                            $rankPullUser ->execute();
                            $rankuser = $rankPullUser ->fetch(PDO::FETCH_ASSOC);

                            $loggedinDataPull = $pdo->prepare("SELECT * FROM user_profile WHERE uid = $uid");
                            $loggedinDataPull->execute();
                            $loggedinData = $loggedinDataPull->fetch(PDO::FETCH_ASSOC);

                            ?>
                            <li class="nav-item dropdown ">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div href="<?=$url?>profile/<?=$user['username']?>" style="margin-top:-5px" data-toggle="tooltip" data-placement="left" title="<?=ucwords($user['username'])?>'s Profile">
                                        <?php if($loggedinData['minecraft_ign'] == null) { ?>
                                            <img src="<?=$url?>img/default.jpg" class="profile-pic">
                                        <?php } else { ?>
                                            <img src="<?=$url?>include/face.php?u=<?=$loggedinData['minecraft_ign']?>&s=48&v=front" class="profile-pic" onerror="this.src='<?=$url?>img/default.jpg'">
                                        <?php } ?>
                                        <span class="d-md-none usernameMobile" style=""><?=ucwords($user['username'])?></span>
                                    </div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item" href="<?=$url?>profile/<?=$user['username']?>"><i class="fas fa-user"></i> My Profile <span class="role-pill" style="background:<?=$rankuser['rank_color']?>; float:right"><?=ucwords($rankuser['rank_name'])?></span></a>
                                    <a class="dropdown-item" href="<?=$url?>saved"><i class="fas fa-save"></i> Saved Palettes</a>
                                    <?php if ($user['rank'] >= 90) {?>
                                    <a class="dropdown-item" href="<?=$url?>dashboard"><i class="fas fa-user-shield"></i> Admin Dashboard</a>
                                    <?php } ?>
                                    <div class="dropdown-divider"></div>
                                    <?php if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) { ?>
                                        <a class="dropdown-item" href="<?=$url?>include/logout.php"><i class="fas fa-lock"></i> Logout</a>
                                    <?php } else { ?>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#loginModal" style="cursor: pointer">Login</a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#registerModal" style="cursor: pointer">Register</a>
                                    <?php } ?>
                                </div>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="modal" data-target="#loginModal" style="cursor: pointer">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="modal" data-target="#registerModal" style="cursor: pointer">Register</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
<div style="height: 65px"></div>


    <!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div align="right" style="padding: 10px">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 35px">
                <div align="center">
                    <h3 class="medium-title" id="loginModalTitle">Join Block Palettes</h3>
                    <p class="text">Sign up to share & collect block palettes</p>
                </div>
                <form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
                    <div class="form-group">
                        <input type="text" id="username" name="username" placeholder="Username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email Address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" class="form-control" required>
                    </div>
                    <button class="btn btn-theme btn-block" type="submit" name="register"><b>Create your free account</b></button>
                </form>
            </div>
            <div class="modal-footer" align="center" style="display: block">
                <p>Already registered? <a href="" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#loginModal" style="cursor: pointer">Sign in</a></p>
            </div>
        </div>
    </div>
</div>


<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div align="right" style="padding: 10px">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 35px" align="center">
                <div align="center">
                    <h3 class="medium-title" id="loginModalTitle">Welcome Back!</h3>
                    <p class="text">Sign into your account here.</p>
                </div>
                <form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
                    <div class="form-group">
                        <input type="text" name="email" placeholder="Email Address" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" class="form-control" required>
                    </div>
                    <div class="form-group" style="padding-left:20px"> 
                        <input class="form-check-input" type="checkbox" name="cookie" id="cookie" value="1">
                        <label class="form-check-label" for="cookie">
                            Keep me logged in.
                        </label>
                    </div>
                    <button class="btn btn-theme btn-block" type="submit" name="login"><b>Sign in</b></button>

                </form>
            </div>
            <div class="modal-footer" align="center" style="display: block">
                <p>Forgot password? <a href="" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#resetModal" style="cursor: pointer">Reset</a></p>
                <p>Don't have an account? <a href="" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#registerModal" style="cursor: pointer">Sign up</a></p>
            </div>
        </div>
    </div>
</div>


<!-- Reset Modal -->
<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="resetModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div align="right" style="padding: 10px">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 35px">
                <div align="center">
                    <h3 class="medium-title" id="resetModalTitle">Reset Password</h3>
                    <p class="text">Enter your email/username below to have a<br>reset link sent via email.</p>
                </div>
                <form action="<?=$_SERVER['PHP_SELF'];?>" method="post">
                    <div class="form-group">
                        <input type="text" name="email" placeholder="Email Address" class="form-control" required>
                    </div>
                    <button class="btn btn-theme btn-block" type="submit" name="reset-password"><b>Send reset link</b></button>
                </form>
            </div>
            <div class="modal-footer" align="center" style="display: block">
                <p>Back to <a href="" data-dismiss="modal" aria-label="Close" data-toggle="modal" data-target="#loginModal" style="cursor: pointer">login</a></p>
            </div>
        </div>
    </div>
</div>

<script>

$("input#username").on({
  keydown: function(e) {
    if (e.which === 32)
      return false;
  },
  change: function() {
    this.value = this.value.replace(/\s/g, "");
  }
});
</script>

