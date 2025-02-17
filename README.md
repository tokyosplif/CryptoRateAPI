Проект реализован не полностью. Создана команда для обновления курсов валют, но её выполнение через Symfony Scheduler или Cron не настроено. 
(Детали реализации можно найти в файлах `ExchangeRateService` и `UpdateExchangeRatesCommand`).
Для тестирования функционала используются фикстуры, которые имитируют реальные данные в базе данных.

Инструкция по сборке и работе:

Сборка:
Установить зависимости: composer install
Настройка окружения: .env DATABASE_URL="mysql://user:password@127.0.0.1:3306/your_database_name?
Применение миграций: php bin/console doctrine:migrations:migrate
Применение фикстур: php bin/console doctrine:fixtures:load
Запуск: symfony server:start

Работа:
http://127.0.0.1:8000/rates - Актуальные данные по монетам 
http://127.0.0.1:8000/rates/history - Просмотр истории курсов за определённый период
