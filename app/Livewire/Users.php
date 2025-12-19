<?php

namespace App\Livewire;

use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Users extends Component
{
    use WithFileUploads, WithPagination;

    #[Url]
    public ?string $search = '';

    #[validate('required|min:3')]
    public $name = '';

    #[validate('required|email:dns|unique:users')]
    public $email = '';

    #[validate('required|min:3')]
    public $password = '';

    #[Validate('image|max:5000|nullable')]
    public $avatar;

    public function createUser()
    {
        $validated = $this->validate();

        $validated['password'] = Hash::make($this->password);

        if($this->avatar){
            $validated['avatar'] = $this->avatar->store('avatar','public');
        }

        User::create($validated);

        $this->reset();

        session()->flash('success', 'User berhasil ditambahkan');
    }

    public function render()
    {
        return view('livewire.users', [
            'title' => 'User page',
            'users' => User::where('name','LIKE','%'. $this->search . '%')->latest()->paginate(6)
        ]);
    }
}
