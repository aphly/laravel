**laravel Class**<br>

环境<br>
php7.0+<br>

安装<br>
`composer require aphly/laravel` <br>

返回json<br>
app/Exceptions/Handle.php<br>
`use Aphly\Laravel\Exceptions\ApiException;` <br>
`protected $dontReport = [ApiException::class];` <br>

