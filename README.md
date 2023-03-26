**laravel **<br>

环境<br>
php8.0+<br>

安装<br>
`composer require aphly/laravel` <br>
`php artisan vendor:publish --provider="Aphly\Laravel\BaseServiceProvider"` <br>
`php artisan migrate` <br>

1、响应json格式 (安装laravel-admin后不需要修改)<br>
修改文件 app/Exceptions/Handle.php <br>
`use Aphly\Laravel\Exceptions\ApiException;`<br>
`protected $dontReport = [ApiException::class];`

继承FormRequest类<br>
11000 表单验证错误


小技巧<br>
1、 数据库迁移报错 app/Providers/AppServiceProvider.php boot()中 添加 `Schema::defaultStringLength(191);`<br>
2、 `composer dump-autoload`<br>
3、 `php artisan storage:link`<br>
4、 后台不显示清下缓存<br>
5、`php artisan config:cache`<br>
6、`php artisan route:cache`<br>
7、`php artisan view:cache`<br>