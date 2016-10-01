<?php

function themeConfig($form) 
{
    $logoUrl = new Typecho_Widget_Helper_Form_Element_Text('logoUrl', NULL, NULL, _t("ImageUrl<hr/>Blog's Logo"), _t('Blog显示的Logo'));
    $form->addInput($logoUrl);
    $DarkimgUrl = new Typecho_Widget_Helper_Form_Element_Text('DarkimgUrl', NULL, "https://imgsrc.kirisame.moe/images/od_wp_01.jpg", _t("Background-image(Dark)"), _t('主题暗色背景'));
    $form->addInput($DarkimgUrl);
    $LightimgUrl = new Typecho_Widget_Helper_Form_Element_Text('LightimgUrl', NULL, "https://imgsrc.kirisame.moe/images/od_wp_01.jpg", _t("Background-image(Light)"), _t('主题亮色背景'));
    $form->addInput($LightimgUrl);
    $PortraitimgUrl = new Typecho_Widget_Helper_Form_Element_text('PortraitimgUrl', NULL, "https://imgsrc.kirisame.moe/images/od_wp_01.jpg", _t("Background-image(Vertical)"), _t('智能设备竖屏显示的背景'));
    $form->addInput($PortraitimgUrl);
    $imagecopyright = new Typecho_Widget_Helper_Form_Element_Textarea('imagecopyright', NULL, NULL, _t("图片引用信息"), _t('如果背景图片有出处建议填写。格式:'));
    $form->addInput($imagecopyright);
    $TwitterUrl = new Typecho_Widget_Helper_Form_Element_text('TwitterUrl', NULL, NULL, _t("<br/>各种链接<hr/>Twitter"),NULL);
    $form->addInput($TwitterUrl);
    $GoogleUrl = new Typecho_Widget_Helper_Form_Element_text('GoogleUrl', NULL, NULL, _t("Google+"),NULL);
    $form->addInput($GoogleUrl);
    $FacebookUrl = new Typecho_Widget_Helper_Form_Element_text('FacebookUrl', NULL, NULL, _t("Facebook"),NULL);
    $form->addInput($FacebookUrl);
    $GitHubUrl = new Typecho_Widget_Helper_Form_Element_text('GitHubUrl', NULL, NULL, _t("GitHub"),NULL);
    $form->addInput($GitHubUrl);
    $WeiboUrl = new Typecho_Widget_Helper_Form_Element_text('WeiboUrl', NULL, NULL, _t("Weibo"),NULL);
    $form->addInput($WeiboUrl);
    $QQ = new Typecho_Widget_Helper_Form_Element_text('QQ', NULL, NULL, _t("QQ"),NULL);
    $form->addInput($QQ);
    $telegram = new Typecho_Widget_Helper_Form_Element_text('telegram', NULL, NULL, _t("Telegram"),_t('如果填写，将在右下角显示icon。'));
    $form->addInput($telegram);
    $themecolorstyle = new Typecho_Widget_Helper_Form_Element_Radio('themecolorstyle', array(
        'LightMode' => _t('亮色模式'),
        'DarkMode' => _t('暗色模式')),
        'DarkMode', _t('默认主题颜色')
        );
    $form->addInput($themecolorstyle);
    $sidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox('sidebarBlock', 
    array(
    'ShowProfileUrls' => _t('显示侧边栏个人链接'),
    'ShowRecentComments' => _t('显示最近回复')),
    array('ShowProfileUrls'), _t('侧边栏显示'));
    $form->addInput($sidebarBlock->multiMode());
}

function themeFields($layout) 
{
    $noindex = new Typecho_Widget_Helper_Form_Element_Radio('noindex', 
    array(
        'no' => _t('禁止收录'),
        'yes' => _t('允许收录')
        ),
        'yes',
        _t('搜索引擎收录设置')
        );
    $layout->addItem($noindex);
    $coverimageurl = new Typecho_Widget_Helper_Form_Element_Text('coverimageurl', NULL, NULL, _t('CoverImgaeUrl'), _t('此文章页面封面图的url'));
    $layout->addItem($coverimageurl);
    $pageicon = new Typecho_Widget_Helper_Form_Element_Text('pageicon', NULL, NULL, _t('该页面的iconFonts'), _t('独立页面侧边栏显示用。<br/>请参考<a href="http://fontawesome.io/icons/">http://fontawesome.io/icons/</a>'));
    $layout->addItem($pageicon);
}


function threadedComments($comments, $options) {
    $commentClass = '';
    if ($comments->authorId) {
        if ($comments->authorId == $comments->ownerId) {
            $commentClass .= ' comment-by-author';
        } else {
            $commentClass .= ' comment-by-user';
        }
    }
 
    $commentLevelClass = $comments->levels > 0 ? ' comment-child' : ' comment-parent'; ?>

    

<li id="li-<?php $comments->theId(); ?>" class="comment-body<?php 
if ($comments->levels > 0) {
    echo ' comment-child';
    $comments->levelsAlt(' comment-level-odd', ' comment-level-even');
} else {
    echo ' comment-parent';
}
$comments->alt(' comment-odd', ' comment-even');
echo $commentClass;
?>">
    <div id="<?php $comments->theId(); ?>" class="comment-floor">
        <div class="comment-author">
            <?php $comments->gravatar('256'); ?>
            <cite class="fn"><?php $comments->author(); ?></cite>
            <?php
				if($comments->parent)
                {
					$p_comment = getPermalinkFromCoid($comments->parent);
					$p_author = $p_comment['author'];
					$p_text = mb_strimwidth(strip_tags($p_comment['text']), 0, 100,"...");
					$p_href = $p_comment['href'];
					echo "<span class='reply-to'>@<a href='$p_href' title='$p_text'>$p_author</a></span>";
				}?>
        </div>
        <div class="comment-meta">
            <span><?php $comments->date('Y-m-d H:i'); ?></span>
            <span class="comment-reply"><?php $comments->reply(); ?></span>
        </div>
        <?php $comments->content(); ?>
    </div>
<?php if ($comments->children) { ?>
    <div class="comment-children">
        <?php $comments->threadedComments($options); ?>
    </div>
<?php } ?>
</li>
<?php }

function getPermalinkFromCoid($coid) 
{
		$db       = Typecho_Db::get();
		$options  = Typecho_Widget::widget('Widget_Options');
		$contents = Typecho_Widget::widget('Widget_Abstract_Contents');
 
		$row = $db->fetchRow($db->select('cid, type, author, text')->from('table.comments')
				  ->where('coid = ? AND status = ?', $coid, 'approved'));
		if (empty($row)) return 'Comment not found!';
		$cid = $row['cid'];
		$select = $db->select('coid, parent')->from('table.comments')
				  ->where('cid = ? AND status = ?', $cid, 'approved')->order('coid');
		if ($options->commentsShowCommentOnly)
			$select->where('type = ?', 'comment');
 
		$comments = $db->fetchAll($select);
 
		if ($options->commentsOrder == 'DESC')
			$comments = array_reverse($comments);
 
		foreach ($comments as $key => $val)
			$array[$val['coid']] = $val['parent'];
 
		$i = $coid;
		while ($i != 0) 
        {
			$break = $i;
			$i = $array[$i];
		}
 
		$count = 0;
		foreach ($array as $key => $val) 
        {
			if ($val == 0) $count++; 
			if ($key == $break) break; 
		}
 
		$parentContent = $contents->push($db->fetchRow($contents->select()->where('table.contents.cid = ?', $cid)));
		$permalink = rtrim($parentContent['permalink'], '/');
 
		$page = ($options->commentsPageBreak)
			  ? '/comment-page-' . ceil($count / $options->commentsPageSize)
			  : ( substr($permalink, -5, 5) == '.html' ? '' : '/' );
 
		return array(
			"author" => $row['author'],
			"text" => $row['text'],
			"href" => "{$permalink}{$page}#{$row['type']}-{$coid}"
		);
	}	
?>