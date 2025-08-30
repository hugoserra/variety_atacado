<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $role = 'admin';
    public string $password = '';
    public string $password_confirmation = '';

    public $user_count;

    public function mount()
    {
        $this->user_count = User::count();
        if (($this->user_count != 0 && !Auth::check()) || Auth::user()?->role == "user")
            return $this->redirect('login');
    }

    public function register(): void
    {
        $user_count = User::count();
        if (($user_count != 0 && !Auth::check()) || Auth::user()?->role == "user")
            return;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'role' => ['required', 'string', 'lowercase'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        event(new Registered(($user = User::create($validated))));

        if (!Auth::check())
            Auth::login($user);
        else
            $this->dispatch('saved-popup', "Novo Usuário Criado!");

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}
