<?php

namespace Copona\Database;

use Illuminate\Database\Eloquent\Model;

class OrmModel extends Model
{
    const CREATED_AT = 'date_added';

    const UPDATED_AT = 'date_modified';
}