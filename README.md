# Mobillium Back-End Challange
Mobillium şirketinin back-end developer'lar için istediği temel bir blog sistemi.

Özellikler:
* 📝 Makaleler için basit CRUD (create, read, update, delete) işlemler,
* 👥 Rol bazlı kullanıcı sistemi (admin, moderator, yazar, okuyucu),
* ⭐ Makale oylama sistemi,
* ⏲️ Makale paylaşımını ileri bir tarih için planlama,
* ↔️ Yazılar arasında kolay geçiş,
* 🌐 Cache sistemi,
* 🔑 OAUTH 2.0 destekli RESTfull API,

Kullanılan teknolojiler/araçlar:
* [Laravel 8](https://laravel.com/)
  * [Laravel/Passport](https://laravel.com/docs/8.x/passport): OAUTH 2.0 için,
  * [Laravel/Dusk](https://laravel.com/docs/8.x/dusk): Browser tabanlı testler için,
* [whtht/perfectly-cache](https://github.com/whthT/perfectly-cache): Sorgu sonuçlarını cache'lemek için,
* [phpunit/phpunit](https://phpunit.de/): Birim testler için,

## Kullanımı
### 0. Gereksinimler
* PHP 7.3 veya üzeri
* MySQL
* Composer
* NodeJS / npm

#### 1 Gerekli kütüphanelerin kurulumu
PHP ve JavaScript kütüphanelerinin kurulması için:
```console
$ composer install
$ npm install
```

### 2. Konfigürasyonlar
Ortam değişkenlerini ayarlamak için `.env.example` dosyasını `.env` olarak yeniden
isimlendirin ve `DB_` ile başlayan değişkenleri düzenleyin.

Aşağıdaki komutla uygulama anahtarını yeniden ürettirin.
```console
$ php artisan key:generate
```

Gerekli veritabanı tablolarının oluşturulması için:
```console
$ php artisan migrate
```

OAUTH 2.0 destekli API'nin çalışabilmesi için:
```console
$ php artisan passport:install
```

### 3. Uygulamayı çalıştırmak
Uygulamayı çalıştırmak için aşağıdaki komutla basit bir web sunucusu oluşturabilirsiniz:
```console
$ php artisan serve
```
uygulama şu an **localhost:8000** adresinde çalışıyor.

Varsayılan kullanıcıları veritabanına eklemek ve rastgele makaleler oluşturmak için:
```console
$ php artisan db:seed
```

İleri tarih için zamanlanmış makalelerin yayınlanabilmesi için arkada sürekli açık olması gereken komut:
```console
$ php artisan schedule:work
```

## Test
Birim testleri çalıştırmak için:
```console
$ ./vendor/bin/phpunit
```

Tarayıcı üzerinde çalışan testleri çalıştırmak için öncelikle bir terminal'de uygulamayı ayağa kaldırın:
```console
$ php artisan serve
```

daha sonra gerekli driver'ları kurmak ve testleri çalıştırmak için (bu testler `.env` dosyasında belirttiğiniz veritabanını
kullanır ve bu testlerin çalışması uzun sürebilir):
```console
$ php artisan dusk:chrome-driver
$ php artisan dusk
```
**NOT:** Bu komutun çalışabilmesi için bilgisayarınızda Google Chrome tarayıcısının yüklü olması gerekir. Olası bir sorunda
`vendor/laravel/dusk/bin/` dizini altındaki dosyaların 755 izinlerinin olduğundan emin olun:
```console
$ chmod 755 -R ./vendor/laravel/dusk/bin/
```

## RESTfull API Kaynakları
### [POST] `/api/login/`
`email` ve `password` kabul eder. Bilgiler doğrusa, kullanıcya özel OAUTH 2.0 anahtlarını JSON formatında geri döner.

**Örnek kullanım (varsayılan kullanıcıların `seed` edilmiş olması gerekir):**
```console
$ curl --request POST \
  --url http://localhost:8000/api/login \
  --header 'Content-Type: application/json' \
  --data '{"email": "admin@mobillium.com", "password": "mobillium"}'
```
Cevap:
```json
{
  "token_type": "Bearer",
  "expires_in": 31536000,
  "access_token": "(...)",
  "refresh_token": "(...)"
}
```
`access_token` kısmı sonraki istekler için gerekli olacak.

### [GET] `/api/aricle`
Tüm kullanıcılara ait tüm makeleleri JSON formatında geri döner. Bu API kaynağını kullanabilmek için "admin" rolüne sahip
olmak gerekir.

**Örnek kullanım:**
```console
$ curl --request GET \
  --url http://localhost:8000/api/article/ \
  --header 'Authorization: Bearer [BURAYA ACCESS_TOKEN GELECEK]'
```
Cevap
```json
[
  {
    "id": 1,
    "user_id": 3,
    "slug": "vero-vel-saepe-repudiandae-aliquid-in-consequatur-312597",
    "title": "Vero vel saepe repudiandae aliquid in consequatur.",
    "content": "Similique facilis id doloribus pariatur provident odit beatae. ",
    "published_at": "2021-03-08T18:59:02.000000Z",
    "created_at": "2021-03-08T18:59:04.000000Z",
    "updated_at": "2021-03-08T18:59:04.000000Z",
    "status": "PUBLISHED",
    "votes": []
  },
  ...
]
```

### [POST] `/api/article/create`
Yeni makale oluşturur. Oluşturulan makaleyi JSON formatında geri döner. Bu API
kaynağını rolü "reader" olanlar dışında herkes kullanabilir.

* Gerekli alanlar: `title`, `content`
* Opsiyonel alanlar: `published_at`: Tarih saat formatı: `Y-m-d H:i`Sadece ileri
  zamanlı makale planlamak için gerekli, varsayılan olarak o anki tarih-saati
  kullanır.

**Örnek kullanım:**
```console
$ curl --request POST \
  --url http://localhost:8000/api/article/create \
  --header 'Authorization: Bearer [BURAYA ACCESS_TOKEN GELECEK]' \
  --header 'Content-Type: application/json' \
  --data '{"title": "Yeni yazı", "content": "Bu yeni bir yazıdır"}'
```
Cevap:
```json
{
  "user_id": 1,
  "slug": "yeni-yazi-65165",
  "title": "Yeni yazı",
  "content": "Bu yeni bir yazıdır",
  "published_at": "2021-03-08T19:15:58.000000Z",
  "status": "PUBLISHED",
  "updated_at": "2021-03-08T19:15:58.000000Z",
  "created_at": "2021-03-08T19:15:58.000000Z",
  "id": 21,
  "user": (...)
}
```

### [POST] `/api/article/update/{id}`
`id`si verilen makalenin içeriğini günceller. Güncellenen makale içeriği geriye JSON olarak gönderilir.

* Gerekli alanlar: `title`, `content`

**Örnek Kullanım:**
```console
$ curl --request POST \
  --url http://localhost:8000/api/article/update/21 \
  --header 'Authorization: Bearer [BURAYA ACCESS_TOKEN GELECEK]' \
  --data '{"title": "Başlık düzenlendi", "content": "İçerik düzenlendi"}'
```
Cevap:
```json
{
  "id": 21,
  ...
  "title": "Başlık düzenlendi",
  "content": "İçerik düzenlendi",
  "updated_at": "2021-03-08T19:25:33.000000Z",
  ...
}
```

### [PUT] `/api/article/publish/{id}`
`id`si verilen makaleyi yayına alır. Yayına alınan makalenin içeriği geriye JSON olarak gönderilir. `id`si verilen makalenin
yayınlanmamış olması gerekir.

**Örnek Kullanım:**
```console
$ curl --request PUT \
  --url http://localhost:8000/api/article/publish/21 \
  --header 'Authorization: Bearer [BURAYA ACCESS_TOKEN GELECEK]'
```
Cevap:
```json
{
  "id": 21,
  ...
  "published_at": "2021-03-08T19:32:08.000000Z",
  "status": "PUBLISHED",
  ...
}
```

### [PUT] `/api/article/unpublish/{id}`
`id`si verilen makaleyi yayından kaldırır. Yayından kaldırılan makalenin içeriği geriye JSON olarak gönderilir. `id`si verilen makalenin
yayınlanmış olması gerekir. Makaleyi yayından kaldırıp, ileri bir tarihe planlamak henüz desteklenmiyor.

**Örnek Kullanım:**
```console
$ curl --request PUT \
  --url http://localhost:8000/api/article/unpublish/21 \
  --header 'Authorization: Bearer [BURAYA ACCESS_TOKEN GELECEK]'
```
Cevap:
```json
{
  "id": 21,
  ...
  "published_at": null,
  "status": "DRAFT",
  ...
}
```

### [DELETE] `/api/article/delete/{id}`
`id`si verilen makaleyi tamamen siler. Geriye sadece 204 HTTP kodu gönderir. Bu işlem geri alınamaz.

**Örnek Kullanım:**
```console
$ curl --request DELETE \
  --url http://localhost:8000/api/article/delete/21 \
  --header 'Authorization: Bearer [BURAYA ACCESS_TOKEN GELECEK]'
```
## Karşılaştırma
Gönderilen challange dökümanındaki görevleri yaptığım yerler:

* "*It should have roles of admin, moderator, writer and reader.*" \
  "*Every user who is a member of the system should have a basic reader role.*" \
  "*All roles must be checked with a single table, that is, users with all roles
    must be in the same table.*"
  - [2021_03_05_202317_insert_role_column_to_users_table.php](./database/migrations/2021_03_05_202317_insert_role_column_to_users_table.php)
* "*There must be pages where users can login and register. All roles should be
   able to login with the same login section and then be directed to their
   respective panels.*" \
   "*The authors should have a panel of their own or they should only see a more
    restricted section of the Management Panel within their authority.*"
  - [routes/web.php](./routes/web.php#L43-L49)
  - [resources/views/article/list.blade.php](./resources/views/article/list.blade.php)
  - [app/Http/Controllers/ArticleController.php](./app/Http/Controllers/ArticleController.php#L21-L29)
  - [app/Http/Controllers/Auth/LoginController.php](./app/Http/Controllers/Auth/LoginController.php)
  - [app/Http/Controllers/Auth/RegisterController.php](./app/Http/Controllers/Auth/RegisterController.php)
  - [resources/views/auth/login.blade.php](./resources/views/auth/login.blade.php)
  - [resources/views/auth/register.blade.php](./resources/views/auth/register.blade.php)
* "*Articles can only be deleted based on administrators. Apart from this, admins,
   moderators or the author who wrote the article will be allowed to delete the
   article.*"
  - [app/Policies/ArticlePolicy.php](./app/Policies/ArticlePolicy.php)
  - [resources/views/article/list.blade.php](./resources/views/article/list.blade.php#L67-L71)
  - [app/Http/Controllers/ArticleController.php](./app/Http/Controllers/ArticleController.php#L133-L141)
* "*Articles should have a publishing feature in the future.*"
  - [app/Console/Commands/PublishScheduledArticles.php](./app/Console/Commands/PublishScheduledArticles.php)
  - [app/Console/Kernel.php](./app/Console/Kernel.php#L28)
  - [resources/views/article/create.blade.php](./resources/views/article/create.blade.php#L33-L43)
  - [app/Http/Controllers/ArticleController.php](./app/Http/Controllers/ArticleController.php#L56-L59)
* "*Articles should have a voting feature between 1 ~ 5.*" \
  "*All users on the system should be able to rate the articles.*" \
  "*Users who are not logged in should be able to see the vote but can not vote.*" \
  "*In the voting, according to the result of the voting, the last 30% of the
  votes should be 2 times more effective than the remaining 70%.*"
  - [app/Http/Controllers/VoteController.php](./app/Http/Controllers/VoteController.php)
  - [routes/web.php](routes/web.php#38-41)
  - [resources/views/article/view.blade.php](./resources/views/article/view.blade.php#L24-L44)
* "*Posts should have views.*" \
  "*Each post has its own page.*" \
  "*The page of each article should include the previous article and next
  article links.*" \
  "*Articles on the main page and their own pages should be cached with the
  whtht/perfectly-cache package.*"
  - [resources/views/article/view.blade.php](./resources/views/article/view.blade.php)
  - [app/Models/Article.php](./app/Models/Article.php)
  - [routes/web.php](./routes/web.php#L23)
* "*Articles should be listed on the main page.*"
  - [resources/views/home.blade.php](./resources/views/home.blade.php)
* "*Models should be linked with Eloquent Relationship.*" \
  "*Related data to be obtained from database queries must be provided with eager
   load.*"
  - [app/Models/Vote.php](./app/Models/Vote.php)
  - [app/Models/User.php](./app/Models/User.php)
  - [app/Models/Article.php](./app/Models/Article.php)
* "*Seed must be used to seed the database.*" \
  "*Data used for testing must be provided with Factory.*"
  - [database/factories/ArticleFactory.php](./database/factories/ArticleFactory.php)
  - [database/factories/UserFactory.php](./database/factories/UserFactory.php)
  - [database/factories/VoteFactory.php](./database/factories/VoteFactory.php)
  - [database/seeders/ArticleSeeder.php](./database/seeders/ArticleSeeder.php)
  - [database/seeders/UserSeeder.php](./database/seeders/UserSeeder.php)
* "*API should work with OAUTH2.0 logic.*" \
  "*API addresses must be available from which OAUTH2.0 tokens can be obtained.
  These addresses must be entered with e-mail address and password.*" \
  "*There should be a restful API where the articles can be exported.*" \
  "*This restful API needs to support adding, editing, unpublishing and deleting
  articles.*"
  - [routes/api.php](./routes/api.php)
  - [app/Http/Controllers/Api/ArticleController.php](./app/Http/Controllers/Api/ArticleController.php)
  - [app/Http/Controllers/Api/Auth/LoginController.php](./app/Http/Controllers/Api/Auth/LoginController.php)
* "*There should be a table listing the articles that are written, and in this
  table, the admin should be able to remove the article from the publication
  or delete it completely.*" \
  "*In order to edit the articles, it should be directed to the author panel and
  should be able to edit it.*" \
  "*The author should have a table where he can see the articles he wrote.*" \
  "*The author should be able to add new articles.*" \
  "*The writer should be able to edit the articles that he wrote and able to delete it.*"
  - [resources/views/article/list.blade.php](./resources/views/article/list.blade.php)
  - [app/Http/Controllers/ArticleController.php](./app/Http/Controllers/ArticleController.php)
  - [resources/views/article/create.blade.php](./resources/views/article/create.blade.php)
* "*The main page and random ten articles should be tested in the application
  with laravel/dusk.*"
  - [tests/Browser/HomepageTest.php](./tests/Browser/HomepageTest.php)
  - [tests/Browser/ArticlePageTest.php](./tests/Browser/ArticlePageTest.php)
* "*The helper function where the voting average will be calculated should be
  tested with phpunit.*"
  - [tests/Unit/Models/ArticleTest.php](./tests/Unit/Models/ArticleTest.php)
* "*In order to test the admin panel and author panel, the users listed below
  must be provided in the database.*"
  - [database/seeders/UserSeeder.php](./database/seeders/UserSeeder.phpS)

## Ekran Görüntüleri
![admin-panel](/screenshots/admin-panel.png)
![article-page](/screenshots/article-page.png)
![create-article-page](/screenshots/create-article-page.png)
![login-page](/screenshots/login-page.png)
![mainpage](/screenshots/mainpage.png)
![register-page](/screenshots/register-page.png)

# Bilinen Sorunlar
* Blade dosyaları çok fazla logic kod içeriyor. Kod tekrarını önlemek için tek
  bir panel sayfası yapıp, kullanıcının yetkilerine göre sayfayı oluşturdum fakat
  bu sefer de HTML dökümanıda çok fazla logic kodu yazmış oldum. Front-end tarafım
  olmadığı için bu tercihi yapmak zorunda kaldım.
* Yine kod tekrarını önlemek için panellerin arkasındaki controller yapısını da
  tek controller şeklinde kullandım. Bu da kullanıcıyı kendi rolüne göre olan
  panele yönlendirme vb. gibi konularda çeşitli numaralar yapmama neden oldu.
  ArticleController içerisinde Laravel'in tüm faydalarını kullanamadığımı
  düşünüyorum. Aynı şekilde API tarafındaki ArticleController için de geçerli.
  Daha iyi yazabilirdim.
* Cache mekanizmasını da çok verimli kullanamadığımı düşünüyorum. Üzerinde daha
  çok çalışarak, daha verimli sorgular oluşturarak bunu iyileştirebilirdim.
  Makalelerin oy sayıları ve yazarlarına cache özelliği eklemedim. Aslında Vote
  modelini de Cachable yapmaya çalıştım fakat bu sefer de Unit test tarafında
  caching disable olmadığı için bug oluştu. Vaktim olursa ilgili caching
  kütüphanesine bunu bildirip, yapabilirsem sorunu çözmeye çalışacağım.

# Lisans
MIT License