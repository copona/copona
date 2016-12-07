function remove_product(e, product_id) {
	//console.log($(e).closest('.list-group-item'));
	$(e).closest('.list-group-item').remove();
}

// Product Group
var product_group_row = "<?= $product_group_row ?>";

$('#product-group').on('click', '.list-group-item.row', function () {
	$('input[type=radio]', this).prop('checked', 'checked');
});

$('#product-group').on('click', '.list-group-item.row a', function () {
	e.stopPropagation();
});

$('input[name=\'product_group_autocomplete\']').autocomplete({
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
			attr = attr + ',' + item['value'];
		} else {
			attr = item['value'];
		}
		document.getElementById('input-product_autocomplete').setAttribute('data-id', attr);
		product_group_row++;
	}
});