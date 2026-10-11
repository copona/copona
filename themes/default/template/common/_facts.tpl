<?php
// Label/value pairs in a panel. Expects $facts = [[label, value], ...] and optional $facts_title.
// Pairs with an empty value are skipped.
?>
<section class="cp-panel cp-info">
  <?php if (!empty($facts_title)) { ?>
    <h2 class="cp-info__title"><?php echo $facts_title; ?></h2>
  <?php } ?>
  <dl>
    <?php foreach ($facts as $fact) { if ($fact[1] === '' || $fact[1] === null || $fact[1] === false) continue; ?>
      <div class="cp-info__item">
        <dt><?php echo rtrim(strip_tags($fact[0]), ': '); ?></dt>
        <dd><?php echo $fact[1]; ?></dd>
      </div>
    <?php } ?>
  </dl>
</section>
<?php $facts_title = ''; ?>
