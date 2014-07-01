<?php
/*
Plugin Name: Threader
Plugin URI: http://birchware.se/wordpress/threader/
Description: Build a guestbook or a mini forum by creating threads. No new tables are needed.
Version: 1.00
Author: Mats Birch
Author URI: http://birchware.se/wordpress/
License: GNU General Public License
License URI: license.txt
*/
if (!defined('WP_PLUGIN_URL')) die('<html><head><title>Access denied!</title><body style="background:#f00;color:#FFF;font-size:3em;"><center><h1><br /><br />Access denied!</h1></center></body></html>');

define('THREADER_VERSION','1.00');
define('THREADER_TITLE'  ,'XS Threader');
define('THREADER_DESC'   ,'Build a mini forum by creating threads. No new tables are needed.');
define('THREADER_DIR'    ,dirname(__FILE__));

define('THREADER_ADMINISTRATOR','5');  //  WP: User Level 8-10 - Administrator � Somebody who has access to all the administration features.
define('THREADER_EDITOR'       ,'4');  //  WP: User Level 3-7  - Editor        � Somebody who can publish posts, manage posts as well as manage other people�s posts, etc.
define('THREADER_AUTHOR'       ,'3');  //  WP: User Level 2    - Author        � Somebody who can publish and manage their own posts.
define('THREADER_CONTRIBUTOR'  ,'2');  //  WP: User Level 1    - Contributor   � Somebody who can write and manage their posts but not publish posts.
define('THREADER_SUBSCRIBER'   ,'1');  //  WP: User Level 0    - Subscriber    � Somebody who can read comments/comment/receive news letters, etc.
define('THREADER_NOONE'        ,'0');  //  WP: Not logged in.

load_plugin_textdomain('threader',false,dirname(plugin_basename(__FILE__)) . '/languages/');

add_shortcode('threader','threader_shortcode');

function threader_shortcode($atts,$data = '')
{
  $obj = new threader($atts,$data);
  return $obj->run();
}

function threader_filter_access($title,$id)
{
  global $threader_userlevel;
  $post = get_post($id);
  if ($post) {
    if ($post->comment_count > 0) $title .= ' (' . $post->comment_count . ')';
    $pos = strpos($post->post_content,'[threader ');
    if ($pos !== false) {
      $pos2 = strpos($post->post_content,']',$pos);
      if ($pos2 !== false) {
        $str = substr($post->post_content,$pos+9,$pos2-$pos);
        $pos = strpos($str,'level=');
        if ($pos === false) {
          return $title;
        }
        else {
          if (ctype_digit($str{$pos+6})) {
            if ((int)$threader_userlevel < (int)$str{$pos+6}) return '';
          }
        }
      }
    }
    return $title;
  }
  return $title;  // Shouldn't get here.
}

class threader
{
  private $show_login;
  function threader($para,$data)
  {
    global $threader_user_ok;
    global $threader_username;
    global $threader_userlevel;
    global $threader_heading;
    global $threader_minlevel;

    extract(shortcode_atts(array('desc'     => ''
                                ,'level'    => 0
                                ,'comments' => 1
                                ,'login'    => 0
                                ),$para));
    
    $threader_minlevel = $level;
    $this->show_login = $login;

    $user = wp_get_current_user();
    if ($user->ID != 0) {
      $threader_username = $user->display_name;
      if      (isset($user->allcaps['level_8'])) $threader_userlevel = THREADER_ADMINISTRATOR;
      else if (isset($user->allcaps['level_3'])) $threader_userlevel = THREADER_EDITOR;
      else if (isset($user->allcaps['level_2'])) $threader_userlevel = THREADER_AUTHOR;
      else if (isset($user->allcaps['level_1'])) $threader_userlevel = THREADER_CONTRIBUTOR;
      else if (isset($user->allcaps['level_0'])) $threader_userlevel = THREADER_SUBSCRIBER;
    }
    else {
      $threader_username  = 'n/a';
      $threader_userlevel = THREADER_NOONE;
    }
    $threader_user_ok = $threader_userlevel >= $threader_minlevel;
    if ((!$comments) || (!$threader_user_ok)) add_filter('comments_open','threader_disable_comments');

    add_filter('the_title'          ,'threader_filter_access',10,2);
    add_filter('comments_template'  ,'threader_comments_filter');
  }
  function run()
  {
    global $threader_userlevel;
    if ($this->show_login) {
      if ($threader_userlevel == THREADER_NOONE) {
        return wp_register('<div>','</div>',false) . wp_login_form(array('show' => false));
      }
      else return wp_loginout(get_permalink(),false);
    }
    return '';
  }
}

function threader_disable_comments()
{
	return false;
}

function threader_delete_comment_link($id)
{
  if (current_user_can('edit_post')) {
    echo ' | <a href="' . admin_url("comment.php?action=cdc&dt=spam&c=$id") . '">' . __('Spam'  ,'threader') . '</a>';
    echo ' | <a href="' . admin_url("comment.php?action=cdc&c=$id")         . '">' . __('Delete','threader') . '</a>';
  }
}

function threader_comments_filter()
{
  return THREADER_DIR . '/comments.php';
}

function threader_comment($comment,$args,$depth)
{
  $GLOBALS['comment'] = $comment;
  ?>
  <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
    <div id="comment-<?php comment_ID(); ?>" class="article comment">
      <footer class="comment-meta">
        <div class="comment-author vcard">
        <?php
          $avatar_size = 64;
          if ('0' != $comment->comment_parent) $avatar_size = 48;
          echo '<span class="alignright">' . get_avatar($comment,$avatar_size) . '</span>';
          printf(__( '%1$s on %2$s <span class="says">said:</span>','threader')
            ,sprintf('<span class="fn">%s</span>',get_comment_author_link())
            ,sprintf('<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>'
              ,esc_url(get_comment_link($comment->comment_ID))
              ,get_comment_time('c')
              ,sprintf(__('%1$s at %2$s','threader'),get_comment_date(),get_comment_time())
            )
          );
          edit_comment_link(__('Edit','threader'),' <span class="edit-link">','</span>');
          threader_delete_comment_link(get_comment_ID());
          ?>
        </div>
        <?php
        if ($comment->comment_approved == '0') {
          echo '<em class="comment-awaiting-moderation">' . __('Your comment is awaiting moderation.','threader') . '</em><br />';
        }
        ?>
      </footer>
      <div class="comment-content"><?php comment_text(); ?></div>
      <div class="reply">
        <?php comment_reply_link(array_merge($args,array('reply_text' => __('Reply <span>&darr;</span>','threader'),'depth' => $depth,'max_depth' => $args['max_depth']))); ?>
      </div>
    </div>
  <?php
}

