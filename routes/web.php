<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('test', function () {
    return 'hello';
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// đây là chỗ của vue api (listuserapi.blade.php,ListUserApiComponent.vue,ListUserApiController.php,app.js(vue))
Route::resource('listuserapi', 'ListUserApiController');
Route::get('/listuserapipage', function () {
    return view('vue/listuserapi');
});
// end vue

// Gate (authorizationController.php(index), Model\Comment.php, AuthServiceProvider(1))
Route::get('/testauthorizationgate', 'authorizationController@index');
// Policies ((authorizationController.php, Model\Comment.php, AuthServiceProvider(2), showauthorizationpolicies.blade.php, App\Policies\CommentPolicy.php (thực hiện phân quyền trong này))
Route::get('/testauthorizationpolicies/{id}', 'authorizationController@show');


//++++ test (table comment,users)
// subquery(1 user có nhiều cmt) get all user trong bảng users sao cho lấy ra được comment mới nhất
Route::get('getorm', function () {
    $lastComment = App\Comment::select('comment')
        ->whereColumn('id_users', '=', 'users.id')
        ->latest()
        ->limit(1)
        ->getQuery();

    $users = App\User::select('users.*')
        ->selectSub($lastComment, 'last_commet')
        ->get();


    echo "<pre>";
    print_r($users->toArray());
    echo "</pre>";
});


// queue trong laravel https://allaravel.com/blog/laravel-queue-xu-ly-cong-viec-kieu-hang-doi
// Laravel Queue xử lý công việc kiểu hàng đợi(thực thi các event theo thứ tự, ví dụ sau khi ng dùng đăng ký tài khoản thành công thì sẽ gửi email cho ng dùng) sau khi thực hiện xong thì sẽ gửi thông tin vào bảng job,

/*
- Tạo và thêm Job vào queue
php artisan make:job SendWelcomeEmail

- Để thêm job vào một queue, sử dụng phương thức dispatch():
ví dụ sau khi đăng ký thành công thì :

class RegisterController extends Controller
{
    ...
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
       // chỗ thêm job vào queue
        $job = (new SendWelcomeEmail($user))->delay(Carbon::now()->addMinutes(10));
        dispatch($job);
        return $user;
    }
}

- Số lần thử thực hiện job
  php artisan queue:work --tries=3 (thực thi 1 lần cho tất cả các job, cái nào lỗi bỏ qua)
  public $tries = 3 (hoặc dùng cái này cho từng job, số lần thực hiện lại là 3)

- Thời gian thực hiện job cx tương tự : php artisan queue:work --timeout=60 || public $timeout = 60;
- Thiết lập thời gian nghỉ giữa các lần xử lý job : php artisan queue:work --sleep=3, or sleep(10) trong file
- Queue worker, thực thi các job trong queue : php artisan queue:work
Chú ý, câu lệnh này khi đã thực hiện sẽ chạy cho đến khi đóng cửa sổ dòng lệnh hoặc dừng nó bằng một câu lệnh. Queue worker là các tiến trình có thời gian sống dài do đó nó sẽ không cập nhật code khi có thay đổi, khi bạn thay đổi code chương trình, bạn cần khởi động lại queue worker bằng câu lệnh

php artisan queue:restart
Ngoài ra còn 1 vài lưu ý thì đọc trong bài trên


_+ ví dụ của mình khi đăng kí tài khoản thì insert vào bảng comment (RegisterController.php hàm register, App\Jobs\RedirectPage)

*/

// cron job (App\Console\Commands\HappyBirthday.php, App\Console\Kernel.php)
/*
- đầu tiên chạy lệnh : php artisan make:command HappyBirthday --command=sms:birthday (để tạo cron job)
- sau đó xử lí code trong function handle() ở trong tk HappyBirthday
- sau đó khai báo ở trong App\Console\Kernel.php, và thực hiện chạy cron trong hàm schedule()
- chạy câu sau lệnh sau ở command(dưới local)php artisan sms:birthday (sms:birthday bên HappyBirthday.php)
https://allaravel.com/blog/laravel-task-scheduling-tu-dong-hoa-cong-viec-trong-he-thong-website
https://viblo.asia/p/quan-ly-cronjobs-voi-laravel-bJzKmkXEl9N
 * */

// +++ oauth 2
// muốn chạy cái này thì phải(AuthController.php, config\app.php,App\User.php, AuthServiceProvider.php,App\Http\Middleware\VerifyCsrfToken.php)
// oauth2, cái này hiểu đơn giản là ví dụ khi client đăng nhập web bằng face, thì sẽ có 1 nơi đó là authorization server sẽ cung cấp "access token", khi ấy resource owner sẽ check xem cái token đó có ok k? nếu có sẽ cho quyền truy cập và từ đó ta có thể xem đc tài nguyên trong resource server (https://viblo.asia/p/laravel-passport-JQVGVZKyGyd tham khảo thêm ở đây)
// Laravel Passport hỗ trợ bốn loại grant: Authorization Code Grant, Refresh Token Grant, Password Grant và Personal Access Grant


// Password Grant http://prntscr.com/s6i50d,http://prntscr.com/s6i54u (lưu vào oauth_access_tokens, oauth_refresh_tokens)

// DƯới đây là ví dụ về oauth2(Personal Access Token), tạo tài khoản, sau đó đăng nhập từ đó lấy "đc token của tài khoản" và sau đó có thể "Lấy info của tài khoản(https://viblo.asia/p/laravel-xac-thuc-api-voi-laravel-56-passport-authentication-part1-Do754p8V5M6)"
// sau khi có token thì sẽ lưu vào bảng oauth_access_tokens, nếu revoked = 0 thì sẽ lấy đc info của user, còn nếu bằng 1 thì sẽ k lấy đc(coi như logout)
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });
});

// send email
/*Route::get('/mail', function () {
    return view('formemail');
});
Route::post('/message/send', ['uses' => 'EmailController@sendEMail', 'as' => 'front.fb']);*/
// (.evn, config\mail.php, EmailController.php,App\Mail\SendMailRegisteredUser)
// https://allaravel.com/blog/xu-ly-thu-dien-tu-voi-laravel-mail/
Route::get('/mail', 'EmailController@sendEMail');
