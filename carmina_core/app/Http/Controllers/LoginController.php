<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Azure;

class LoginController extends Controller
{
    public function login()
    {
        return Azure::redirect();
    }

    public function handleCallback()
    {
        try {
            $token = Azure::getAccessToken('authorization_code', [
                'code' => $_GET['code'],
                'resource' => 'https://graph.microsoft.com',
            ]);
            Log::info("Login Azure process started");
            Log::info($token);
            // We got an access token, let's now get the user's details
            $me = Azure::get("me", $token);
             // Use this to interact with an API on the users behalf
            $me_string = json_encode($me);
            $authUser = $this->findOrCreateUser($me);

            auth()->login($authUser, true);

            session([
                'azure_access_token' => $token->getToken(),
                'azure_id_token' => $me["mail"],
                'azure_string_decoded_token' => $me_string
            ]);

            return redirect(
                route('home')
            );
        } catch (\Exception $e) {
            Log::error('Error in Azure login: ' . $e->getMessage());
            abort(500, 'Error in Azure login: ' . $e->getMessage());
        }
    }

    protected function findOrCreateUser($user)
    {
        $user_class = config('oauth2azure.user_class');
		$authUser = $user_class::where('email', $user['mail'])->first();

        if ($authUser) {
            return $authUser;
        }

        try {
            \DB::beginTransaction();
            //register the user
            $new_user = new $user_class;
            $new_user->name = $user['displayName'];
            $new_user->email = $user['mail'];
            $new_user->azure_id = $user['id'];
            $new_user->password = Hash::make('123456789');
            $new_user->rol = 1;
            $new_user->save();
            //asignamos tiposcliente al usuario recien creado
            $tipos_cliente = ["Gran-Distribución","HORECA","Mayorista","Minorista","Zyrcular"];

            $user_class::actualizarPermisos($new_user->id, $tipos_cliente);
            \DB::commit();
            return $new_user;
        } catch (\Throwable $th) {
            \DB::rollback();
            Log::error("Error al crear el usuario: ".$th);
            return null;
        }
    }

    public function logout()
    {
        $redirect_url = route('login');
        return redirect(Azure::getLogoutUrl($redirect_url));
    }
}
