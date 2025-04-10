<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'over_name' => 'string|max:10',
        'under_name' => 'required|string|max:10',
        'over_name_kana' => 'required|string|regex:/^[ァ-ヶー　]+$/u|max:30',
        'under_name_kana' => 'required|string|regex:/^[ァ-ヶー　]+$/u|max:30',
        'mail_address' => 'required|string|email:strict,dns|max:100|unique:users,mail_address',
        'sex' => 'required|in:1,2,3',
        // 'old_year' => 'required|before:tomorrow',
        // 'old_month' => 'required',
        // 'old_day' => 'required',
        // 'birth_day' => 'required|date|after_or_equal:2000-01-01|before_or_equal:today',
        'role' => 'required|in:1,2,3,4',
        'password' => 'required|min:8|max:30|confirmed',
        'password_confirmation' => 'required|min:8|max:30',
    ];

    }

    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $year = $this->input('old_year');
        $month = $this->input('old_month');
        $day = $this->input('old_day');

        // 存在する日付かどうかチェック（2/31などNG）
        if (!checkdate((int)$month, (int)$day, (int)$year)) {
            $validator->errors()->add('birth_day', '生年月日が正しくありません。');
            return;
        }

        // 正常な日付なら、範囲チェック！
        $birthDate = strtotime("$year-$month-$day");
        $minDate = strtotime('2000-01-01');
        $maxDate = strtotime(date('Y-m-d')); // 今日

        if ($birthDate < $minDate || $birthDate > $maxDate) {
            $validator->errors()->add('birth_day', '生年月日は2000年1月1日〜今日までの間で入力してください。');
        }
    });
}


    public function messages(){
  return [
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
    // 'birth_day.required' => '生年月日の入力は必須です',


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

    }





    /**
     * Attempt to authenticate the request's credentials.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('mail_address', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'mail_address' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'mail_address' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('mail_address')).'|'.$this->ip();
    }
}
