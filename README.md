# Приложение для списания с баланса

Для развертывания приложений запустите следующую команду:
```
docker-compose up -d
```

После развертывания вы можете применить схему schema.sql, с помощью запуска скрипта:
```
./init-db.sh
```
Он так же позволяет обновить баланс счета пользователя, если пользователь уже существует.

Нужно не забыть добавить запись в /etc/hosts:
```
127.0.0.1 finance-app
```

Тестовая схема выглядит вот так:
```sql
CREATE TABLE IF NOT EXISTS account (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    balance INT NOT NULL DEFAULT 0,
    access_token VARCHAR(255)
) ENGINE = InnoDB;

INSERT INTO account (
    username,
    password,
    balance
) VALUES (
    'test_user',
    '$2y$10$Aa/Z3Qp2wl1cV1gvVzanVeZbxOSw.wb./4UJPW6t5hfamqkq3kFfO', -- 12345678
    10000
) ON DUPLICATE KEY UPDATE balance=10000;
```