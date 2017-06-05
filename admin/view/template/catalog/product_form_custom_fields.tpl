<table id="attribute" class="table table-striped table-bordered table-hover">
  <thead>
  <tr>
    <td class="text-left"><?php echo $entry_attribute; ?></td>
    <td class="text-left"><?php echo $entry_text; ?></td>
    <td></td>
  </tr>
  </thead>
  <tbody>
  <?php $attribute_row = 0; ?>
  <?php foreach ($product_attributes as $product_attribute) { ?>
    <tr id="attribute-row<?php echo $attribute_row; ?>">
      <td class="text-left" style="width: 40%;"><input type="text" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $product_attribute['name']; ?>" placeholder="<?php echo $entry_attribute; ?>" class="form-control" />
        <input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>" /></td>
      <td class="text-left"><?php foreach ($languages as $language) { ?>
          <div class="input-group"><span class="input-group-addon lng-image"><img src="<?= HTTP_CATALOG ?>catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
            <textarea name="product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"><?php echo isset($product_attribute['product_attribute_description'][$language['language_id']]) ? $product_attribute['product_attribute_description'][$language['language_id']]['text'] : ''; ?></textarea>
          </div>
          <?php } ?></td>
      <td class="text-left"><button type="button" onclick="$('#attribute-row<?php echo $attribute_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
    </tr>
      <?php $attribute_row++; ?>
  <?php } ?>
  </tbody>
  <tfoot>
  <tr>
    <td colspan="2"></td>
    <td class="text-left"><button type="button" onclick="addAttribute();" data-toggle="tooltip" title="<?php echo $button_attribute_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
  </tr>
  </tfoot>
</table>