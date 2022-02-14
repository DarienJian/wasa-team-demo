# 開發 system design

### How to start
```
git clone git@github.com:DarienJian/wasa-team-demo.git

composer install

php artisan migrate 初始化tables

php artisan db:seed --class=InitProject 填充一些預設情境資料
```

### 設計情境

一個簡單的文章功能的api

提供一個接口供使用者註冊、登入、登出

admin帳號可以管理所有帳號權限 <-admin帳號將會在db:seed填入
```
預設admin帳號 admin@wasateam.com 密碼 12345678
```
權限預設為
admin最高管理者     => 可以使用全部功能api,
manager文章管理者   =>  可對文章做CRUD,
normal一般使用者


### APIs
Header
```
Content-Type: application/json
Authorization: Bearer <token>
```

### 各種status意思
|statusCode|description|
|:--:|:-----:|
|200|call api 成功|
|401|登入token驗證失敗|
|403|非允許權限操作|

### 註冊
:::
POST
:::

```
/api/register
```

#### parameters
|Key|Type|Description|
|:---:|:----:|:------:|
|name|required/string|最大255字|
|email|required/string|email|
|password|required/string|密碼|
|password_confirmation|required/string|二次驗證密碼|


### 登入
:::
POST
:::

```
/api/login
```

#### parameters
|Key|Type|Description|
|:---:|:----:|:------:|
|email|required/string|email|
|password|required/string|password|

#### return.parameters
|Key|Type|Description|
|:---:|:----:|:------:|
|token|string|token|

* 登入成功將會得到token, 作為平台上識別用戶的token
* 需要登入狀態才能使用的api 需在Header將此token 以Bearer Token 的方式傳入

```json
回傳範例
{
    "user": {
        "id": 1,
        "name": "admin",
        "email": "admin@wasateam.com",
        "email_verified_at": null,
        "created_at": "2022-02-13T14:18:08.000000Z",
        "updated_at": "2022-02-13T14:18:08.000000Z",
        "groups": [
            {
                "id": 1,
                "group_name": "admin",
                "created_at": "2022-02-13T14:18:08.000000Z",
                "updated_at": "2022-02-13T14:18:08.000000Z",
                "pivot": {
                    "user_id": 1,
                    "group_id": 1
                }
            }
        ]
    },
    "token": "14|VNEXsXcXtHuBmjOqYX7HTQxvYtdSZXruTqDg4j74"
}
```

### 登出
:::success
POST
:::

```
/api/logout
```

### 建立文章
:::
POST
:::

```
/api/posts
```

#### parameters
|Key|Type|Description|
|:---:|:----:|:------:|
|title|required/string|標題|
|content|required/string|內文|

#### return.parameters
|Key|Type|Description|
|:---:|:----:|:------:|
|title|string|標題|
|content|string|內文|
|user_id|int|建立者編號|
|updated_at|date/Y-m-d H:i:s|更新時間|
|created_at|date/Y-m-d H:i:s|建立時間|
|id|int|文章編號|

``` json
回傳範例
{
    "title": "teitle",
    "content": "123123",
    "user_id": 3,
    "updated_at": "2022-02-14 13:08:28",
    "created_at": "2022-02-14 13:08:28",
    "id": 2
}
```

### 取得文章列表
:::
GET
:::

```
/api/posts
```

#### return.parameters
|Key|Type|Description|
|:---:|:----:|:------:|
|title|string|標題|
|content|string|內文|
|user_id|int|建立者編號|
|updated_at|date/Y-m-d H:i:s|更新時間|
|created_at|date/Y-m-d H:i:s|建立時間|
|id|int|文章編號|


``` json
回傳範例
[
    {
        "id": 2,
        "user_id": 3,
        "title": "teitle",
        "content": "123123",
        "created_at": "2022-02-14 13:08:28",
        "updated_at": "2022-02-14 13:08:28"
    }
]
```

### 取得文章
:::
GET
:::

```
/api/posts/{id}
```

#### return.parameters
|Key|Type|Description|
|:---:|:----:|:------:|
|title|string|標題|
|content|string|內文|
|user_id|int|建立者編號|
|updated_at|date/Y-m-d H:i:s|更新時間|
|created_at|date/Y-m-d H:i:s|建立時間|
|id|int|文章編號|


``` json
回傳範例
{
    "id": 2,
    "user_id": 3,
    "title": "teitle",
    "content": "123123",
    "created_at": "2022-02-14 13:08:28",
    "updated_at": "2022-02-14 13:08:28"
}
```

### 編輯文章
:::
PUT
:::

```
/api/posts/{id}   
```
#### parameters
|Key|Type|Description|
|:---:|:----:|:------:|
|title|required/string|標題|
|content|required/string|內文|


### 刪除文章
:::
DELETE
:::

```
/api/posts/{id}   
```

### 變更帳號權限
:::
POST
:::

```
/api/setGroup
```

#### parameters
|Key|Type|Description|
|:---:|:----:|:------:|
|user_id|required/integer|使用者編號|
|group_id|required/integer|權限編號|
```
------------------------
目前權限的做法還是第一次嘗試使用auth:sanctum這個套件來做,
他方便是可將權限設定直接寫在token內, 並在middleware直接判斷, 非常直觀且好維護

然後就是因架構不大的關係, 所以將商業邏輯的部份都擺在controller中
若架構逐漸變大會將商業邏輯的部份轉往Service, 資料運用轉往Repositories, 分層處理
