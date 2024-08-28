<div>
    <x-header title="Users" separator />

    <div class="flex my-4 justify-between">
        <div class="flex flex-row items-center space-x-4">
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
        
        <div class="flex flex-col space-y-4">
            <x-button class="btn-primary" label="Create" responsive icon="o-plus" />

            <x-checkbox label="Filter deleted users" wire:model.live="search_trash" right tight class="checkbox-primary" />
        </div>
    </div>

    <x-table :headers="$this->headers" :rows="$this->users">
        {{-- TABLE HEADER --}}
        @scope('header_id', $header)
            {{-- <x-table.th :$header name="id" /> --}}
            <div wire:click="sortBy('id', '{{ $header['sortDirection'] == 'asc' ? 'desc' : 'asc' }}')" class="cursor-pointer">
                {{ $header['label'] }}
                @if ($header['sortByColumn'] == 'id')
                    <x-icon :name="$header['sortDirection'] == 'asc' ? 'o-chevron-down': 'o-chevron-up'" />
                @endif
            </div>
        @endscope

        @scope('header_name', $header)
            {{-- <x-table.th :$header name="name" /> --}}
            <div wire:click="sortBy('name', '{{ $header['sortDirection'] == 'asc' ? 'desc' : 'asc' }}')" class="cursor-pointer">
                {{ $header['label'] }}
                @if ($header['sortByColumn'] == 'name')
                    <x-icon :name="$header['sortDirection'] == 'asc' ? 'o-chevron-down': 'o-chevron-up'" />
                @endif
            </div>
        @endscope

        @scope('header_email', $header)
            {{-- <x-table.th :$header name="email" /> --}}
            <div wire:click="sortBy('email', '{{ $header['sortDirection'] == 'asc' ? 'desc' : 'asc' }}')" class="cursor-pointer">
                {{ $header['label'] }}
                @if ($header['sortByColumn'] == 'email')
                    <x-icon :name="$header['sortDirection'] == 'asc' ? 'o-chevron-down': 'o-chevron-up'" />
                @endif
            </div>
        @endscope

        {{-- TABLE BODY --}}
        @scope('cell_permissions', $user)
            @foreach ($user->permissions as $permission)
                <x-badge :value="$permission->key" class="badge-primary" />
            @endforeach
        @endscope

        @scope('actions', $user)
            <div class="flex flex-row">
                <x-button icon="o-pencil-square" spinner class="btn-sm" />
                @if ($user->trashed())
                    <x-button icon="o-arrow-left-start-on-rectangle" spinner class="btn-sm" />
                @else
                    <x-button icon="o-trash" spinner class="btn-sm" />
                @endif
            </div>
        @endscope
    </x-table>
</div>
