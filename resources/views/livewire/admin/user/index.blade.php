<div>
    <x-header title="Users" separator />

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
