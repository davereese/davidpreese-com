<?php
global $threader_user_ok;
global $threader_username;
global $threader_heading;
if ($threader_user_ok) {
?>

    <article>
      <div class="article">
        <div id="comments">
        <?php
          if (post_password_required()) {
            echo '<p class="nopassword">' . __('This post is password protected. Enter the password to view any comments.','threader') . '</p></div>';
            return;
          }
          if (have_comments()) {
            if (get_comment_pages_count() > 1 && get_option('page_comments')) {
              echo '<div id="comment-nav-above">'
                 .   '<div class="nav-previous">' . get_previous_comments_link(__('&larr; Older Comments','threader')) . '</div>'
                 .   '<div class="nav-next">'     .     get_next_comments_link(__('Newer Comments &rarr;','threader')) . '</div>'
                 . '</div>';
            }
            echo '<ol class="commentlist">';
            echo $threader_heading;
            wp_list_comments(array('callback' => 'threader_comment','reverse_top_level' => true));
            echo '</ol>';
            if (get_comment_pages_count() > 1 && get_option('page_comments')) {
              echo '<div id="comment-nav-below">'
                 .   '<div class="nav-previous">'  . get_previous_comments_link(__('&larr; Older Comments','threader')) . '</div>'
                 .   '<div class="nav-next">'      .     get_next_comments_link(__('Newer Comments &rarr;','threader')) . '</div>'
                 . '</div>';
            }
            else if (!comments_open() && !is_page() && post_type_supports(get_post_type(),'comments')) {
              echo '<p class="nocomments">' . __('Comments are closed.','threader') . '</p>';
            }
          }
          comment_form(array('comment_field' => '<p class="comment-form-comment">'
                                              . '<label for="comment"><span class="comment-form-label">' . __('Comment','threader') . '</span></label>'
                                              . '<textarea id="comment" name="comment" aria-required="true"></textarea>'
                                              . '</p>'
                                              ));
        ?>
        </div>
      </div>
    </article>
<?php
}

