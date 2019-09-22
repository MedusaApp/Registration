<?php
namespace Personality\Registration;

use \App\User;
use \Illuminate\Auth\Events\Registered;
use \Illuminate\Http\Request;

trait RegistersUsers
{
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if (config('medusa.membership_approve') === false) {
            $this->guard()->login($user);
        }

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'address_1' => $data['address_1'],
            'address_2' => empty($data['address_2']) ? null : $data['address_2'],
            'city' => $data['city'],
            'state_province' => $data['state_province'],
            'postal_code' => $data['postal_code'],
            'country' => $data['country'],
            'dob' => date('Y-m-d', strtotime($data['dob'])),
            'email' => $data['email'],
            'telephone' => empty($data['telephone']) ? null : $data['telephone'],
            'password' => Hash::make($data['password']),
            'application_date' => date('Y-m-d H:i:s'),
            'registration_date' => null,
            'membership_status' => 'pending',
        ]);
    }
}