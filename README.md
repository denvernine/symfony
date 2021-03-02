# DDD + Symfony

```fundamental
project_root/
|-- .env
|-- .gitignore
|-- README.md
|-- bin/
|   `-- console
|-- public/
|   `-- index.php
`-- src/
    |-- composer.json
    |-- composer.lock
    |-- composer.phar
    |-- symfony.lock
    |-- app/
    |   |-- Kernel.php
    |   |-- Applications/     // アプリケーション層（ユースケース層）
    |   |-- Domains/          // ドメイン層
    |   |-- Infrastructures/  // インフラストラクチャー層
    |   `-- Presentations/    // プレゼンテーション層（ユーザーインターフェース層）
    |       `-- Http/
    |           |-- API/
    |           |   `-- Controller/
    |           `-- Web/
    |               `-- Controller/
    |                   `-- .gitignore
    |-- config/
    |   |-- bundles.php
    |   |-- preload.php
    |   |-- routes.yaml
    |   |-- services.yaml
    |   |-- packages/
    |   |   |-- cache.yaml
    |   |   |-- framework.yaml
    |   |   |-- routing.yaml
    |   |   |-- prod/
    |   |   |   `-- routing.yaml
    |   |   `-- test/
    |   |       `-- framework.yaml
    |   `-- routes/
    |       `-- dev/
    |           `-- framework.yaml
    |-- var/
    |   |-- cache/
    |   `-- log/
    `-- vendor/
        |-- autoload.php
        `-- [...]
```
