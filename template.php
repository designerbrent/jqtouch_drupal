<?php 
// $Id: $

// Auto-rebuild the theme registry during theme development.
if (theme_get_setting('jqtouch_rebuild_registry')) {
  drupal_rebuild_theme_registry();
}

//disable admin menu 
if (module_exists('admin_menu')) {
  admin_menu_suppress(TRUE);
}

/**
 * Implements HOOK_theme().
 */
function jqtouch_theme(&$existing, $type, $theme, $path) {
  if (!db_is_active()) {
    return array();
  }
  include_once './' . drupal_get_path('theme', 'jqtouch') . '/template.theme-registry.inc';
  return _jqtouch_theme($existing, $type, $theme, $path);
}

/**
 * Intercept page template variables
 *
 * @param $vars
 *   A sequential array of variables passed to the theme function.
 */
function jqtouch_preprocess_page(&$vars) {

  unset($vars['css']['all']['module']);
  $vars['styles'] = drupal_get_css($vars['css']); // add only theme css styles

  // Setup viewport meta tags
  if (theme_get_setting('jqtouch_disable_resize')) {
    $noscale = 'user-scalable=no, ';
  }
  
  $vars['viewport'] = '<meta name="viewport" content="'. $noscale .'width=device-width">
    <meta name="viewport" content="initial-scale=1.0">';

}

function jqtouch_pager($tags = array(), $limit = 10, $element = 0, $parameters = array(), $quantity = 3) {
  $quantity = 0;
  global $pager_page_array, $pager_total;
  // Calculate various markers within this pager piece:
  // Middle is used to "center" pages around the current page.
  $pager_middle = ceil($quantity / 2);
  // current is the page we are currently paged to
  $pager_current = $pager_page_array[$element] + 1;
  // first is the first page listed by this pager piece (re quantity)
  $pager_first = $pager_current - $pager_middle + 1;
  // last is the last page listed by this pager piece (re quantity)
  $pager_last = $pager_current + $quantity - $pager_middle;
  // max is the maximum page number
  $pager_max = $pager_total[$element];
  // End of marker calculations.

  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }
  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.
  $li_first = theme('pager_first', (isset($tags[0]) ? $tags[0] : t('«')), $limit, $element, $parameters);
  $li_previous = theme('pager_previous', (isset($tags[1]) ? $tags[1] : t('‹')), $limit, $element, 1, $parameters);
  $li_next = theme('pager_next', (isset($tags[3]) ? $tags[3] : t('›')), $limit, $element, 1, $parameters);
  $li_last = theme('pager_last', (isset($tags[4]) ? $tags[4] : t('»')), $limit, $element, $parameters);

  if (!$li_first) {
    $li_first = '«';
  }
  if (!$li_previous) {
    $li_previous = '‹';
  }
  if (!$li_next) {
    $li_next = '›';
  }
  if (!$li_last) {
    $li_last = '»';
  }
  
  if ($pager_total[$element] > 1) {
    // if ($li_first) {
      $items[] = array(
        'class' => 'pager-first',
        'data' => $li_first,
      );
    // }
    // if ($li_previous) {
      $items[] = array(
        'class' => 'pager-previous',
        'data' => $li_previous,
      );
    // }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      // if ($i > 1) {
      //   $items[] = array(
      //     'class' => 'pager-ellipsis',
      //     'data' => '…',
      //   );
      // }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => 'pager-item',
            'data' => theme('pager_previous', $i, $limit, $element, ($pager_current - $i), $parameters),
          );
        }
        if ($i == $pager_current) {
          $items[] = array(
            'class' => 'pager-current',
            'data' => $i,
          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => 'pager-item',
            'data' => theme('pager_next', $i, $limit, $element, ($i - $pager_current), $parameters),
          );
        }
      }
      // if ($i < $pager_max) {
      //   $items[] = array(
      //     'class' => 'pager-ellipsis',
      //     'data' => '…',
      //   );
      // }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => 'pager-next',
        'data' => $li_next,
      );
    }
    if ($li_last) {
      $items[] = array(
        'class' => 'pager-last',
        'data' => $li_last,
      );
    }
    
    return theme('item_list', $items, NULL, 'ul', array('class' => 'pager'));
  }
}

/**
* Trim a string to a given number of words
*
* @param $string
*   the original string
* @param $count
*   The number of characters to return.
* @param $ellipsis
*   TRUE to add "..."
*   or use a string to define other character
*
* @return
*   trimmed string with ellipsis added if it was truncated
*/
function char_trim($string, $count, $ellipsis = FALSE){
  $strlen = strlen($string);
  if ($count < $strlen) {
    $string = drupal_substr($string, 0, $count);
    if (is_string($ellipsis)){
      $string .= $ellipsis;
    }
    elseif ($ellipsis){
      $string .= '&hellip;';
    }
  }
  
  return $string;
}