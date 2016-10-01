<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>
<div class="col-mb-12 col-8" id="main" role="main">
    <?php if($this->fields->coverimageurl != null): ?>
    <div class="post-cover" style="background-size:cover ;background-image:url('<?php echo $this->fields->coverimageurl ?>')"></div>
    <?php endif; ?>
    <article class="post" itemscope itemtype="http://schema.org/BlogPosting">
    <div class="post-header color-title <?php if($this->fields->coverimageurl != null) echo 'cover-position'; ?>" >
        <h1 class="post-title" itemprop="name headline"><?php $this->title() ?></h1>
        <ul class="post-meta">
            <li><time datetime="<?php $this->date('c'); ?>" itemprop="datePublished"><?php $this->date('M d, Y'); ?></time>
            &nbsp&nbsp<?php _e('分类: '); ?><?php $this->category(','); ?></li>
        </ul>
    </div>
    <div class="post-content" itemprop="articleBody">
            <?php $this->content(); ?>
    </div>
    <span itemprop="keywords" class="tags">Tags: <?php $this->tags('&nbsp&nbsp', true, 'None'); ?></span>
    </article>
    <section>
        <ul class="post-near">
            <li class="post-prev">上一篇: <?php $this->thePrev('%s','&nbsp&nbsp&nbsp&nbsp'); ?></li>
            <li class="post-next">下一篇: <?php $this->theNext('%s','&nbsp&nbsp&nbsp&nbsp'); ?></li>
        </ul>
     <?php $this->need('comments.php'); ?>
    </section>
    

</div>
<?php $this->need('footer.php'); ?>