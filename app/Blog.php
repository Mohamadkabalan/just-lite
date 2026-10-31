<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Columns of the Voyager-managed `blogs` table (there is no migration).
 *
 * @property int $id
 * @property string|null $title_en
 * @property string|null $title_ar
 * @property string|null $text_en
 * @property string|null $text_ar
 * @property string|null $image
 * @property int|null $is_receipt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Blog extends Model
{

}
