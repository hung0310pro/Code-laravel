<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Jobs\RedirectPage;
use Illuminate\Http\Request;

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

	/**
	 * Where to redirect users after registration.
	 *
	 * @var string
	 */
	protected $redirectTo = RouteServiceProvider::HOME;

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make($data, [
			'name' => ['required', 'string', 'max:255'],
			'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
			'password' => ['required', 'string', 'min:8', 'confirmed'],
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param array $data
	 * @return \App\User
	 */
	protected function create(array $data)
	{
		return User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
		]);
	}

	public function register(Request $request)
	{
		$this->validator($request->all())->validate();

		event(new Registered($user = $this->create($request->all())));

		$this->guard()->login($user);

		// tối ưu ảnh vs Tinify
        $source = \Tinify\fromUrl("https://img.thuthuatphanmem.vn/uploads/2018/10/24/beautiful-city-wallpaper_102641859.jpg");
        $zoomouted = $source->resize(array(
            "method" => "scale",
            "width" => 250
        ));
        $zoomouted ->toFile("zoomoutedImage.jpg");

		if ($response = $this->registered($request, $user)) {
			return $response;
		}
        // Để thêm job vào một queue, sử dụng phương thức dispatch():
		dispatch(new RedirectPage($user));

		return $request->wantsJson()
			? new Response('', 201)
			: redirect($this->redirectPath());
	}
}
