<?php

namespace Copona\Model\Product;

use Copona\Database\OrmModel;

class Product extends OrmModel
{
    protected $table = 'product';

    protected $primaryKey = 'product_id';

    protected $dates = ['date_available'];

    public function descriptions()
    {
        return $this->hasMany(Description::class, 'product_id', 'product_id');
    }
}