<?php

namespace App\Traits\Models;

use App\Enums\ECan;
use App\Models\Permission;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasPermissions
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissionTo(ECan|string $key): void
    {
        $permissionKey = $key instanceof ECan ? $key->value : $key;

        $this
            ->permissions()
            ->firstOrCreate([
                'key' => $permissionKey,
            ]);

        $cacheKey = $this->getPermissionsCacheKey();
        Cache::forget($cacheKey);
        Cache::rememberForever($cacheKey, fn() => $this->permissions);
    }

    public function hasPermissionTo(ECan|string $key): bool
    {
        $permissionKey = $key instanceof ECan ? $key->value : $key;

        /** @var Collection $permissions */
        $permissions = Cache::get($this->getPermissionsCacheKey(), $this->permissions);

        return  $permissions
            ->where('key', '=', $permissionKey)
            ->isNotEmpty();
    }

    private function getPermissionsCacheKey(): string
    {
        return 'user::' . $this->id . '::permissions';
    }
}
