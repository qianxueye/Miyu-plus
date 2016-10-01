<?php
/**
 * Just Archives
 * 
 * @package JustArchives
 * @author jKey
 * @version 0.2.2
 * @link http://typecho.jkey.lu/
 * @license GPL3
 */

class JustArchives_Plugin implements Typecho_Plugin_Interface
{
	public static $version = '0.2.0';
	public static $cache_path = 'usr/plugins/JustArchives/cache/';
	public static $cache_file = 'justarchives.cache';
	public static $is_archive_page = false;
	/**
	 * 激活插件方法,如果激活失败,直接抛出异常
	 * 
	 * @access public
	 * @return void
	 * @throws Typecho_Plugin_Exception
	 */
	public static function activate()
	{
		// 把相关的 javascript 添加到头部
		Typecho_Plugin::factory('Widget_Archive')->footer = array('JustArchives_Plugin', 'outputCSSJavascript');
		
		// 读取文章时匹配 [justarchives] 替换为归档
		Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('JustArchives_Plugin', 'parse');
		
		// 当有新的文章发布时删除归档缓存文件
		Typecho_Plugin::factory('Widget_Contents_Post_Edit')->write = array('JustArchives_Plugin', 'deleteCache');
		
		// 当有新的评论发布时删除归档缓存文件
		Typecho_Plugin::factory('Widget_Feedback')->finishComment = array('JustArchives_Plugin', 'deleteCache');
	}

	/**
	 * 禁用插件方法,如果禁用失败,直接抛出异常
	 * 
	 * @static
	 * @access public
	 * @return void
	 * @throws Typecho_Plugin_Exception
	 */
	public static function deactivate()
	{
		JustArchives_Plugin::deleteCache();
	}

	/**
	 * 获取插件配置面板
	 * 
	 * @access public
	 * @param Typecho_Widget_Helper_Form $form 配置面板
	 * @return void
	 */
	public static function config(Typecho_Widget_Helper_Form $form)
	{
		
		$cfg_format = new Typecho_Widget_Helper_Form_Element_Text('cfg_format',NULL,'"m-d H:i"','文章日期格式','请参考 PHP 日期格式写法.');
		$form->addInput($cfg_format);	// 自行设置日期输出format

		$cfg_usejs = new Typecho_Widget_Helper_Form_Element_Checkbox(
			'cfg_usejs',
			array('usejs' => '使用Javascript折叠显示存档效果',
				  'incjq' => '插件单独引用jQuery'),
			array('usejs','incjq'),
			'是否使用 JavaScript','如果主题已引用jQuery库则无需再次引用.'
		);
		$form->addInput($cfg_usejs);

		$cfg_monthorder = new Typecho_Widget_Helper_Form_Element_Radio(
			'cfg_monthorder',
			array('month_desc' => '按时间倒叙排列月份（离现在最近的月份排最前）',
				'month_asc' => '按时间正序排列月份（离现在最远的月份排最前）'
			),
			'month_desc',
			'存档月份排序'
		);
		$form->addInput($cfg_monthorder->multiMode());
		
		$cfg_postorder = new Typecho_Widget_Helper_Form_Element_Radio(
			'cfg_postorder',
			array('post_desc' => '将最新的日志显示在第一位',
				'post_asc' => '将最旧的日志显示在第一位'
			),
			'post_desc',
			'存档文章排序'
		);
		$form->addInput($cfg_postorder->multiMode());
		
		$cfg_postcount = new Typecho_Widget_Helper_Form_Element_Checkbox(
			'cfg_postcount',
			array('show' => '显示文章数'),
			array('show'),
			'是否显示文章数'
		);
		$form->addInput($cfg_postcount);
		
		$cfg_delcache = new Typecho_Widget_Helper_Form_Element_Checkbox(
			'cfg_delcache',
			array('comment' => '有新的评论时删除归档缓存'),
			array(),
			'删除缓存',
			'默认只有在你创建文章时删除缓存，勾选后同时在有新评论时也删除缓存，主要是为了显示评论数与数据库的一致'
		);
		$form->addInput($cfg_delcache);
	}

	/**
	 * 个人用户的配置面板
	 * 
	 * @access public
	 * @param Typecho_Widget_Helper_Form $form
	 * @return void
	 */
	public static function personalConfig(Typecho_Widget_Helper_Form $form){}
	
	/*
	 * Output a little helper CSS and the Javascript for the plugin
	 * Based on code from http://www.learningjquery.com/2007/03/accordion-madness
	 *
	 */
	public static function outputCSSJavascript()
	{
		if ( !JustArchives_Plugin::$is_archive_page )
			return;

		$settings = Helper::options()->plugin('JustArchives');
		if ( !in_array("usejs", $settings->cfg_usejs) ) {
			return;
		}
		global $cleanarchivesreloaded;

		if ( !empty($cleanarchivesreloaded) )
			return;

		$cleanarchivesreloaded = true;

		$options = Typecho_Widget::widget('Widget_Options');
		?>

	<!-- Just Archives | http://typecho.jkey.lu/ -->
	<style type="text/css">.car-collapse .car-yearmonth { cursor: s-resize; } </style>
<?php if ( in_array("incjq", $settings->cfg_usejs) ): ?>
	<script src="//cdn.bootcss.com/jquery/3.1.0/jquery.js"></script>
<?php endif ?>
	<script type="text/javascript">
		/* <![CDATA[ */
			jQuery(document).ready(function() {
				jQuery('.car-collapse').find('.car-monthlisting').hide();
				jQuery('.car-collapse').find('.car-monthlisting:first').show();
				jQuery('.car-collapse').find('.car-yearmonth').click(function() {
					jQuery(this).next('div').stop();
					jQuery(this).next('div').slideToggle(400);
				});
				jQuery('.car-collapse').find('.car-toggler').click(function() {
					jQuery(this).parent('.car-container').find('.car-monthlisting').stop();
					if ( '展开全部' == jQuery(this).text() ) {
						jQuery(this).parent('.car-container').find('.car-monthlisting').show(400);
						jQuery(this).text('折叠全部');
					}
					else {
						jQuery(this).parent('.car-container').find('.car-monthlisting').hide(400);
						jQuery(this).text('展开全部');
					}
					return false;
				});
			}); 
		/* ]]> */
	</script>

<?php
	}
	
	/**
	 * Grab all posts and filter them into an array
	 *
	 */
	public static function GetPosts()
	{
		$options = Typecho_Widget::widget('Widget_Options');
		
		/**
		 * 获取数据库实例化对象
		 * 用静态变量存储实例化的数据库对象,可以保证数据连接仅进行一次
		 */
		$db = Typecho_Db::get();
		
		$select = $db->select('cid', 'title', 'slug', 'created', 'allowComment', 'commentsNum')
					->from('table.contents')
					->where('status = ?', 'publish')
					->where('type = ?', 'post');
		$rawposts = $db->fetchAll($select);

		$posts = array();
		// Loop through each post and sort it into a structured array
		foreach( $rawposts as $post ) {
			/** 取出所有分类 */
            $categories = $db->fetchAll($db
				->select('slug')->from('table.metas')
				->join('table.relationships', 'table.metas.mid = table.relationships.mid')
				->where('table.relationships.cid = ?', $post['cid'])
				->where('table.metas.type = ?', 'category')
				->order('table.metas.order', Typecho_Db::SORT_ASC));

            /** 取出第一个分类作为slug条件 */
            $post['category'] = current(Typecho_Common::arrayFlatten($categories, 'slug'));
		
			$date = new Typecho_Date($post['created']);
			$post['year'] = $date->year;
			$post['month'] = $date->month;
			$post['day'] = $date->day;
			
			$type = 'post';//$p['type'];
            $routeExists = (NULL != Typecho_Router::get($type));
            $permalink = $routeExists ? Typecho_Router::url($type, $post, $options->index) : '#';

			$post['permalink'] = $permalink;
			
			$posts[ $post['year'] . '.' . $post['month'] ][] = $post;
		}
		$rawposts = null; // More memory cleanup

		return $posts;
	}

	/**
	 * Generates the HTML output based on $atts array from the shortcode
	 *
	 */
	public static function PostList()
	{
		$settings = Helper::options()->plugin('JustArchives');

		// Set any missing $atts items to the defaults
		$atts = array(
			'format'	   => ($settings->cfg_format != NULL) ? $settings->cfg_format : 'Y-m-d H:i:s',
			'usejs'		   => ($settings->cfg_usejs != NULL) ? in_array('usejs', $settings->cfg_usejs) : false,
			'monthorder'   => $settings->cfg_monthorder,
			'postorder'    => $settings->cfg_postorder,
			'postcount'    => ($settings->cfg_postcount != NULL) ? (in_array('show', $settings->cfg_postcount) ? '1' : '0') : '0'
		);

		// Get the big array of all posts
		// 检查是否存在缓存文件，有则读取，没有则创建
		if ( file_exists( JustArchives_Plugin::$cache_path . JustArchives_Plugin::$cache_file ) ) {
			$posts = unserialize( JustArchives_Plugin::readCache() );
		} else {
			$posts = JustArchives_Plugin::GetPosts();
			JustArchives_Plugin::writeCache( serialize($posts) );
		}

		// Sort the months based on $atts
		( 'month_desc' == $atts['monthorder'] ) ? krsort( $posts ) : ksort( $posts );

		// Sort the posts within each month based on $atts
		foreach( $posts as $key => $month ) {
			$sorter = array();
			foreach ( $month as $post )
				$sorter[] = $post['created'];

			$sortorder = ( 'post_desc' == $atts['postorder'] ) ? SORT_DESC : SORT_ASC;

			array_multisort( $sorter, $sortorder, $month );

			$posts[$key] = $month;
			unset($month);
		}


		// Generate the HTML
		$html ='<style type="text/css">.star-post {width: 30px}.car-monthlisting table{width:100%;line-height: 1.5em}
		.date-post{text-align: right;padding-right: 20%}
		@media(max-width:960px) {.car-list{padding-left:0}}
		@media(max-width:480px){.date-post{padding-right: 5%}}
		</style>';
		$html .= '<div class="car-container';
		if ( true == $atts['usejs'] ) $html .= ' car-collapse';
		$html .= '">'. "\n";

		if ( true == $atts['usejs'] ) $html .= '<a href="#" class="car-toggler">展开全部' . "</a>\n\n";

		$html .= '<ul class="car-list">' . "\n";

		$firstmonth = TRUE;
		foreach( $posts as $yearmonth => $posts ) {
			list( $year, $month ) = explode( '.', $yearmonth );

			$firstpost = TRUE;
			foreach( $posts as $post ) {
				if ( TRUE == $firstpost ) {
					$html .= '	<li><span class="car-yearmonth">' . sprintf( '%1$s %2$d', $month . '月', $year );
					if ( '0' != $atts['postcount'] ) $html .= ' <span title="Post Count">(' . count($posts) . ')</span>';
					$html .= "</span>\n		<div class='car-monthlisting'><table>\n";
					$firstpost = FALSE;
				}

				$html .= '<tr><td class="star-post"></td><td class="title-post">' . '<a href="' . $post['permalink'] . '">' . $post['title'] . '</a></td><td class="date-post">' .date($atts['format'],$post['created']) . '</td>';

				// Unless comments are closed and there are no comments, show the comment count

				$html .= "</tr>\n";
			}
			$html .= "</table></div>\n	</li>\n";
		}

		$html .= "</ul>\n</div>\n";

		return $html;
	}
	
	/**
	 * 插件实现方法
	 * 
	 * @access public
	 * @return void
	 */
	public static function parse($text, $widget, $lastResult)
	{
		$text = empty($lastResult) ? $text : $lastResult;
		
		if ( $widget instanceof Widget_Archive && ( 1 == preg_match("/(\s*)(<justarchives>)(\s*)/si", $text) ) ) {
			JustArchives_Plugin::$is_archive_page = true;
			return preg_replace("/(\s*)(<justarchives>)(\s*)/si", "$1" . JustArchives_Plugin::PostList() . "$3", $text);
		} else {
			return $text;
		}
	}
	
	/**
	 * 把缓存写入文件
	 */
 	public static function writeCache($cache)
	{
		if ( !is_writeable ( JustArchives_Plugin::$cache_path ) ) {
			if ( !chmod( JustArchives_Plugin::$cache_path , 777 ) )
				return false;
		}

		$fp = fopen(JustArchives_Plugin::$cache_path . JustArchives_Plugin::$cache_file, 'w');
		fwrite($fp, $cache);
		fclose($fp);
	}
	
	/**
	 * 读取缓存
	 */
 	public static function readCache()
	{
		$fp = fopen(JustArchives_Plugin::$cache_path . JustArchives_Plugin::$cache_file, 'r');
		$cache = fread($fp, filesize(JustArchives_Plugin::$cache_path . JustArchives_Plugin::$cache_file));
		fclose($fp);
		return $cache;
	}
	
	/**
	 * 删除缓存
	 */
 	public static function deleteCache($widget = NULL)
	{
		$settings = Helper::options()->plugin('JustArchives');
		if ( !in_array('comment', $settings->cfg_delcache) && $widget instanceof Widget_Abstract_Comments ) {
			return $widget;
		}

		if ( file_exists( JustArchives_Plugin::$cache_path . JustArchives_Plugin::$cache_file ) ) {
			unlink(JustArchives_Plugin::$cache_path . JustArchives_Plugin::$cache_file);
		}
		return $widget;
	}
}