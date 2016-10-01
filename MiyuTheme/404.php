<?php 
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php'); ?>

<div id="page-404">
    <img src="<?php $this->options->themeUrl('/img/404light.png');?>" class="light" />
    <img src="<?php $this->options->themeUrl('/img/404dark.png');?>" class="dark" />
    <span id="title-404">404 not found!</span>
</div>
<?php $this->need('footer.php'); ?>