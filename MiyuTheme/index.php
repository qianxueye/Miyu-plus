<?php
/**
 * 千雪夜自用的Typecho主题。2014年Wordpress Theme的重制版。
 * 
 * @package Miyu++ Typecho Theme 
 * @author 千雪夜(HondaMiyu)
 * @version 1.0
 * @link https://kirisame.moe
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
    $this->need('header.php');?>

<div class="post-list">
    <?php while($this->next()): ?>
    <article class="post" itemscope itemtype="https://schema.org/BlogPosting">
            <h1 class="post-title" itemprop="name headline"><a itemtype="url" href="<?php $this->permalink() ?>"> 
                <?php $this->title(); ?></a></h1>
            <div class="post-meta color-light">
            <ul>
                <li><time datetime="<?php $this->date('c'); ?>" itemprop="datePublished"><?php $this->date('M d, Y'); ?></time>
                &nbsp&nbsp<?php _e('分类: '); ?><?php $this->category(','); ?></li>
				<li itemprop="interactionCount"><?php $this->commentsNum('暂无评论', '1 评论', '%d 评论'); ?></li></ul>
            </div>
    <div  class="post-content" itemprop="articleBody">
        <?php $this->content(' more '); ?>
    </div>
    </article>
    <?php endwhile; ?>
    <?php $this->pageNav('&laquo; ', ' &raquo;'); ?>
</div>

<?php $this->need('footer.php'); ?>