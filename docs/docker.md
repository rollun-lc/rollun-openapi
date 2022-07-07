## Разработка через docker
Разработка через докер осуществляется с помощью утилит docker-compose и make.
1. Запустите инициализацию проекта (пересобирает/собирает все контейнеры, запускает миграции (при наличии), устанавливает
зависимости из composer.json и т.п).
Достаточно лишь изредка запускать, если были изменения в контейнерах или скриптах которые запускаются при инициализации.
```bash
make init
```

2. Сервис будет доступен по адресу localhost:8080

Чтобы закончить работу с сервисом запустите
```bash
make down
```

Чтобы запустить контейнеры обратно (без их пересборки) запустите
```bash
make up
```

Остальные полезные команды можете найти в файле [Makefile](/Makefile)

### Настройка PhpStorm
1. Добавьте интерпретатор
   ![Cli-interpreter settings](/docs/img/cli-interpreter.png?raw=true)
   ![Php settings](/docs/img/php-settings.png?raw=true)

2. Настройка Xdebug
   ![Debug settings](/docs/img/debug-settings.png?raw=true)

   ![Xdebug server settings](/docs/img/servers-settings.png?raw=true)

> Server name берется из переменной окружения PHP_IDE_CONFIG, значение которой обычно описывается в docker-compose