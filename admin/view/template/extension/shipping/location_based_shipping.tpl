<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="button" onclick="$('#form').submit();" form="form-fedex" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>

  <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
  <?php } ?>

  <div class="container-fluid">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" class="form-horizontal">
      <table class="form table">
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="location_based_shipping_status" class="form-control">
                  <?php if ($location_based_shipping_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input class="form-control" type="text" name="location_based_shipping_sort_order" value="<?php echo $location_based_shipping_sort_order; ?>" size="1"  placeholder="<?php echo $entry_sort_order; ?>"/></td>
        </tr>
      </table>

      <?php $row = 0; ?>
      <?php $group_row = 1; ?>
      <?php if (is_array($location_based_shipping_cost)) { ?>
          <?php
          foreach ($location_based_shipping_cost as $group_id => $group) {
              ?>
              <?php if (is_array($group)) { ?>
                  <table class="location-based-shipping-shipping list table">
                    <thead>
                      <tr>
                        <td class="left"><?php echo $entry_country; ?></td>
                        <td class="left"><?php echo $entry_zone; ?></td>
                        <td class="left"><?php echo $entry_tax_class; ?></td>
                        <td><?php echo $entry_cost ?></td>
                        <td class="left"><?php echo $entry_title ?></td>
                        <td class="left"><?php echo $entry_show_address ?></td>
                        <td></td>
                      </tr>
                    </thead>
                    <h1>Group <?php echo $group_row ?></h1>
                    <?php foreach ($group as $czc) { ?>
                        <tbody id="location-based-shipping-cost-row<?php echo $row; ?>">
                          <tr>
                        <input type="hidden" name="location_based_shipping_cost[<?php echo $row; ?>][group]" value="<?php echo $group_row ?>">
                        <td class="left"><select class="form-control" name="location_based_shipping_cost[<?php echo $row; ?>][country_id]" id="country<?php echo $row; ?>" onchange="$('#zone<?php echo $row; ?>').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=0');">
                                <?php foreach ($countries as $country) { ?>
                                    <?php if ($country['country_id'] == $czc['country_id']) { ?>
                                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                                <?php } ?>
                            <?php } ?>
                          </select></td>
                        <td class="left"><select class="form-control" name="location_based_shipping_cost[<?php echo $row; ?>][zone_id]" id="zone<?php echo $row; ?>">
                          </select></td>
                        <td><select class="form-control" name="location_based_shipping_cost[<?php echo $row; ?>][tax_class_id]">
                            <option value="0"><?php echo $text_none; ?></option>
                            <?php foreach ($tax_classes as $tax_class) { ?>
                                <?php if ($czc['tax_class_id'] == $tax_class['tax_class_id']) { ?>
                                    <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                                <?php } else { ?>
                                    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                                <?php } ?>
                            <?php } ?>
                          </select></td>
                        <td><label class="control-label"><span data-toggle="tooltip" title="" data-original-title="<?php echo $text_rates_info; ?>"><?php echo $text_rates; ?></span></label><input class="form-control" type="text" size="15" name="location_based_shipping_cost[<?php echo $row; ?>][rates]" value="<?php echo $czc['rates']; ?>" /><br />
                          <label><span data-original-title="Shipping cost"><?php echo $text_cost; ?></span></label><input class="form-control" type="text" size="15" name="location_based_shipping_cost[<?php echo $row; ?>][cost]" value="<?php echo $czc['cost']; ?>" />
                        <td>
                            <?php foreach ($languages as $language) {
                                ?>
                              <div class="input-group">
                                <span class="input-group-addon lng-image">
                                  <img src="<?= HTTP_CATALOG ?>catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png" title="<?php echo $language['name']; ?>" />
                                </span>
                                <input class="form-control" placeholder="<?php echo $entry_title ?>" type="text" name="location_based_shipping_cost[<?php echo $row; ?>][title][<?php echo $language['language_id']; ?>]" value="<?php echo $czc['title'][$language['language_id']]; ?>" />
                              </div>
                          <?php } ?>
                        </td>
                        <td>
                          <label>
                              <?php if (isset($czc['show_address']) && $czc['show_address']) { ?>
                                <input type="checkbox" name="location_based_shipping_cost[<?php echo $row; ?>][show_address]" value="1" checked="checked" id="input-show-address" />
                            <?php } else { ?>
                                <input type="checkbox" name="location_based_shipping_cost[<?php echo $row; ?>][show_address]" value="1" id="input-show-address" />
                            <?php } ?>
                          </label>
                        </td>
                        <td class="left"><a onclick="$('#location-based-shipping-cost-row<?php echo $row; ?>').remove();" class="btn btn-danger"><?php echo $button_remove; ?></a></td>
                        </tr>
                        </tbody>
                        <?php $row++; ?>
                    <?php } ?>

                    <tfoot class="add-row-<?php echo $group_id ?>">
                      <tr>
                        <td colspan="5"></td>
                        <td class="left"><a onclick="addRow(<?php echo $group_id ?>);" class="btn btn-success">Add</a></td>
                      </tr>
                    </tfoot>
                  </table>
              <?php } ?>
              <?php
              $group_row++;
          }
          ?>
      <?php }
      ?>
      <span onclick="add_group()" class="btn btn-primary add-group">Add group</span>
    </form>
  </div>
</div>

<?php $row = 0; ?>
<?php foreach ($location_based_shipping_cost as $group_id => $group) { ?>
    <?php if (is_array($group)) foreach ($group as $location_based_shipping_cost) { ?>
            <script type="text/javascript"><!--
                  $('#zone<?php echo $row; ?>').load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=<?php echo $location_based_shipping_cost['country_id']; ?>&zone_id=<?php echo $location_based_shipping_cost['zone_id']; ?>');
                //--></script>
            <?php $row++; ?>
        <?php } ?>
<?php } ?>

<script type="text/javascript"><!--
                var row = <?php echo $row; ?>;
    var group_row = <?php echo $group_row; ?>;

    function add_group() {
        html = ' <h1>Group ' + group_row + '</h1>';
        html += '<table class="location-based-shipping-shipping list table">\
                    <thead>\
                      <tr>\
                        <td class="left"><?php echo $entry_country; ?></td>\
                        <td class="left"><?php echo $entry_zone; ?></td>\
                        <td class="left"><?php echo $entry_tax_class; ?></td>\
                        <td><?php echo $entry_cost ?></td>\
                        <td class="left"><?php echo $entry_title ?></td>\
                        <td></td>\
                      </tr>\
                    </thead>\n\
                    <tbody></tbody>';
        html += '<tfoot class="add-row-' + group_row + '">\
                      <tr>\
                        <td colspan="5"></td>\
                        <td class="left"><a onclick="addRow(' + group_row + ');" class="btn btn-success">Add</a></td>\
                      </tr>\
                    </tfoot></table>';
        $('.add-group').before(html);
        group_row++;
    }

    function addRow(group_id) {
        html = '<tbody id="location-based-shipping-cost-row' + row + '">';
        html += '<tr>';
        html += '<input type="hidden" name="location_based_shipping_cost[' + row + '][group]" value="' + group_id + '" >';
        html += '<td class="left"><select class="form-control" name="location_based_shipping_cost[' + row + '][country_id]" id="country' + row + '" onchange="$(\'#zone' + row + '\').load(\'index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=\' + this.value + \'&zone_id=0\');">';
<?php foreach ($countries as $country) { ?>
            html += '<option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';
<?php } ?>
        html += '</select></td>';
        html += '<td class="left"><select class="form-control" name="location_based_shipping_cost[' + row + '][zone_id]" id="zone' + row + '"></select></td>';
        html += '<td class="left"><select class="form-control" name="location_based_shipping_cost[' + row + '][tax_class_id]">';
        html += '<option value="0"><?php echo $text_none; ?></option>';
<?php foreach ($tax_classes as $tax_class) { ?>
            html += '<option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>';
<?php } ?>
        html += '</select></td>';
        html += '<td><label class="control-label"><span data-toggle="tooltip" title="" data-original-title="<?php echo $text_rates_info; ?>"><?php echo $text_rates; ?></span></label><input class="form-control" type="text" size="15" name="location_based_shipping_cost[' + row + '][rates]" value=""><br />\n\
        <label><span data-original-title="Shipping cost"><?php echo $text_cost; ?></span></label>\n\
    <input class="form-control" type="text" size="15" name="location_based_shipping_cost[' + row + '][cost]" value=""></td>';
        html += '<td>';
<?php foreach ($languages as $language) { ?>
            html += '<div class="input-group"><span class="input-group-addon lng-image"><img src="<?php echo HTTP_CATALOG ?>catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png" title="<?php echo $language['name']; ?>" /></span>';
            html += '<input class="form-control" placeholder="<?php echo $entry_title ?>" type="text" name="location_based_shipping_cost[' + row + '][title][<?php echo $language['language_id'] ?>]" value=""></div>';
<?php } ?>
        html += '</td>';
        html += '<td> <input type="checkbox" name="location_based_shipping_cost[<?php echo $row; ?>][show_address]" value="1" id="input-show-address" /></td>';
        html += '<td class="left"><a onclick="$(\'#location-based-shipping-cost-row' + row + '\').remove();" class="btn btn-danger"><?php echo $button_remove; ?></a></td>';
        html += '</tr>';
        html += '</tbody>';

        $('.add-row-' + group_id).before(html);

        $('#zone' + row).load('index.php?route=localisation/geo_zone/zone&token=<?php echo $token; ?>&country_id=' + $('#country' + row).attr('value') + '&zone_id=0');
        row++;
    }



//--></script>

<?php echo $footer; ?>