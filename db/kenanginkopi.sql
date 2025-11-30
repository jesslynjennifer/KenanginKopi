CREATE DATABASE IF NOT EXISTS kenanginkopi;
USE kenanginkopi;

-- Users table
CREATE TABLE Users(
    UserID CHAR(5) PRIMARY KEY,
    FullName VARCHAR(50) NOT NULL,
    UserName VARCHAR(50) NOT NULL UNIQUE,
    UserEmail VARCHAR(50) NOT NULL UNIQUE,
    UserPassword VARCHAR(100) NOT NULL,
    UserRole ENUM('Admin', 'User') NOT NULL DEFAULT 'User'
);

-- Store table
CREATE TABLE Store(
    StoreID CHAR(5) PRIMARY KEY,
    StoreName VARCHAR(50) NOT NULL,
    StoreLocation VARCHAR(100) NOT NULL
);

-- Coffee table
CREATE TABLE Coffee(
    CoffeeID CHAR(5) PRIMARY KEY,
    CoffeeName VARCHAR(50) NOT NULL,
    CofeeDesc VARCHAR(100) NOT NULL
);

-- StoreCoffee table
CREATE TABLE StoreCoffee(
    StoreID CHAR(5) NOT NULL,
    CoffeeID CHAR(5) NOT NULL,
    Price DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (StoreID) REFERENCES Store(StoreID) ON DELETE CASCADE,
    FOREIGN KEY (CoffeeID) REFERENCES Coffee(CoffeeID) ON DELETE CASCADE
);

-- Transaction table
CREATE TABLE Transactions(
    TransactionID CHAR(5) PRIMARY KEY,
    UserID CHAR(5) NOT NULL,
    StoreID CHAR(5) NOT NULL,
    TransactionDate DATE NOT NULL,
    TotalPrice DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID) ON DELETE CASCADE,
    FOREIGN KEY (StoreID) REFERENCES Store(StoreID) ON DELETE CASCADE
);

-- TransactionDetails table
CREATE TABLE TransactionDetails(
    TransactionID CHAR(5) NOT NULL,
    CoffeeID CHAR(5) NOT NULL,
    Qty INT NOT NULL,
    SubTotal DECIMAL(8,2) NOT NULL,
    FOREIGN KEY (TransactionID) REFERENCES Transactions(TransactionID) ON DELETE CASCADE,
    FOREIGN KEY (CoffeeID) REFERENCES Coffee(CoffeeID) ON DELETE CASCADE
);