<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<div id="post-<?php $this->cid() ?>" class="post-<?php $this->cid() ?> post type-post">
    <?php if($this->fields->coverimageurl != null): ?>
    <div class="post-cover" style="background-size:cover ;background-image:url('<?php echo $this->fields->coverimageurl ?>')"></div>
    <?php endif; ?>
    <div class="post-inner">
        <div class="post-header <?php if($this->fields->coverimageurl != null) echo 'cover-position'; ?>">
            <h1 class="post-title"><?php $this->title() ?></h1>
            <div class="post-meta"><time datetime="<?php $this->date('c'); ?>" itemprop="datePublished">创建于： <?php $this->date('M d, Y  G:i:s'); ?></time></div>
        </div>
        <style>.post-content{margin-bottom:80px}</style>
        <div class="post-content">
        <?php $this->content(); ?>
        </div>
        <?php $this->need('comments.php'); ?>
    </div>
</div>

<?php $this->need('footer.php'); ?>
