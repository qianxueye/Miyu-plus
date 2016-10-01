<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
 <link rel="stylesheet" href="<?php $this->options->themeUrl('css/comment.css'); ?>">
<div id="comments">
    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()): ?>
	<h3><?php $this->commentsNum(_t('暂无评论'), _t('1 评论'), _t('%d 评论')); ?></h3>
    <?php $comments->listComments(); ?>
    <?php $comments->pageNav('&laquo; 前一页', '后一页 &raquo;'); ?>
    <?php endif; ?>
    
    <?php if($this->allow('comment')): ?>
    <div id="<?php $this->respondId(); ?>" class="respond">
        <div class="cancel-comment-reply">
        <?php $comments->cancelReply(); ?>
        </div>
        <br/>
    	<form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" role="form">
            <?php if($this->user->hasLogin()): ?>
    		<p><?php _e('登录身份: '); ?><a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>. <a href="<?php $this->options->logoutUrl(); ?>" title="Logout"><?php _e('退出'); ?> &raquo;</a></p>
            <?php else: ?>
    		<p>
                <label for="author" class="required"><?php _e('Nickname'); ?></label>
    			<input type="text" name="author" id="author" class="text" value="<?php $this->remember('author'); ?>" required />
    		</p>
    		<p>
                <label for="mail"<?php if ($this->options->commentsRequireMail): ?> class="required"<?php endif; ?>><?php _e('Email'); ?></label>
    			<input type="email" name="mail" id="mail" class="text" value="<?php $this->remember('mail'); ?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?> />
    		</p>
    		<p>
                <label for="url"<?php if ($this->options->commentsRequireURL): ?> class="required"<?php endif; ?>><?php _e('Website'); ?></label>
    			<input type="url" name="url" id="url" class="text" placeholder="<?php _e('http://'); ?>" value="<?php $this->remember('url'); ?>"<?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?> />
    		</p>
            <?php endif; ?>
    		<p>
                <img class="gravatar-v" src="//cn.gravatar.com/avatar/<?php echo md5($this->remember('mail',true)); ?>?s=120&r=X" />
                <textarea rows="8" cols="50" name="text" id="textarea" class="textarea" placeholder="Ctrl+Enter to Comment" required ><?php $this->remember('text'); ?></textarea>
            </p>
    		<p>
                <button type="submit" class="submit" id="replysubmit"><?php _e('提交'); ?></button>
            </p>
    	</form>
    </div>
    <script type="text/javascript">
        document.getElementById("textarea").onkeydown = function (moz_ev){var ev = null;if (window.event){ev = window.event;}else{ev = moz_ev;}if (ev != null && ev.ctrlKey && ev.keyCode == 13){document.getElementById("replysubmit").click();}}
        $(document).ready(function(){
        $(".comment-reply").click(function(){
             obj = $(this).parent().parent();
             var reply = $('a.appendMe')[0]; 
             obj.append(reply);
        })});
    </script>
    <?php endif; ?>
</div>