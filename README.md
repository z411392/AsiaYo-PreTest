## 紀錄
已經很久沒寫 PHP 了，而且後來換了 M2，這邊順便記錄一下安裝的過程。

### 安裝過程
參考：
1. [安裝 PHP](https://github.com/shivammathur/homebrew-php?tab=readme-ov-file#install-php)
2. [安裝 composer](https://getcomposer.org/download/)
3. 安裝 laravel installer
```bash
composer global require laravel/installer
```
> 記得把 ~/.composer/vendor/bin 露出在 PATH
4. 開 mysql 的 container（本機可以設定 `MYSQL_ALLOW_EMPTY_PASSWORD=1`）。
5. laravel new PROJECT_NAME
6. 安裝時，選：
    - starter kit: none
    - db: mysql
    - test tool: PHP Unit
並執行 migration
7. 執行
```bash
php artisan install:api
```
8. 將 routes/web.php 和 routes/api.php 預設的路由註解掉。

### 將功能作水平方向的切分
```json
        "psr-4": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/",
            "AsiaYo\\": "modules/" // 加上這行
        }
```

```bash
compose dump-autoload
```