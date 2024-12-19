## 說明
> 實作之類別需符合物件導向設計 原則 SOLID 與設計模式。並於該此專案的 README.md 說明您所使用的 SOLID 與 設計模式分別為何。

### "S"OLID
我的理解是，每個東西都有它應該安放的地方。我們要有一套擺放邏輯的規則，就像圖書分類法一樣，把東西一個一個擺在合適的地方，和其他功能分離開來，以便日後我們在修改或新增功能時，可以推演出要從既有系統的什麼地方開始改起。

這會體現在程式碼的垂直（分層）和水平方向（模組化）上的劃分。

#### 水平方向上的劃分
因為 laravel 預設的 `app/` 已經有擺 `User.php` 和 `AppServiceProvider.php`，如果直接從這裡打掉怕會影響功能（我是想要把 eloquent model 都拔掉改用 `Repository`/`Dao` 的人）。這裡我選擇另外開一個資料夾叫 `lib`，按 psr-4 的規則將專案會用到的程式碼按模組擺放在這個資料夾下。

#### 垂直方向上的劃分
我的分層會有 `presentation layer` -> `application layer` -> `domain layer`（但大部分時候，貧血模型都可以簡單搞定）。

##### presentation layer 的職責
- 將資料從 http request/mq message/cli 拿出，組成 `mutation` / `query`（其實我更傾向於把它包裝成像 python 上的 dataclass 而不是使用 php 的 associative array）。
- 透過依賴注入產生 `application service` 並執行。或者直接從其他 adapters 取得資料，並從多個結果組合成我們想要的資料。 
- 產生對應的 html/json、console log。

##### application layer 的職責
- 我覺得最好是寫成 class：
    1. 在 constructor 一併儲存所依賴的物件，才可以將這些依賴和執行 service 的參數分離開來（有人主張他應該要是 function）。
    2. 如果流程會因為傳入參數改變而有所不同時，才可以用上多型（譬如通知可以是用 email 或 LINE，這時候傳入的依賴和流程可能會稍有不同）。
- 宣告依賴時可以抽象成 interface，這樣測試的時候可以改傳入 mock。
- 所以主要的業務流程會寫在這邊。
> ports 可以是 application services 或是其他 adapters 的抽象。

##### domain layer 的職責
這是我個人信仰，但暫時沒有想到一定要用它的情況。

### S"O"LID
我的理解是，功能儘量是可以透過加入新的類別檔案作擴充，而不是塞在原本的地方，對同一份類別或檔案一直複雜的條件判斷。

### SO"L"ID
在繼承又繼承的過程中（但其實希望不會真的遇到），傳入的東西最好可以越來越抽象，回傳的東西最好可以越來越明確。

### SOL"I"D
這是「最小充分原則」（或是 60 分法則）。
如果完成一件流程，強制自己只用必要且基本的物件，我們一方面可以減低認知負擔，另一方面也可以避免不小心依賴到不該依賴的東西（也不會因為這額外依賴的東西壞掉而導致我們的流程中斷）。

### SOLI"D"
想不到什麼說法。但我知道有人會把「使用依賴注入容器」當作實踐這個原則，這不太對⋯。

### 其他說明
我覺得如果能改用 `Validator`，資料驗證部分的測試，就可以改放到 `tests/Unit`。可是這樣一來，`tests/Feature` 內容就會變得滿空洞。

另一點是，如果 cli 或者 mq worker 也想重用這個驗證，寫成 FormRequest 感覺怪怪的（因為這些時候我們接收到的不真的是一個 http request）。

- 過程中額外思考到，資料驗證該不該進到 `application layer` 時才做？
- 驗證如果失敗，應該拋個別的 Exception 類別嗎？還是統一都拋 `ValidationException`？

如果你們已經有完好的定見，也歡迎屆時跟我分享。

----

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

