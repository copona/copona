<?php

namespace Copona\Model\Product;

use Copona\Database\OrmModel;

class Description extends OrmModel
{
    protected $table = 'product_description';

    protected $primaryKey = 'product_id';

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'product_id');
    }
}