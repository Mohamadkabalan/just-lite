<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

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
    use Prunable;

    /**
     * Booking requests older than the retention period in the privacy policy
     * (config site.retention_months), deleted by the scheduled model:prune.
     *
     * @return Builder<static>
     */
    public function prunable(): Builder
    {
        return static::query()->where('created_at', '<=', now()->subMonths((int) config('site.retention_months')));
    }
}
