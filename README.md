**laravel **<br>

环境<br>
php8.0+<br>

安装<br>
`composer require aphly/laravel` <br>

1、响应json格式 (安装laravel-admin后不需要修改)<br>
修改文件 app/Exceptions/Handle.php <br>
`use Aphly\Laravel\Exceptions\ApiException;`<br>
`protected $dontReport = [ApiException::class];`

继承FormRequest类<br>
11000 表单验证错误
