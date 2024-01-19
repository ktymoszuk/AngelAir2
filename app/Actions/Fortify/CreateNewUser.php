<?php

namespace App\Actions\Fortify;

// use App\Models\User;

use App\Models\User;
use App\Mail\AvvisoNuovoUtente;
use Illuminate\Validation\Rule;
use App\Jobs\MailAmministratori;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        // $amministratori = User::where('Ruolo', 0)->get(); //Ottengo la mail degli amministratori del sistema

        //Invio una mail di avviso a tutti gli ammnistratori del sistema
        // foreach($amministratori as $amministratore){
            // Mail::to($amministratore->Email)->queue(new AvvisoNuovoUtente($input['Email']));
        // }

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
