<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Columns of the Voyager-managed `appointments` table (there is no migration).
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $appointment_date
 * @property string|null $type_of_service
 * @property string|null $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Appointment extends Model
{

}
