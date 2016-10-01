<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<div id="sidebar" class="color-white">
  <div class="site-name">
        <a id="logo" href="<?php $this->options->siteUrl(); ?>">
        <?php if ($this->options->logoUrl): ?>
            <img class="color-Elight" src="<?php $this->options->logoUrl() ?>" alt="<?php $this->options->title() ?>" />
        <?php endif; ?>
        </a>
        <span class="title"><?php $this->options->title() ?></span>
        <p class="description"><?php $this->options->description() ?></p>
 </div>
 <?php if(in_array('ShowProfileUrls', $this->options->sidebarBlock)): ?>
    <section class="widget profile">
    <ul>
        <?php if(!empty($this->options->GitHubUrl)): ?>
        <li class="profile-url"><a class="square-profile github" href="<?php echo $this->options->GitHubUrl ?>"><i class="fa fa-github" aria-hidden="true"></i></a></li>
        <?php endif ?>
        <?php if(!empty($this->options->TwitterUrl)): ?>
        <li class="profile-url"><a class="square-profile twitter" href="<?php echo $this->options->TwitterUrl ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
        <?php endif ?>
        <?php if(!empty($this->options->FacebookUrl)): ?>
        <li class="profile-url"><a class="square-profile facebook" href="<?php echo $this->options->FacebookUrl ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
        <?php endif ?>
        <?php if(!empty($this->options->GoogleUrl)): ?>
        <li class="profile-url"><a class="square-profile google" href="<?php echo $this->options->GoogleUrl ?>"><i class="fa fa-google-plus-square" aria-hidden="true"></i></a></li>
        <?php endif ?>
        <?php if(!empty($this->options->WeiboUrl)): ?>
        <li class="profile-url"><a class="square-profile weibo" href="<?php echo $this->options->WeiboUrl ?>"><i class="fa fa-weibo" aria-hidden="true"></i></a></li>
        <?php endif ?>
        <?php if(!empty($this->options->QQ)): ?>
        <li class="profile-url"><a class="square-profile qq" href="<?php echo $this->options->QQ ?>"><i class="fa fa-qq" aria-hidden="true"></i></a></li>
        <?php endif ?>
        </ul>
    </section>
<?php else: echo "<br>"; endif ?>
    <section class="widget page-list">
        <ul class="fa-ul">
            <li><a href="<?php $this->options->siteUrl(); ?>"><i class="fa-fw fa-li fa fa-home" aria-hidden="true"></i><span>首页</span></a></li>
        <?php $this->widget('Widget_Contents_Page_List')->to($pagelist);
            while($pagelist->next()):
            if($pagelist->fields->pageicon == null) continue; ?>
                <li><a href="<?php $pagelist->permalink(); ?>">
                    <i class="fa-fw fa-li <?php echo $pagelist->fields->pageicon;?>" aria-hidden="true"></i><span><?php $pagelist->title(); ?></span></a></li>
            <?php endwhile; ?>
        </ul>
	</section>
</div>