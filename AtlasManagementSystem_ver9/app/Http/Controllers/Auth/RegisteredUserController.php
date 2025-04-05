<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use DB;

use App\Models\Users\Subjects;
use App\Models\Users\User;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $subjects = Subjects::all();
        return view('auth.register.register', compact('subjects'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {


        //バリデーション設定
        $validatedData = $request->validate([
              'over_name' => 'required|string|max:10',
              'under_name' => 'required|string|max:10',
              'over_name_kana' => 'required|string|regex:/^[ァ-ヶー　]+$/u|max:30',
              'under_name_kana' => 'required|string|regex:/^[ァ-ヶー　]+$/u|max:30',
              'mail_address' => 'required|string|email:strict,dns|max:100|unique:users,email',
              'sex' => 'required|in:1,2,3',
              'birth_day' => 'required|date|after_or_equal:2000-01-01|before_or_equal:today',
              'role' => 'required|in:1,2,3,4',
              'password' => 'required|min:8|max:30|confirmed',
              'password_confirmation' => 'required|min:8|max:30',
        ], $message= [

            'mail_address.required' => '※メール形式で入力してください',
        ]);
        //ここまで


        DB::beginTransaction();
        try{
            $old_year = $request->old_year;
            $old_month = $request->old_month;
            $old_day = $request->old_day;
            $data = $old_year . '-' . $old_month . '-' . $old_day;
            $birth_day = date('Y-m-d', strtotime($data));
            $subjects = $request->subject;

            $user_get = User::create([
                'over_name' => $request->over_name,
                'under_name' => $request->under_name,
                'over_name_kana' => $request->over_name_kana,
                'under_name_kana' => $request->under_name_kana,
                'mail_address' => $request->mail_address,
                'sex' => $request->sex,
                'birth_day' => $birth_day,
                'role' => $request->role,
                'password' => bcrypt($request->password)
            ]);
            if($request->role == 4){
                $user = User::findOrFail($user_get->id);
                $user->subjects()->attach($subjects);
            }
            DB::commit();
            return view('auth.login.login');
        }catch(\Exception $e){
            DB::rollback();
            return redirect()->route('loginView');
        }
    }
}
