Тестовая задача (TelegramBot предоставляющий информацию о курсах валют на опредленную дату)

Для развертывания, приложения приложение требует
- PHP8.3
- MYSQL8.0
- Redis
Возможна настройка с помощью Docker. 
Для развертывния последнего варианта проделайте следующие шаги
1.  git clone https://github.com/last1971/test-task-inova
2. cd ./test-task-inova
3. ./vendor/bin/sail up -d
4. docker exec -it test-task-inova-laravel.test-1 bash
5. Скопировать .env.example в .env и настроить подключение к MYSQL и Redis
6. Настроить следующие переменные 
7. BOT_PATH - дополнительный путь в url для TelegramBot webhook, 
8. BOT_TOKEN - TelegramBot token
9. BOT_SECRET_TOKEN - Дополнительны токен передаваемый вебхуком, для авторизиаци входящего соединения
10. BOT_PROCESS_EXPIRE - время в секундах, сброса процесса отправки сообщения, если он не сработал
11. BOT_LIMIT - ограничение на количество запростов команды /convert
12. BOT_INTERVAL - время в секундах для ограничения
13. Через GET запрос настройте webhook https://api.telegram.org/bot{BOT_TOKEN}/setWebhook?url=https://
    {Ваш домен}/ravbot&secret_token={BOT_SECRET_TOKEN}
14. php artisan migrate
15. php artisan db:seed
16. php artisan queue:work -v (для запуска обработчика очередей)
17. Подключиться к боту и отправить /start
