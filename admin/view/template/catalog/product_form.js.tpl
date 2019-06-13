<script type="text/javascript">
    // Manufacturer
    $('input[name=\'manufacturer\']').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function (json) {
                    json.unshift({
                        manufacturer_id: 0,
                        name: '<?php echo $text_none; ?>'
                    });
                    response($.map(json, function (item) {
                        return {
                            label: item['name'],
                            value: item['manufacturer_id']
                        }
                    }));
                }
            });
        },
        'select': function (item) {
            $('input[name=\'manufacturer\']').val(item['label']);
            $('input[name=\'manufacturer_id\']').val(item['value']);
        }
    });

    function remove_product(e, product_id) {
        //console.log($(e).closest('.list-group-item'));
        $(e).closest('.list-group-item').remove();

        a = $('#input-product_autocomplete');
        a.attr('data-id', a.attr('data-id').replace(',' + product_id + ',', ','));
    }

// Product Group
    var product_group_row = "<?= $product_group_row ?>";

    $('#product-group').on('click', '.list-group-item.row', function () {
        $('input[type=radio]', this).prop('checked', 'checked');
    });

    $('#product-group').on('click', '.list-group-item.row a', function (e) {
        e.stopPropagation();
    });

    $('input[name=\'product_group_autocomplete\']').autocomplete({
        'keepDropdown': true,
        'source': function (request, response) {
            var attribute = document.getElementById('input-product_autocomplete').getAttribute('data-id');
            if (attribute == null) {
                attribute = '';
            }

            $.ajax({
                url: 'index.php?route=catalog/product/product_group_autocomplete&token=<?php echo $token; ?>&products=' + attribute + '&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        //console.log(item);
                        return {
                            label: item['name'],
                            model: item['model'],
                            value: item['product_id'],
                            price: item['price']
                        }
                    }));
                }
            });
        },
        'select': function (item) {
            var checked = '';
            if (product_group_row == 0) {
                checked = 'checked="checked"';
            }

            html = '<li class="list-group-item row">\n\
                      <div class="col-sm-1"><input ' + checked + ' name="main_product_id" type="radio"  value="' + item['value'] + '"></div>\n\
                      <div class="col-sm-4"><a href="index.php?route=catalog/product/edit&product_id=' + item['value']
                    + '&token=<?php echo $token; ?>" target="_blank">' + item['label'] + '</a>\n\n\
                      </div>\n\
                      <div class="col-sm-3">' + item['model'] + '</div>\n\
                      <div class="col-sm-3">' + item['price'] + '</div>\n\
                      <input class="product_id" name="product_group[' + product_group_row + '][product_id]"type="hidden" value="' + item['value'] + '">\n\
                      <div class="col-sm-1"><button onclick="remove_product(this, ' + item['value'] + ')" type="button" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button>\n\
                      </div>\n\
                    </li>';
            $('#product-group').append(html);
            $('input[name=\'product_group_autocomplete\']').val('');
            // check, if this product has benn already added, and then display results
            var attr = document.getElementById('input-product_autocomplete').getAttribute('data-id');
            if (attr) {
                attr = attr + item['value'] + ',';
            } else {
                attr = "," + item['value'] + ",";
            }
            document.getElementById('input-product_autocomplete').setAttribute('data-id', attr);
            product_group_row++;
        }
    });

    // Category
    $('input[name=\'category\']').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {
                            label: item['name'],
                            value: item['category_id']
                        }
                    }));
                }
            });
        },
        'select': function (item) {
            $('input[name=\'category\']').val('');
            $('#product-category' + item['value']).remove();
            $('#product-category').append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '" /></div>');
        }
    });
    $('#product-category').delegate('.fa-minus-circle', 'click', function () {
        $(this).parent().remove();
    });
    // Filter
    $('input[name=\'filter\']').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {
                            label: item['name'],
                            value: item['filter_id']
                        }
                    }));
                }
            });
        },
        'select': function (item) {
            $('input[name=\'filter\']').val('');
            $('#product-filter' + item['value']).remove();
            $('#product-filter').append('<div id="product-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_filter[]" value="' + item['value'] + '" /></div>');
        }
    });
    $('#product-filter').delegate('.fa-minus-circle', 'click', function () {
        $(this).parent().remove();
    });
    // Downloads
    $('input[name=\'download\']').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/download/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {
                            label: item['name'],
                            value: item['download_id']
                        }
                    }));
                }
            });
        },
        'select': function (item) {
            $('input[name=\'download\']').val('');
            $('#product-download' + item['value']).remove();
            $('#product-download').append('<div id="product-download' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_download[]" value="' + item['value'] + '" /></div>');
        }
    });
    $('#product-download').delegate('.fa-minus-circle', 'click', function () {
        $(this).parent().remove();
    });
    // Related
    $('input[name=\'related\']').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {
                            label: item['name'],
                            value: item['product_id']
                        }
                    }));
                }
            });
        },
        'select': function (item) {
            $('input[name=\'related\']').val('');
            $('#product-related' + item['value']).remove();
            $('#product-backway' + item['value']).remove();
            $('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');
            $('#product-backway').append('<div id="product-backway' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_backway[]" value="' + item['value'] + '" /></div>');
        }
    });
    $('#product-related, #product-backway').delegate('.fa-minus-circle', 'click', function () {
        $(this).parent().remove();
    });
    // Attribute rows
    var attribute_row = '<?php echo $attribute_row; ?>';
    function addAttribute() {
        html = '<tr id="attribute-row' + attribute_row + '">';
        html += '  <td class="text-left" style="width: 20%;"><input type="text" name="product_attribute[' + attribute_row + '][name]" value="" placeholder="<?php echo $entry_attribute; ?>" class="form-control" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
        html += '  <td class="text-left">';
<?php foreach ($languages as $language) { ?>
            html += '<div class="input-group"><span class="input-group-addon lng-image"><img src="<?= HTTP_CATALOG ?>catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png" title="<?php echo $language['name']; ?>" /></span><textarea name="product_attribute[' + attribute_row + '][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control"></textarea></div>';
<?php } ?>
        html += '  </td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#attribute-row' + attribute_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';
        $('#attribute tbody').append(html);
        attributeautocomplete(attribute_row);
        attribute_row++;
    }

    function attributeautocomplete(attribute_row) {
        $('input[name=\'product_attribute[' + attribute_row + '][name]\']').autocomplete({
            'source': function (request, response) {
                $.ajax({
                    url: 'index.php?route=catalog/attribute/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function (json) {
                        response($.map(json, function (item) {
                            return {
                                category: item.attribute_group,
                                label: item.name,
                                value: item.attribute_id
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'product_attribute[' + attribute_row + '][name]\']').val(item['label']);
                $('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').val(item['value']);
            }
        });
    }

    $('#attribute tbody tr').each(function (index, element) {
        attributeautocomplete(index);
    });
    // Options rows
    var option_row = <?php echo $option_row; ?>;
    $('input[name=\'option\']').autocomplete({
        'source': function (request, response) {
            $.ajax({
                url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function (json) {
                    response($.map(json, function (item) {
                        return {
                            category: item['category'],
                            label: item['name'],
                            value: item['option_id'],
                            type: item['type'],
                            option_value: item['option_value']
                        }
                    }));
                }
            });
        },
        'select': function (item) {
            html = '<div class="tab-pane" id="tab-option' + option_row + '">';
            html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
            html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + item['label'] + '" />';
            html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + item['value'] + '" />';
            html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + item['type'] + '" />';
            html += '	<div class="form-group">';
            html += '	  <label class="col-sm-2 control-label" for="input-required' + option_row + '"><?php echo $entry_required; ?></label>';
            html += '	  <div class="col-sm-10"><select name="product_option[' + option_row + '][required]" id="input-required' + option_row + '" class="form-control">';
            html += '	      <option value="1"><?php echo $text_yes; ?></option>';
            html += '	      <option value="0"><?php echo $text_no; ?></option>';
            html += '	  </select></div>';
            html += '	</div>';
            if (item['type'] == 'text') {
                html += '	<div class="form-group">';
                html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control" /></div>';
                html += '	</div>';
            }

            if (item['type'] == 'textarea') {
                html += '	<div class="form-group">';
                html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                html += '	  <div class="col-sm-10"><textarea name="product_option[' + option_row + '][value]" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control"></textarea></div>';
                html += '	</div>';
            }

            if (item['type'] == 'file') {
                html += '	<div class="form-group" style="display: none;">';
                html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control" /></div>';
                html += '	</div>';
            }

            if (item['type'] == 'date') {
                html += '	<div class="form-group">';
                html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                html += '	  <div class="col-sm-3"><div class="input-group date"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
                html += '	</div>';
            }

            if (item['type'] == 'time') {
                html += '	<div class="form-group">';
                html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                html += '	  <div class="col-sm-10"><div class="input-group time"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
                html += '	</div>';
            }

            if (item['type'] == 'datetime') {
                html += '	<div class="form-group">';
                html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                html += '	  <div class="col-sm-10"><div class="input-group datetime"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value' + option_row + '" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
                html += '	</div>';
            }

            if (item['type'] == 'select' || item['type'] == 'radio' || item['type'] == 'checkbox' || item['type'] == 'image') {
                html += '<div class="table-responsive">';
                html += '  <table id="option-value' + option_row + '" class="table table-striped table-bordered table-hover">';
                html += '  	 <thead>';
                html += '      <tr>';
                html += '        <td class="text-left"><?php echo $entry_option_value; ?></td>';
                html += '        <td class="text-right"><?php echo $entry_quantity; ?></td>';
                html += '        <td class="text-left"><?php echo $entry_subtract; ?></td>';
                html += '        <td class="text-right"><?php echo $entry_price; ?></td>';
                html += '        <td class="text-right"><?php echo $entry_option_points; ?></td>';
                html += '        <td class="text-right"><?php echo $entry_weight; ?></td>';
                html += '        <td></td>';
                html += '      </tr>';
                html += '  	 </thead>';
                html += '  	 <tbody>';
                html += '    </tbody>';
                html += '    <tfoot>';
                html += '      <tr>';
                html += '        <td colspan="6"></td>';
                html += '        <td class="text-left"><button type="button" onclick="addOptionValue(' + option_row + ');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
                html += '      </tr>';
                html += '    </tfoot>';
                html += '  </table>';
                html += '</div>';
                html += '  <select id="option-values' + option_row + '" style="display: none;">';
                for (i = 0; i < item['option_value'].length; i++) {
                    html += '  <option value="' + item['option_value'][i]['option_value_id'] + '">' + item['option_value'][i]['name'] + '</option>';
                }

                html += '  </select>';
                html += '</div>';
            }

            $('#tab-option .tab-content').append(html);
            $('#option > li:last-child').before('<li><a href="#tab-option' + option_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick=" $(\'#option a:first\').tab(\'show\');$(\'a[href=\\\'#tab-option' + option_row + '\\\']\').parent().remove(); $(\'#tab-option' + option_row + '\').remove();"></i>' + item['label'] + '</li>');
            $('#option a[href=\'#tab-option' + option_row + '\']').tab('show');
            $('[data-toggle=\'tooltip\']').tooltip({
                container: 'body',
                html: true
            });
            $('.date').datetimepicker({
                pickTime: false
            });
            $('.time').datetimepicker({
                pickDate: false
            });
            $('.datetime').datetimepicker({
                pickDate: true,
                pickTime: true
            });
            option_row++;
        }
    });
    // Options Values
    var option_value_row = <?php echo $option_value_row; ?>;
    function addOptionValue(option_row) {
        html = '<tr id="option-value-row' + option_value_row + '">';
        html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]" class="form-control">';
        html += $('#option-values' + option_row).html();
        html += '  </select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
        html += '  <td class="text-right">';
        html +='<input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" />'
        html +='<input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][description]" value="" placeholder="Description" class="form-control" />'
        html +='<input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][article]" value="" placeholder="Article" class="form-control" />'
        html += '</td>';
        html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]" class="form-control">';
        html += '    <option value="1"><?php echo $text_yes; ?></option>';
        html += '    <option value="0"><?php echo $text_no; ?></option>';
        html += '  </select></td>';
        html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]" class="form-control">';
        html += '    <option value="+">+</option>';
        html += '    <option value="-">-</option>';
        html += '  </select>';
        html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
        html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]" class="form-control">';
        html += '    <option value="+">+</option>';
        html += '    <option value="-">-</option>';
        html += '  </select>';
        html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" placeholder="<?php echo $entry_points; ?>" class="form-control" /></td>';
        html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]" class="form-control">';
        html += '    <option value="+">+</option>';
        html += '    <option value="-">-</option>';
        html += '  </select>';
        html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" placeholder="<?php echo $entry_weight; ?>" class="form-control" /></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" rel="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';
        $('#option-value' + option_row + ' tbody').append(html);
        $('[rel=tooltip]').tooltip();
        option_value_row++;
    }

    // Discount rows
    var discount_row = <?php echo $discount_row; ?>;
    function addDiscount() {
        html = '<tr id="discount-row' + discount_row + '">';
        html += '  <td class="text-left"><select name="product_discount[' + discount_row + '][customer_group_id]" class="form-control">';
<?php foreach ($customer_groups as $customer_group) { ?>
            html += '    <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
<?php } ?>
        html += '  </select></td>';
        html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>';
        html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
        html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" /></td>';
        html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
        html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_discount[' + discount_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';
        $('#discount tbody').append(html);
        $('.date').datetimepicker({
            pickTime: false
        });
        discount_row++;
    }

    // Special Rows
    var special_row = <?php echo $special_row; ?>;
    function addSpecial() {
        html = '<tr id="special-row' + special_row + '">';
        html += '  <td class="text-left"><select name="product_special[' + special_row + '][customer_group_id]" class="form-control">';
<?php foreach ($customer_groups as $customer_group) { ?>
            html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo addslashes($customer_group['name']); ?></option>';
<?php } ?>
        html += '  </select></td>';
        html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][priority]" value="" placeholder="<?php echo $entry_priority; ?>" class="form-control" /></td>';
        html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" />\n\
        <input data-toggle="tooltip" title="<?= $label_price_with_base_vat ?>" type="text" name="" value="" placeholder="<?= $label_price_with_base_vat ?>" class="price-vat form-control" />\n\
        </td>';
        html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_start]" value="" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
        html += '  <td class="text-left" style="width: 20%;"><div class="input-group date"><input type="text" name="product_special[' + special_row + '][date_end]" value="" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#special-row' + special_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';
        $('#special tbody').append(html);
        $('.date').datetimepicker({
            pickTime: false
        });
        special_row++;
    }

    // Images
    var image_row = <?php echo $image_row; ?>;
    function addImage() {
        html = '<tr id="image-row' + image_row + '">';
        html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
        html += '<td class="text-right">'
<?php foreach ($languages as $language) { ?>
            html += '<div class="input-group">';
            html += '<span class="input-group-addon lng-image">';
            html += '  <img src="<?= HTTP_CATALOG ?>catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png">';
            html += '</span>'
            html += '<input type = "text" name = "product_image[' + image_row + '][description][<?php echo $language['language_id']; ?>][description]" value = "" placeholder = "<?php echo $entry_additional_image_description; ?>" class = "form-control" / >'
            html += '</div>'
<?php } ?>
        html += '</td>';
        html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';
        $('#images tbody').append(html);
        image_row++;
    }

    // Videos
    var video_row = <?php echo $video_row; ?>;
    function addVideo() {
        html = '<tr id="video-row' + video_row + '">';
        html += '<td class="text-right">'
<?php foreach ($languages as $language) { ?>
            html += '<div class="input-group">';
            html += '<span class="input-group-addon lng-image">';
            html += '  <img src="<?= HTTP_CATALOG ?>catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png">';
            html += '</span>'
            html += '<input type = "text" name = "content_meta[product_video][' + video_row + '][video][<?php echo $language['language_id']; ?>]" value = "" placeholder = "<?php echo $entry_video_link; ?>" class = "form-control" / >'
            html += '</div>'
<?php } ?>
        html += '</td>';
        html += '  <td class="text-right"><input type="text" name="content_meta[product_video][' + video_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#video-row' + video_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';

        html += '</tr>';
        $('#videos tbody').append(html);
        video_row++;
    }

    // Recurring
    var recurring_row = <?php echo $recurring_row; ?>;
    function addRecurring() {
        html = '<tr id="recurring-row' + recurring_row + '">';
        html += '  <td class="left">';
        html += '    <select name="product_recurring[' + recurring_row + '][recurring_id]" class="form-control">>';
<?php foreach ($recurrings as $recurring) { ?>
            html += '      <option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>';
<?php } ?>
        html += '    </select>';
        html += '  </td>';
        html += '  <td class="left">';
        html += '    <select name="product_recurring[' + recurring_row + '][customer_group_id]" class="form-control">>';
<?php foreach ($customer_groups as $customer_group) { ?>
            html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
<?php } ?>
        html += '    <select>';
        html += '  </td>';
        html += '  <td class="left">';
        html += '    <a onclick="$(\'#recurring-row' + recurring_row + '\').remove()" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>';
        html += '  </td>';
        html += '</tr>';
        $('#tab-recurring table tbody').append(html);
        recurring_row++;
    }
</script>
<script>
    /* Javascript price input ease */

    var tax_rates = <?= json_encode($tax_rates); ?>;
    ease_rate = (tax_rates[0].rate / 100) + 1;

    $(function () {
        var $price = $('input[name=\'price\']');
        var $price_vat = $('input[name=\'price-vat\']');
        var vat = Number(ease_rate);
        // prive without TAX
        $price.on('keyup', function () {
            var res = (Number($price.val().replace(/,/g, ".")) * vat).toFixed(4);
            $price_vat.val(res.replace(/,/g, "."));
        });
        // prive with TAX
        $price_vat.on('keyup', function () {
            var res = (Number($price_vat.val().replace(/,/g, ".")) / vat).toFixed(4);
            $price.val(res.replace(/,/g, "."));
        });
    });

    $(function () {
        var e = $.Event('keyup');
        $('input[name=\'price\']').trigger(e);
        window.somethingChanged = false;
    });

    /* SPECIAL */

    $("#form-product").on('keyup', "input[name^=\'product_special\']", 'keyup', function () {
        $(this).siblings('input.price-vat').val(($(this).val() * Number(ease_rate)).toFixed(4));
    });

    $("#form-product").on('keyup', 'input.price-vat', function () {
        $(this).siblings("input[name^=\'product_special']").val(($(this).val() / Number(ease_rate)).toFixed(4));
    });
    $("input[name^=\'product_special'], input.price-vat").trigger('keyup');
</script>
