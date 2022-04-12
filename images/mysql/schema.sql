CREATE TABLE IF NOT EXISTS account (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    balance DECIMAL(10,2) NOT NULL DEFAULT 0,
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