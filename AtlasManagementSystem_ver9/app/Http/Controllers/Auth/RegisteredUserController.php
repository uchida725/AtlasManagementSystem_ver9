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
use Illuminate\Support\Facades\Validator;

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

        // 新規登録画面のバリデーション
    $message = [

    // 氏名（姓）
    'over_name.required' => '姓は必須項目です。',
    'over_name.string' => '姓を正しく入力してください。',
    'over_name.max' => '姓は10文字以内で入力してください。',

    // 氏名（名）
    'under_name.required' => '名は必須項目です。',
    'under_name.string' => '名を正しく入力してください。',
    'under_name.max' => '名は10文字以内で入力してください。',

    // 氏名カナ（姓）
    'over_name_kana.required' => 'フリガナ(セイ)は必須項目です。',
    'over_name_kana.regex' => 'フリガナ(セイ)は全角カタカナで入力してください。',
    'over_name_kana.max' => '30文字以内で入力してください。',

    // 氏名カナ（名）
    'under_name_kana.required' => 'フリガナ(メイ)は必須項目です。',
    // 'under_name_kana.string' => 'は文字列で入力してください。',
    'under_name_kana.regex' => 'フリガナ(メイ)は全角カタカナで入力してください。',
    'under_name_kana.max' => 'フリガナ(メイ)は30文字以内で入力してください。',

    // メールアドレス
    'mail_address.required' => 'メールアドレスは必須項目です。',
    'mail_address.string' => 'メールアドレスの入力が正しくありません',
    'mail_address.email' => '正しいメールアドレス形式で入力してください。',
    'mail_address.max' => 'メールアドレスは100文字以内で入力してください。',
    'mail_address.unique' => 'このメールアドレスはすでに登録されています。',

    // 性別
    'sex.required' => '性別は必須項目です。',

    // 生年月日
    'old_year.required' => '生年月日の入力は必須です',
    'old_month.required' => '生年月日の入力は必須です',
    'old_day.required' => '生年月日の入力は必須です',
    // 'old_year.date' => '生年月日の「年」は正しい日付を選択してください。',
    'old_month.date' => '生年月日の「月」は正しい日付を選択してください。',
    'old_day.date' => '生年月日の「日」は正しい日付を選択してください。',


    // 役職
    'role.required' => '役職は必須項目です。',

    // パスワード
    'password.required' => 'パスワードは必須項目です。',
    'password.min' => 'パスワードは8文字以上で入力してください。',
    'password.max' => 'パスワードは30文字以内で入力してください。',
    'password.confirmed' => 'パスワードが一致しません。',

    // パスワード（確認用）
    'password_confirmation.required' => 'パスワード確認は必須項目です。',
    'password_confirmation.min' => 'パスワードは8文字以上で入力してください。',
    'password_confirmation.max' => 'パスワードは30文字以内で入力してください。',



    ];

    $validatedData = $request->validate([
        'over_name' => 'string|max:10',
        'under_name' => 'required|string|max:10',
        'over_name_kana' => 'required|string|regex:/^[ァ-ヶー　]+$/u|max:30',
        'under_name_kana' => 'required|string|regex:/^[ァ-ヶー　]+$/u|max:30',
        'mail_address' => 'required|string|email:strict,dns|max:100|unique:users,email',
        'sex' => 'required|in:1,2,3',
        'old_year' => 'required|date|after_or_equal:2000-01-01|before_or_equal:today',
        'old_month' => 'required|date|after_or_equal:2000-01-01|before_or_equal:today',
        'old_day' => 'required|date|after_or_equal:2000-01-01|before_or_equal:today',
        'role' => 'required|in:1,2,3,4',
        'password' => 'required|min:8|max:30|confirmed',
        'password_confirmation' => 'required|min:8|max:30',
    ], $message);





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
