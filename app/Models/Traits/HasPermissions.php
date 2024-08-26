<?php

namespace App\Models\Traits;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissionTo(string $key): void
    {
        $this->permissions()->firstOrCreate(compact('key'));

        $cacheKey = $this->getPermissionsCacheKey();
        Cache::forget($cacheKey);
        Cache::rememberForever($cacheKey, fn () => $this->permissions);
    }

    public function hasPermissionTo(string $key): bool
    {
        /** @var Collection $permissions */
        $permissions = Cache::get($this->getPermissionsCacheKey(), $this->permissions);

        return  $permissions
            ->where('key', '=', $key)
            ->isNotEmpty();
    }

    private function getPermissionsCacheKey(): string
    {
        return 'user::' . $this->id . '::permissions';
    }
}
