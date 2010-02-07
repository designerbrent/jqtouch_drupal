<?php
// $Id: $
?>
  <div id="get"  class="current">
    <div class="toolbar">
        <?php if (!empty($site_name)): ?>
          <h1 id="site-name">
            <a href="<?php print $front_page ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
          </h1>
        <?php endif; ?>
        <a class="button slideup" id="infoButton" href="/node/3">About</a>
        <a class="back" href="#">AJAX</a>
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
        <?php if (!empty($mission)): ?><div id="mission"><?php print $mission; ?></div><?php endif; ?>

        <div id="content">
          <?php if (!empty($title)): ?><h1 class="title" id="page-title"><?php print $title; ?></h1><?php endif; ?>
          <?php if (!empty($tabs)): ?><div class="tabs"><?php print $tabs; ?></div><?php endif; ?>
          <?php if (!empty($messages)): print $messages; endif; ?>
          <?php if (!empty($help)): print $help; endif; ?>
          <div id="content-content" class="clear-block">
            <?php print $content; ?>
          </div> <!-- /content-content -->
          <?php print $feed_icons; ?>
        </div> <!-- /content -->
        
      </div>
    </div>
    
</div>
    
        