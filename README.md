<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template</h1>
    <br>
</p>

![Debian](https://img.shields.io/badge/Debian-12-A81D33?logo=debian&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-28.1-2496ED?logo=docker&logoColor=white)
![Yii2](https://img.shields.io/badge/Yii2-2.0-83B81A?logo=yii&logoColor=white)
![Nginx](https://img.shields.io/badge/Nginx-1.29-009639?logo=nginx&logoColor=white)
![PHP-FPM](https://img.shields.io/badge/PHP_FPM-8.4-777BB4?logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-11.8-003545)

## Установить проект
```bash
git clone git@github.com:ivanitch/localinternet.git myproject
```

## Docker
```bash
# Собрать и запустить контейнеры 
make build && make up

# Войти в контейнер
make app
```

## Среда для разработки
```bash
# Выбрать среду для разработки
php init 
# Далее: [0], Development
```

## Composer
```bash
composer install
```

## Дополнительно
- Выполнить миграции: `php yii migrate`
- Зайти на сайт по `url`: http://localhost
- Зарегистрироваться по `url`: http://localhost/site/signup
- `Url` для подтверждения регистрации можно найти в директории `frontend/runtime/mail` или в БД изменить поле `status` на `10`
- Войти на сайт по `url`: http://localhost/site/login
- Админка: http://admin.localhost (в файл `/etc/hosts` добавить: `127.0.0.1 admin.localhost`)

## Генерация данных
```bash
php yii faker/generate
```

## API

### Список Банков
```bash
GET http://api.localhost
```

### Полная информация по банку
```bash
http://api.localhost/v1/banks/7
```
ANSWER:
```json
{
    "id": 7,
    "name": "ПАО РемМеталТрансНаладка",
    "description": "Ut dolorem asperiores ea praesentium. Corrupti at ea odit. At mollitia magnam tempora nam.",
    "cities": [
        {
            "id": 106,
            "name": "Burg",
            "country": "Фарерские острова"
        }
    ],
    "services": [
        {
            "id": 3,
            "name": "sit"
        },
        {
            "id": 25,
            "name": "harum"
        },
        {
            "id": 37,
            "name": "ex"
        },
        {
            "id": 40,
            "name": "quo"
        }
    ]
}
```

### Обновление данных банка
```
PUT http://api.localhost/v1/banks/31
```
BODY:
```json
{
    "name": "Новое название банка",
    "description": "Обновленное описание.",
    "status": 1,
    "city_ids": [1, 5, 8],
    "service_ids": [10, 25]
}
```
ANSWER:
```json
{
    "id": 31,
    "name": "Новое название банка",
    "description": "Обновленное описание.",
    "cities": [
        {
            "id": 1,
            "name": "Павловский Посад",
            "country": "Антарктида"
        },
        {
            "id": 5,
            "name": "DuhamelBourg",
            "country": "Новая Каледония"
        },
        {
            "id": 7,
            "name": "Cottbus",
            "country": "Панама"
        }
    ],
    "services": [
        {
            "id": 10,
            "name": "odio"
        },
        {
            "id": 25,
            "name": "harum"
        }
    ]
}
```

### "Мягкое" удаление банка
```bash
DELETE http://localhost/api/banks/34
```



