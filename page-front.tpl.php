<?php
// $Id: page.tpl.php,v 1.11.2.1 2009/04/30 00:13:31 goba Exp $

/**
 * @file page.tpl.php
 *
 * Theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $css: An array of CSS files for the current page.
 * - $directory: The directory the theme is located in, e.g. themes/garland or
 *   themes/garland/minelli.
 * - $is_front: TRUE if the current page is the front page. Used to toggle the mission statement.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Page metadata:
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
 * - $head_title: A modified version of the page title, for use in the TITLE tag.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $body_classes: A set of CSS classes for the BODY tag. This contains flags
 *   indicating the current layout (multiple columns, single column), the current
 *   path, whether the user is logged in, and so on.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 * - $mission: The text of the site mission, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $search_box: HTML to display the search box, empty if search has been disabled.
 * - $primary_links (array): An array containing primary navigation links for the
 *   site, if they have been configured.
 * - $secondary_links (array): An array containing secondary navigation links for
 *   the site, if they have been configured.
 *
 * Page content (in order of occurrance in the default page.tpl.php):
 * - $left: The HTML for the left sidebar.
 *
 * - $breadcrumb: The breadcrumb trail for the current page.
 * - $title: The page title, for use in the actual HTML content.
 * - $help: Dynamic help text, mostly for admin pages.
 * - $messages: HTML for status and error messages. Should be displayed prominently.
 * - $tabs: Tabs linking to any sub-pages beneath the current page (e.g., the view
 *   and edit tabs when displaying a node).
 *
 * - $content: The main content of the current Drupal page.
 *
 * - $right: The HTML for the right sidebar.
 *
 * Footer/closing data:
 * - $feed_icons: A string of all feed icons for the current page.
 * - $footer_message: The footer message as defined in the admin settings.
 * - $footer : The footer region.
 * - $closure: Final closing markup from any modules that have altered the page.
 *   This variable should always be output last, after all other dynamic content.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 */
?>
<!DOCTYPE html>
<html>
<head>
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  <script type="text/javascript" charset="utf-8">
      var jQT = new $.jQTouch({
          icon: 'jqtouch.png',
          addGlossToIcon: false,
          startupScreen: 'jqt_startup.png',
          statusBar: 'black',
          preloadImages: [
              'jqtouch/themes/jqt/img/back_button.png',
              'jqtouch/themes/jqt/img/back_button_clicked.png',
              'jqtouch/themes/jqt/img/button_clicked.png',
              'jqtouch/themes/jqt/img/grayButton.png',
              'jqtouch/themes/jqt/img/whiteButton.png',
              'jqtouch/themes/jqt/img/loading.gif'
              ]
      });
      // Some sample Javascript functions:
      $(function(){
          // Show a swipe event on swipe test
          $('#swipeme').swipe(function(evt, data) {                
              $(this).html('You swiped <strong>' + data.direction + '</strong>!');
          });
          $('a[target="_blank"]').click(function() {
              if (confirm('This link opens in a new window.')) {
                  return true;
              } else {
                  $(this).removeClass('active');
                  return false;
              }
          });
          // Page animation callback events
          $('#pageevents').
              bind('pageAnimationStart', function(e, info){ 
                  $(this).find('.info').append('Started animating ' + info.direction + '&hellip; ');
              }).
              bind('pageAnimationEnd', function(e, info){
                  $(this).find('.info').append(' finished animating ' + info.direction + '.<br /><br />');
              });
          // Page animations end with AJAX callback event, example 1 (load remote HTML only first time)
          $('#callback').bind('pageAnimationEnd', function(e, info){
              if (!$(this).data('loaded')) {                      // Make sure the data hasn't already been loaded (we'll set 'loaded' to true a couple lines further down)
                  $(this).append($('<div>Loading</div>').         // Append a placeholder in case the remote HTML takes its sweet time making it back
                      load('ajax.html .info', function() {        // Overwrite the "Loading" placeholder text with the remote HTML
                          $(this).parent().data('loaded', true);  // Set the 'loaded' var to true so we know not to re-load the HTML next time the #callback div animation ends
                      }));
              }
          });
          // Orientation callback event
          $('body').bind('turn', function(e, data){
              $('#orient').html('Orientation: ' + data.orientation);
          });
      });
  </script>
	<?php print $viewport; ?>
</head>
<body class="<?php print $body_classes; ?>">
  <div id="page"  class="current">
    <div class="toolbar">
        <?php if (!empty($site_name)): ?>
          <h1 id="site-name">
            <a href="<?php print $front_page ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
          </h1>
        <?php endif; ?>
        <a class="button slideup" id="infoButton" href="/node/3">About</a>
        <?php print $titlebar; ?>
    </div>

    <div id="container" class="clear-block">
      <div id="navigation" class="menu <?php if (!empty($primary_links)) { print "withprimary"; } ?> ">
        <?php if (!empty($primary_links)): ?>
          <div id="primary" class="clear-block">
            <?php print theme('links', $primary_links, array('class' => 'links primary-links')); ?>
          </div>
        <?php endif; ?>
      </div> <!-- /navigation -->

      <div id="main" class="column"><div id="main-squeeze">
        <?php if (!empty($mission)): ?><div id="mission" class="info"><?php print $mission; ?></div><?php endif; ?>

        <div id="content">
          <?php if ($messages): ?>
            <div class="info">
              <?php print $messages; ?>
            </div>
          <?php endif ?>

          <?php if (!empty($title)): ?>
            <?php if (!empty($title)): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
            <?php if (!empty($tabs)): ?><div class="tabs"><?php print $tabs; ?></div><?php endif; ?>


            <?php if (!empty($messages)): print $messages; endif; ?>
            <?php if (!empty($help)): print $help; endif; ?>
            <div id="content-content" class="clear-block">
              <?php print $content; ?>
            </div> <!-- /content-content -->
          <?php else: ?>
            <div id="content-content" class="clear-block">
              <ul class="edgetoedge">
                <?php print $content; ?>
              </ul>
            </div> <!-- /content-content -->
          <?php endif ?>
          
          <?php print $feed_icons; ?>
        </div> <!-- /content -->

      </div></div> <!-- /main-squeeze /main -->

    </div> <!-- /container -->

    <div id="footer">
      <?php print $footer_message; ?>
      <?php if (!empty($footer)): print $footer; endif; ?>
    </div> <!-- /footer -->


  </div> <!-- /page -->
  <?php print $closure; ?>

</body>
</html>