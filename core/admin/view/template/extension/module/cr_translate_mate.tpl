<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php e($heading_title); ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>

      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php e($button_cancel); ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
        <div class="btn-group" data-toggle="buttons" id="optionsGroup">
          <?php foreach (array('catalog'=>'frontend', 'admin'=>'backend') as $k=>$v ) { ?>
            <label class="btn btn-default <?php if($interface==$k){ echo 'active'; } ?>">
              <input type="radio" name="interface" value="<?php e($k); ?>" autocomplete="off" <?php if($interface=="$k"){ echo 'checked'; } ?>> <?php e(${'tab_'.$v}); ?>
            </label>
          <?php } // end foreach (array('catalog'=>'frontend', 'admin'=>'backend') as $k=>$v ) ?>
        </div>

        <label class="btn btn-primary">
          <input type="checkbox" name="notTranslated" id="notTranslated" autocomplete="off"> <?php e($text_not_translated_only); ?>
        </label>
      </div>
    </div><!-- end .container-fluid -->
  </div><!-- end .page-header -->

  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php e($error_warning); ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div><!-- end alert-danger -->
    <?php } ?>
    

    <div id="helpDiv" class="alert alert-info">
      <p><?php echo sprintf($text_help_errors, 'https://github.com/chrisrollins65/cr_translate_mate', 'http://www.opencart.com/index.php?route=extension/extension/info&extension_id=23098'); ?></p>
      <p><?php echo $text_help_hotkeys; ?></p>
      <p><?php echo $text_help_caution; ?></p>
    </div>

    <div id="translateFormsContainer">

      <table class="table table-striped table-hover table-condensed" id="transTable">
        <thead>
          <tr>
            <th class="pageCol"><?php e($text_page); ?></th>
            <th class="keyCol"><?php e($text_key); ?></th>
            <?php foreach ( $languages as $l ) { ?>
              <th><?php e($l['name']); ?></th>
            <?php } // end foreach ( $cr_langs as $l ) ?>
          </tr>
          <tr id="searchRow">
            <td id="pageSearchTD" class="pageCol">
              <select name="pageSearch" id="pageSearch" class="form-control">
                <option value="" selected="selected"><?php e($text_all_pages); ?></option>
                <?php echo $fileSelect; ?>
              </select>
            </td>
            <td id="keySearchTD" class="keyCol">
              <input type="text" name="keySearch" id="keySearch" class="form-control" placeholder="<?php e($text_search_keys); ?>">
            </td>
            <td colspan="<?php echo count($languages); ?>" id="textSearchTD">
              <input type="text" name="textSearch" id="textSearch" class="form-control" placeholder="<?php e($text_search_text); ?>">
            </td>
          </tr><!-- end #searchRow -->
        </thead>

        <tbody>
          <!-- Translation strings -->
          
        </tbody>
      </table><!-- end transTable -->

    </div><!-- end #translateFormsContainer -->

    <p id="noTextsFound"><?php e($text_no_texts_found); ?></div>

    <div id="loadInfoDiv">
      <h2 id="loadingTextsWait"><i class="fa fa-spinner fa-spin"></i> <?php e($text_loading_please_wait); ?></h2>
      <button id="loadMoreBtn" class="btn btn-lg btn-primary"><i class="fa fa-arrow-circle-o-down"></i> <?php e($text_load_more); ?></button>
      <button id="scrollToTopBtn" class="btn btn-lg btn-default"><i class="fa fa-arrow-up"></i></button>
    </div><!-- end #loadInfoDiv -->

  </div><!-- end .container-fluid -->
</div><!-- end #content -->

<div class="modal fade" id="errorModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body"></div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- for use in javascript -->
<div id="notTranslatedTemplate">
  <span class="notTranslatedSpan text-danger"><?php e($text_not_translated); ?></span>
</div>
<!-- javascript --><?php // declare php variables here ?>
<script type="text/javascript">
  var crtm = { // cr_translate_mate object
    url : "<?php echo html_entity_decode($action); ?>",
    error_error : "<?php e($error_error); ?>",
    error_unexpected : "<?php e($error_unexpected); ?>",
    text_save_translation : "<?php e($text_save_translation); ?>",
    text_cancel : "<?php e($text_cancel); ?>"
  };
</script>
<!-- end javascript -->
<?php echo $footer; ?>