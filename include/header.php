<div class="custom-header" id="#">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="<?=$url?>">
                    <img src="<?=$url?>img/logotest.png" class="logo-size">
                </a>
                <button class="navbar-toggler custom-toggler" id="hamburger" type="button" data-toggle="collapse" data-target="#navbarsExample05" aria-controls="navbarsExample05" aria-expanded="false" aria-label="Toggle navigation">
                  <i class="fas fa-bars fa-2x"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarsExample05">
                    <ul class="navbar-nav ml-auto custom-nav-text centeredContent">
                        <li class="nav-item">
                            <a href="<?=$url?>" class="nav-link">Featured Palettes</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=$url?>palettes" class="nav-link">Palettes</a>
                        </li>
                        <?php if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) { ?>
                            <li class="nav-item">
                                <a href="<?=$url?>saved" class="nav-link">Saved Palettes</a>
                            </li> 
                        <?php } else { }?> 

                        <li class="nav-item dropdown ">
                          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            More <i class="fas fa-chevron-down"></i>
                          </a>
                          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="<?=$url?>blog">Blog</a>
                            <a class="dropdown-item" href="<?=$url?>about">About</a>
                            <div class="dropdown-divider"></div>
                            <?php if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) { ?>
                              <a class="dropdown-item" href="<?=$url?>include/logout.php"><i class="fas fa-lock"></i> Logout</a>
                            <?php } else { ?>
                              <a class="dropdown-item" data-toggle="modal" data-target="#loginModal" style="cursor: pointer">Login</a>
                              <a class="dropdown-item" data-toggle="modal" data-target="#registerModal" style="cursor: pointer">Register</a>
                            <?php } ?>
                          </div>
                        </li>
                        <li class="nav-item">
                            <a href="<?=$url?>submit" class=" btn btn-theme-nav">Submit</a>
                        </li>
                        <?php if(isset($_SESSION['user_id']) || isset($_SESSION['logged_in'])) { ?> 
                            <li class="nav-item">
                                <a href="<?=$url?>profile/<?=$user['username']?>" class="nav-link" style="margin-top:-5px" data-toggle="tooltip" data-placement="bottom" title="<?=ucwords($user['username'])?>'s Profile"><i class="fas fa-user-circle fa-2x"></i></a>
                            </li>
                        <?php } else { }?>        
                    </ul>
                </div>
            </div>
        </nav>
    </div>


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

