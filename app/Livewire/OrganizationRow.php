<?php

namespace App\Livewire;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrganizationRow extends Component
{
    public Organization $organization;

    public function toggleArchive()
    {
        if ($this->organization->trashed()) {
            $this->organization->restore();
            $users = User::withTrashed()
                ->where('organization_id', $this->organization->id)
                ->restore();

        } else {
            $users = $this->organization->users;
            foreach ($users as $user) {
                $user->tokens()->delete();
                $user->delete();
            }
            $this->organization->delete();
        }
    }

    public function render()
    {
        return view('livewire.organization-row');
    }
}
