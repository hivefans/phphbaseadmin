<!DOCTYPE HTML>
<html lang="<?php echo $common_lang_set?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <link rel="icon" type="image/ico" href="favicon.ico">
    <title>PhpHbaseAdmin - Login</title>
    <link rel="stylesheet" href="<?php echo $this->config->base_url();?>css/login.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet'>
    <!-- jQuery framework -->
        <script src="<?php echo $this->config->base_url();?>js/jquery-1.8.3.min.js"></script>
    <!-- validation -->
        <script src="<?php echo $this->config->base_url();?>js/jquery.validate.min.js"></script>
    <script type="text/javascript">
        (function(a){a.fn.vAlign=function(){return this.each(function(){var b=a(this).height(),c=a(this).outerHeight(),b=(b+(c-b))/2;a(this).css("margin-top","-"+b+"px");a(this).css("top","50%");a(this).css("position","absolute")})}})(jQuery);(function(a){a.fn.hAlign=function(){return this.each(function(){var b=a(this).width(),c=a(this).outerWidth(),b=(b+(c-b))/2;a(this).css("margin-left","-"+b+"px");a(this).css("left","50%");a(this).css("position","absolute")})}})(jQuery);
        $(document).ready(function() {
            if($('#login-wrapper').length) {
                $("#login-wrapper").vAlign().hAlign()
            };
            if($('#login-validate').length) {
                $('#login-validate').validate({
                    onkeyup: false,
                    errorClass: 'error',
                    rules: {
                        login_name: { required: true },
                        login_password: { required: true }
                    }
                })
            }
            if($('#forgot-validate').length) {
                $('#forgot-validate').validate({
                    onkeyup: false,
                    errorClass: 'error',
                    rules: {
                        forgot_email: { required: true, email: true }
                    }
                })
            }
            $('#pass_login').click(function() {
                $('.panel:visible').slideUp('200',function() {
                    $('.panel').not($(this)).slideDown('200');
                });
                $(this).children('span').toggle();
            });
        });
    </script>
</head>
<body>
    <div id="login-wrapper" class="clearfix">
        <div class="main-col">
            <img src="<?php echo $this->config->base_url();?>img/hbase.png" alt="" class="logo_img" />
            <div class="panel">
                <p class="heading_main">Account Login</p>
                <form id="login-validate" action="<?php echo $this->config->base_url();?>index.php/manage/validate_user" method="post">
                    <label for="login_name">Login</label>
                    <input type="text" id="user_name" name="user_name" value="" />
                    <label for="login_password">Password</label>
                    <input type="password" id="password" name="password" value="" />
                   <!-- <label for="login_remember" class="checkbox"><input type="checkbox" id="login_remember" name="login_remember" /> Remember me</label>-->
                    <div class="submit_sect">
                        <button type="submit" class="btn btn-beoro-3">Login</button>
                    </div>
                </form>
            </div>
            <div class="panel" style="display:none">
                <p class="heading_main">Can't sign in?</p>
                <form id="forgot-validate" method="post">
                    <label for="forgot_email">Your email adress</label>
                    <input type="text" id="forgot_email" name="forgot_email" />
                    <div class="submit_sect">
                        <button type="submit" class="btn btn-beoro-3">Request New Password</button>
                    </div>
                </form>
            </div>
        </div>
        <!--<div class="login_links">
            <a href="javascript:void(0)" id="pass_login"><span>Forgot password?</span><span style="display:none">Account login</span></a>
        </div>-->
    </div>
   
</body>
</html>