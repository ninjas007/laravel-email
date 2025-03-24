<?php

namespace App\Actions\Fortify;

use App\Helpers\ListHelper;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $prefix = $this->createTableName(5, $user->id);

        $user->tbl_prefix = $prefix;
        $user->save();

        $this->duplicateTables($prefix);

        return $user;
    }

    private function duplicateTables($prefix)
    {
        $tables = ListHelper::getTables();

        foreach ($tables as $table) {
            $newTable = $prefix . '_' . $table;

            // Salin struktur tabel
            DB::statement("CREATE TABLE `$newTable` LIKE `$table`");
        }
    }

    private function createTableName($length, $userId)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return 'emlq_'.strtolower($randomString).$userId;
    }
}
