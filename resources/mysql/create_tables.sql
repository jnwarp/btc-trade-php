CREATE TABLE accounts (
    account_id int NOT NULL AUTO_INCREMENT,
    account_enabled BOOLEAN NOT NULL,
    email CHAR(254) NOT NULL,
    password CHAR(31) NOT NULL,
    CONSTRAINT pk_AccountID PRIMARY KEY(account_id)
);

CREATE TABLE prices (
    price_id int NOT NULL AUTO_INCREMENT,
    coin_symbol char(4) NOT NULL,
    time datetime NOT NULL,
    usd_value decimal(13,2) NOT NULL,
    CONSTRAINT pk_PriceId PRIMARY KEY(price_id)
);

CREATE TABLE orderbook (
    order_id int NOT NULL AUTO_INCREMENT,
    account_id int NOT NULL,
    num_shares int NOT NULL,
    order_type char(10) NOT NULL,
    price_id int NOT NULL,
    balance_usd decimal(13,2) NOT NULL,
    balance_coin decimal(13,8) NOT NULL
    CONSTRAINT pk_OrderId PRIMARY KEY(order_id),
    CONSTRAINT fk_AccountId FOREIGN KEY(account_id) REFERENCES accounts(account_id),
    CONSTRAINT fk_PriceId FOREIGN KEY(price_id) REFERENCES prices(price_id)
);