<?php //$Id: node-og.tpl.php,v 1.1.2.2 2005/05/31 04:43:18 weitzman Exp $ 
?>
<div class="node<?php print ($sticky) ? " sticky" : ""; ?>">
  <?php if ($page == 0): ?>
    <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
  <?php endif; ?>
  <?php print $picture ?>


  <div class="content">
    <?php print $content ?>
  </div>

</div>
