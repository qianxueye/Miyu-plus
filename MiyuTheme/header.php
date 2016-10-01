<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="<?php $this->options->charset(); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
        <?php if($this->is('page') || $this->is('post'))
            if($this->fields->noindex == "no") :?>
        <meta name="robots" content="noindex,nofollow" />
        <?php endif; ?>
        <meta name="theme-color" content="#777" id="themecolor">
        <meta name="msapplication-navbutton-color" content="#777" id="wpthemecolor">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="icon" href="<?php $this->options->themeUrl('img/favicon.ico'); ?>" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php $this->options->themeUrl('img/favicon.ico'); ?>" type="image/x-icon" />
        <link rel="stylesheet" href="<?php $this->options->themeUrl('css/style.min.css'); ?>">
        <link rel="alternate stylesheet" href="<?php if(isset($_COOKIE["color"])){
            if ($_COOKIE["color"] == "Light") $this->options->themeUrl('css/Dark.css');
            if ($_COOKIE["color"] == "Dark") $this->options->themeUrl('css/Light.css');
            }elseif($this->options->themecolorstyle == "LightMode") $this->options->themeUrl('css/Dark.css');
            else  $this->options->themeUrl('css/Light.css');
            ?>">
        <link rel="stylesheet" href="<?php if(isset($_COOKIE["color"])){
            if ($_COOKIE["color"] == "Light") $this->options->themeUrl('css/Light.css');
            else $this->options->themeUrl('css/Dark.css');}elseif($this->options->themecolorstyle == "LightMode")
                $this->options->themeUrl('css/Light.css');else  $this->options->themeUrl('css/Dark.css'); ?>" id="colorstyle">
        <link rel="stylesheet" href="<?php $this->options->themeUrl('css/font-awesome.min.css'); ?>">
        <?php if($this->is('page')): ?>
        <link rel="stylesheet" href="<?php $this->options->themeUrl('css/page.css'); ?>">
        <?php endif ?>

        <?php if(!$this->is('404')): ?>
        <script src="//cdn.bootcss.com/jquery/3.1.0/jquery.js"></script> 
        <script src="<?php $this->options->themeUrl('js/jquery.md5.js'); ?>"></script>
        <?php endif ?>
        <script src="<?php $this->options->themeUrl('js/main.js'); ?>"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.6.0/highlight.min.js"></script>
        <title><?php $this->options->title(); ?><?php $this->archiveTitle(); ?></title>
        <script type="text/javascript">
        hljs.initHighlightingOnLoad();
        var themeurl = "<?php $this->options->themeUrl(); ?>";
        <?php if($this->options->imagecopyright != null): ?>
        var imgsrc = <?php echo $this->options->imagecopyright ?>;
        <?php else :?>
         var imgsrc = null;
        <?php endif ?>
    </script>
    <style type="text/css">
        <?php if(isset($_COOKIE["color"])) {
              if($_COOKIE["color"] == "Light") echo "#checked{display: none}";
              else echo "#unchecked{display: none}";
        }elseif($this->options->themecolorstyle == "LightMode")
                echo "#checked{display: none}";
            else
           echo "#unchecked{display: none}";
        ?>
    
       
    </style>
    
        <?php $this->header(); ?>
    </head>
    <body>
        <header id="header" class="clearfix">
        <span id="show-sidebar"><i class="fa fa-bars color-text" aria-hidden="true"></i></span>
        <?php $this->need('sidebar.php'); ?>
        </header>

        <div class="control-s page-button"><i class="fa fa-terminal fa-2x" aria-hidden="true"></i></div>
        <div class="control-o page-button"><i class="fa fa-angle-up fa-3x" id="up-page" aria-hidden="true"></i><i class="fa fa-angle-down fa-3x" id="down-page" aria-hidden="true"></i></div>
      <ul id="button-group" class="page-button">
        <form method="post" action="">
            <div class="w-search hide"><input type="text" name="s" class="text" size="42" placeholder="Press Enter to Search" /> </div>
        </form>
      <?php if(!($this->user->hasLogin())): ?>
        <div class="login-call hide">
        <form action="<?php $this->options->loginAction(); ?>" method="post" name="login" role="form">
            <label for="name" class="sr-only"><?php _e('用户名'); ?></label>
            <input type="text" id="name" name="name" value="<?php echo $rememberName; ?>" placeholder="<?php _e('用户名'); ?>" class="text-l w-100" autofocus />
            <label for="password" class="sr-only"><?php _e('密码'); ?></label>
            <input type="password" id="password" name="password" class="text-l w-100" placeholder="<?php _e('密码'); ?>" />
            <button type="submit" class="btn btn-l w-100 submit primary"><?php _e('登录'); ?></button>
        </form>
        </div>
        <?php endif ?>
        <li id="feedbutton"><a href="<?php $this->options->feedUrl(); ?>" title="Post RSS"><i class="fa fa-rss" aria-hidden="true"></i></a></li>
        <?php  if(!$this->user->hasLogin()): ?>
        <li class="searchbutton"><i class="fa fa-search fa-2x" aria-hidden="true"></i></li>
        <?php endif ?>
        <li class="switchbutton"><i id="unchecked" class="fa fa-sticky-note-o fa-2x" aria-hidden="true"></i><i id="checked" class="fa fa-sticky-note fa-2x" aria-hidden="true"></i></li>
        <li id="background-view"><i class="fa fa-picture-o fa-2x" aria-hidden="true"></i></li>
        <?php  if($this->user->hasLogin()): ?>
		<li class="adminbutton"><a href="<?php $this->options->adminUrl(); ?>"><i class="fa fa-cog fa-2x" aria-hidden="true"></i></a></li>
        <li class="logoutbutton"><a href="<?php $this->options->logoutUrl(); ?>"><i class="fa fa-sign-out fa-2x" aria-hidden="true"></i></a></li>
        <?php else: ?>
        <?php if(!empty($this->options->telegram)) ?>
        <li class="telegram"><a href="<?php echo $this->options->telegram; ?>"><i class="fa fa-paper-plane fa-2x" aria-hidden="true"></i></a></li>
        <li class="loginbutton"><i class="fa fa-sign-in fa-2x" aria-hidden="true"></i></li>
        <?php endif; ?>
        <li id="imagesource" class="color-white">
        <i class="fa fa-file-image-o fa-3x " aria-hidden="true"></i><span><span id="illust-name"></span></span>
        <span><span id="illust-desc"></span></span>
        <p><span></span><span id="illust-src"></span></p>
        </li>
      </ul>
        <div id="switch-icon" class="color-text"><i class="dark-mode fa fa-file-text-o fa-5x" aria-hidden="true"></i><i class="fa fa-file-text fa-5x light-mode" aria-hidden="true"></i><p class="light-mode">Light Mode</p><p class="dark-mode">Dark Mode</p></div>
        <div id="background" class="blur-saturate">
        <?php if ($this->options->DarkimgUrl): ?>
            <div id="dark-wallpaper" style='background-image:url("<?php echo $this->options->DarkimgUrl ?>")'></div>
        <?php endif ?>
        <?php if ($this->options->LightimgUrl): ?>
            <div id="light-wallpaper"  style='background-image:url("<?php echo $this->options->LightimgUrl ?>")'></div>
        <?php endif ?>
        <?php if ($this->options->PortraitimgUrl): ?>
            <div id="portrait-wallpaper" style='background-image:url("<?php echo $this->options->PortraitimgUrl ?>")'></div>
        <?php endif ?>
        </div>
        <div id="wrapper" class="background-color color-text" >
        <main id="body">
