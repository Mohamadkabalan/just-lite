<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The Voyager admin forms had no validation, so empty blogs, packages or
 * banners could be saved and showed as blank cards on the site. This adds a
 * "required" rule (and the form's asterisk) to the title and text fields that
 * every existing record already fills. Images stay optional: Voyager would
 * otherwise ask for a new upload on every edit. Only Voyager's form settings
 * (data_rows) change; no content is touched.
 */
return new class extends Migration
{
    /** @var array<string, array<int, string>> BREAD slug => required fields */
    private const REQUIRED = [
        'abouts' => ['title_en', 'title_ar', 'text_en', 'text_ar'],
        'bad-habits' => ['title_en', 'title_ar', 'text_en', 'text_ar'],
        'banners' => ['title_en', 'title_ar', 'text_en', 'text_ar'],
        'blogs' => ['title_en', 'title_ar', 'text_en', 'text_ar'],
        'info-details' => ['list_en', 'list_ar'],
        'infos' => ['title_en', 'title_ar'],
        'nutrition-special-features' => ['title_en', 'title_ar'],
        'packages' => ['name_en', 'name_ar', 'features_en', 'features_ar', 'price'],
        'results' => ['title_en', 'title_ar'],
        'services' => ['title_en', 'title_Ar', 'text_en', 'text_ar'],
        'testimonials' => ['text_en', 'text_ar'],
        'weekly-diet-plans' => ['title_en', 'title_ar', 'text_en', 'text_ar'],
    ];

    private const RULE = 'required';

    public function up(): void
    {
        foreach ($this->rows() as $row) {
            $details = $this->details($row->details);

            if (isset($details['validation'])) {
                continue; // Leave rules someone already set in the admin alone.
            }

            $details['validation'] = ['rule' => self::RULE];
            $this->save($row->id, $details, true);
        }
    }

    public function down(): void
    {
        foreach ($this->rows() as $row) {
            $details = $this->details($row->details);

            if (($details['validation']['rule'] ?? null) !== self::RULE) {
                continue;
            }

            unset($details['validation']);
            $this->save($row->id, $details, false);
        }
    }

    /**
     * @return \Illuminate\Support\Collection<int, object>
     */
    private function rows()
    {
        $rows = collect();

        foreach (self::REQUIRED as $slug => $fields) {
            $typeId = DB::table('data_types')->where('slug', $slug)->value('id');

            if ($typeId !== null) {
                $rows = $rows->merge(DB::table('data_rows')->where('data_type_id', $typeId)->whereIn('field', $fields)->get(['id', 'details']));
            }
        }

        return $rows;
    }

    /**
     * @return array<string, mixed>
     */
    private function details(?string $json): array
    {
        $details = json_decode($json ?? '', true);

        return is_array($details) ? $details : [];
    }

    /**
     * @param  array<string, mixed>  $details
     */
    private function save(int $id, array $details, bool $required): void
    {
        DB::table('data_rows')->where('id', $id)->update([
            // An empty array must stay a JSON object: Voyager reads details as one.
            'details' => json_encode($details === [] ? new stdClass : $details, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'required' => $required,
        ]);
    }
};
