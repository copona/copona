<!-- Loaded text rows -->
<?php foreach ( $texts as $page=>$strings ) { ?>
  <?php foreach ( $strings as $key=>$translations ) { ?>
    <tr class="textRow" data-page="<?php e($page); ?>">
      <td class="pageCol"><?php e($page == '_main_lang_file' ? $text_main_lang_file : $page); ?></td>
      <td class="keyCol"><?php e($key); ?></td>
      <?php foreach ( $languages as $l ) { ?>
        <td class="translationCol" data-lang="<?php e($l['directory']); ?>">
        <div class="transDiv"><?php 
          echo (isset($translations[$l['directory']]) && $translations[$l['directory']] != '') ?
            hs($translations[$l['directory']]) : '<span class="notTranslatedSpan text-danger">'.h($text_not_translated).'</span>';
        ?></div><!-- end .transDiv -->
        </td>
      <?php } // end foreach ( $languages as $l ) ?>
    </tr>
  <?php } // end foreach ( $strings as $key=>$translations ) ?>
<?php } // end foreach ( $texts as $page=>$strings ) ?>
<!-- End text rows -->