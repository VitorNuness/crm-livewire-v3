<div>
    <x-header title="Users" separator />

    <div class="flex my-4 justify-between">
        <div class="flex flex-row space-x-4">
            <x-input label="Search by name or email" wire:model.live.debounce="search" clearable icon="o-magnifying-glass" class="!px-10"/>

            <x-choices
                class="!h-3"
                label="Search by permissions"
                wire:model.live.debounce="search_permissions"
                :options="$permissionsSearchable"
                option-label="key"
                clearable
            />
        </div>

        <x-button class="btn-primary" label="Create" responsive icon="o-plus" />
    </div>

    <x-table :headers="$this->headers" :rows="$this->users">
        @scope('cell_permissions', $user)
            @foreach ($user->permissions as $permission)
                <x-badge :value="$permission->key" class="badge-primary" />
            @endforeach
        @endscope

        @scope('actions', $user)
            <x-button icon="o-pencil-square" spinner class="btn-sm" />
        @endscope
    </x-table>
</div>
