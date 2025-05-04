<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;
use App\Models\User;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';
    public string $personalnr = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $this->validate([
            'personalnr' => ['required', 'integer' ],
        ]);
        session()->flash('status', 'Bitte warten ...');
        /* ALT
        $status = Password::sendResetLink(
            // $this->only('email')
            $this->only('personalnr')
        );
        */

        /* NEU */
        $status = Password::broker()->sendResetLink(
            // $this->only('email') // Die E-Mail-Adresse des Benutzers
            $this->only('personalnr')
        );
        /* NEU ENDE */

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('auth.Forgot your password? No problem. Just let us know your personal number and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink">
        <!-- Email Address -->

        <div>
            <x-input-label for="personalnr" :value="__('auth.personalnr')" />
            <x-text-input wire:model="personalnr" id="personalnr" class="block mt-1 w-full" type="text" name="personalnr" required autofocus />
            <x-input-error :messages="$errors->get('personalnr')" class="mt-2" />
        </div>


        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('auth.Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</div>
