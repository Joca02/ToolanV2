<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserRegistrationService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;

    protected $redirectTo = '/home';
    private $registrationService;


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'gender' => ['required', 'string', 'in:male,female'],
        ]);
    }
    public function __construct(UserRegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
       // $this->middleware('guest');
    }

    public function checkInputField(Request $request){
        $fieldName=$request->input("fieldName");
        $fieldValue=$request->input("fieldValue");
        $isAvailable=false;
        if($fieldName=='username')
            $isAvailable= $this->registrationService->isUsernameAvailable($fieldValue);
        else if($fieldName=='email')
            $isAvailable= $this->registrationService->isEmailAvailable($fieldValue);
        return response()->json(['available' => $isAvailable]);
    }
    protected function create(Request $request)
    {
        $data=$request->all();
        $this->registrationService->registerUser($data);

        return view('afterRegistrationView',['email'=>$data['email']]);
    }
}
