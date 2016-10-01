<?php
/**
 * 显示所有标签与分类
 *
 * @package custom
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');?>
<div id="post-<?php $this->cid() ?>" class="post-<?php $this->cid() ?> post type-post">
    <?php if($this->fields->coverimageurl != null): ?>
    <div class="post-cover" style="background-size:cover ;background-image:url('<?php echo $this->fields->coverimageurl ?>')"></div>
    <?php endif; ?>
    <div class="post-inner">
        <div class="post-header <?php if($this->fields->coverimageurl != null) echo 'cover-position'; ?>">
            <h1 class="post-title"></h1>
        </div>
        <div class="post-content">
            <br/>
            <form method="post" action="">
                <div id="post-search"><label>Blog搜索</label><input type="text" name="s" class="text" style="width:70%;overflow-x:visible;overflow-y:visible;" placeholder="Press Enter to Search" /></div>
            </form>
            <br/>
            <ul>
<?php Typecho_Widget::widget('Widget_Stat')->to($stat); ?>
<li>文章总数：<?php $stat->publishedPostsNum() ?>篇</li>
<li>分类总数：<?php $stat->categoriesNum() ?>个</li>
<li>评论总数：<?php $stat->publishedCommentsNum() ?>条</li>
<li>页面总数：<?php $stat->publishedPagesNum() ?>个</li></ul>
            <section class="post-category">
            <h2>文章分类</h2>
                <ul>
                    <?php $this->widget('Widget_Metas_Category_List')->parse('<li><a href="{permalink}">{name}</a><span class="cate-count">{count} 篇文章</span></li>'); ?>
                </ul>
            </section>
            <section class="pages">
            <h2>独立页面</h2>
            <ul>
            <?php $this->widget('Widget_Contents_Page_List')->to($pagelist);
            while($pagelist->next()): ?>
                <li><a href="<?php $pagelist->permalink(); ?>">
                <span><?php $pagelist->title(); ?></span></a></li>
            <?php endwhile; ?>
        </ul></section>
            <br/><br/>
            <section class="post-tags"><br/>
                <?php 
                $db = Typecho_Db::get();
                $options = Typecho_Widget::widget('Widget_Options');
                $tags= $db->fetchAll($db->select()->from('table.metas')->where('table.metas.type = ?', 'tag')->order('table.metas.order', Typecho_Db::SORT_DESC));
                foreach($tags AS $tag) {
                    $type = $tag['type'];
                    $routeExists = (NULL != Typecho_Router::get($type));
                    $tag['pathinfo'] = $routeExists ? Typecho_Router::url($type, $tag) : '#';
                    $tag['permalink'] = Typecho_Common::url($tag['pathinfo'], $options->index);
                    echo "<a href=\"".$tag['permalink']."\">".$tag['name']."</a> ";
                }?>	
            </section>
        <?php $this->content(); ?>
        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>