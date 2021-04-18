<?php

namespace App\Models;

use App\Models\Traits\Attribute\MenuAttribute;
use App\Models\Traits\Connection\AuthConnection;
use App\Models\Traits\Method\MenuMethod;
use App\Models\Traits\Relationship\MenuRelationship;
use Database\Factories\MenuFactory;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Menu extends Model
{
    use AuthConnection,
        CascadeSoftDeletes,
        HasFactory,
        MenuAttribute,
        MenuMethod,
        MenuRelationship,
        SoftDeletes,
        Userstamps;

    protected $cascadeDeletes = ['children'];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $guarded = [];

    protected $appends = ['grid'];

    protected $with = 'icon';

    private function cleanSlug($slug)
    {
        $dirty = [
            config('ui.external_iframe_prefix'),
            config('ui.internal_iframe_prefix'),
            '#disabled_link#',
            '?externallink=',
        ];

        return ltrim(str_replace($dirty, '', $slug), '/');
    }

    protected static function newFactory()
    {
        return MenuFactory::new();
    }

    protected function getIconId($icon)
    {
        if (is_int($icon)) {
            return Icon::query()->find($icon) ? $icon : null;
        }

        $id = Icon::query()->where('title', $icon)->value('id');

        if ($id) {
            return $id;
        }

        $icon = Icon::query()->create([
            'title' => $icon,
            'source' => 'FontAwesome',
            'version' => '5',
        ]);

        return $icon->id;
    }
}
