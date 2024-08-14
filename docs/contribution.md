# Contribution

Фактично бібліотека складається з двох генераторів: один для клієнтської частини, один для серверної. Але деякі частини
коду, чи темплейти вони перевикористовують.

І залежно від того, яку частину ми генеруємо відрізняється вхідна точка програми:

- [openapi-client-generate.php](../bin/openapi-client-generate.php) - для клієнтської частини
- [openapi-server-generate.php](../bin/openapi-server-generate.php) - для серверної частини

Незалежно від того генеруємо ми серверну чи клієнтську частину бібліотека працює у два етапи:

### 1. Виклик [OpenAPITools/openapi-generator](https://github.com/OpenAPITools/openapi-generator)

Спочатку в папку tmp-openapi генерується код,
через [OpenAPITools/openapi-generator](https://github.com/OpenAPITools/openapi-generator).

Відбувається це викликом

```php
// Це спрощена версія, на справді опції більше
exec("openapi-generator generate -i $manifest -g $generator -o tmp-openapi -t $templatePath ...", $output)
```

Де,

- `$manifest` - шлях до openapi документу (.yaml файл з якого ми генеруємо).
- `$generator` - назва openapi [генератора](https://openapi-generator.tech/docs/generators):
    - `php-ze-ph` - для серверної частини (доступний до версії 5, потім перейменовано, здається
      в [php-mezzio-ph](https://openapi-generator.tech/docs/generators/php-mezzio-ph))
    - `php` - для клієнтської частини
- `-o tmp-openapi` - вказання директорії, де будуть розміщені згенеровані файли
- `$templatePath` - шлях, до директорії з темплейтами

Головний спосіб втрутитись в роботу [OpenAPITools/openapi-generator](https://github.com/OpenAPITools/openapi-generator)
це змінити темплейти, що вказані по шляху `$templatePath` (вони в директорії [template](../template)).

Працює це наступним чином: openapi-generator парсить маніфест в набір різних змінних, які потім підставляються в
темплейти, написані мовою mustache.

Наприклад на основі темплейту

`api.mustache`

```mustache
<?php
declare(strict_types=1);

{{#operations}}
```

Створиться файл, де замість `{{#operations}}` підставиться значення змінної `operations`. Щоб дізнатись, які зміні надає
openapi-generator можна запустити його з опціями `--global-property debugModels,debugOperations`, тоді в stdout
виведеться два json зі значеннями для моделей (DTO) та операцій (Api класи).

```bash
openapi-generator generate \
    -g php \
    -o tmp-openapi \
    -i openapi.yaml \
    --global-property debugModels,debugOperations
```

Детальніше можна почитати
в [debugging.md](https://github.com/OpenAPITools/openapi-generator/blob/master/docs/debugging.md)
та [customization.md](https://github.com/OpenAPITools/openapi-generator/blob/master/docs/customization.md)

### 2. Пост обробка файлів, згенерованих через [OpenAPITools/openapi-generator](https://github.com/OpenAPITools/openapi-generator)

Потім, файли з tmp-openapi php кодом переносяться в інші директорії (e.g. `src/{ManifestTitle}/Client/*`), та за
необхідності модифікуються (наприклад змінюється неймспейс), а також php код генерує файли конфігурації та REST класи.

Змінити, те що відбувається на цьому етапі можна в файлах:
- [openapi-client-generate.php](../bin/openapi-client-generate.php) - для клієнтської частини
- [openapi-server-generate.php](../bin/openapi-server-generate.php) - для серверної частини

------------

Для того, щоб додати фічу чи пофіксити щось в бібліотеці, треба для початку розібратись в тому, на якому етапі 
згенерувався файл (або конкретний рядок в файлі). Загальне правило таке, що REST класи та конфігурація генерується на
другому етапі і тому треба змінювати php код, а усе інше на першому і треба змінювати mustache темплейти.

Також, ця бібліотека підключається до проектів, як залежність в composer.json, та згенеровані файли, можуть 
використовувати класи з цієї бібліотеки. Тому не кожна зміна потребує втручання в процесс генерації, інколи треба просто
змінити код в якомусь класі бібліотеки (наприклад валідаторі) і оновити її в проекті.