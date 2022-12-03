<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Valuestore\Valuestore;

class PasswordRule implements Rule
{
    protected $lastPasswords = 5;

    protected $messages = [];

    protected $userId;

    /**
     * On obtient l'utilisateur recherché grâce à l'email renseigné
     * @param $email
     */
    public function __construct($email)
    {
        $this->userId = DB::table('users')->select('id')->where('email', $email)->first()->id;;
    }

    /**
     * Determine if the validation rule passes.
     * Ici cela permet de vérifier que l'utilisateur n'utilise pas les x derniers mots de passe
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $old_passwords_from_history = DB::table('user_passwords')
            ->select('password')->where('user_id', $this->userId)->get();

        $valuestore = Valuestore::make('settings.json');
        $maxReuse = $valuestore->get('password_reusable', $this->lastPasswords);
        $counter = 0;
        $offset = count($old_passwords_from_history) - 1 - $maxReuse;

        foreach ($old_passwords_from_history as $hashed_password) {
            $hashed_password = $hashed_password->password;
            if($counter > $offset) {
                if (Hash::check($value, $hashed_password)) {
                    return false;
                }
            }
            $counter++;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $valuestore = Valuestore::make('settings.json');
        $maxReuse = $valuestore->get('password_reusable', $this->lastPasswords);
        return 'You can\'t reuse last '.$maxReuse.' passwords.';
    }
}
