# Inventory System

Built using PHP and MySQL

## ERD

ERD visually represents the database structure, including relationships and cardinalities.

```mermaid
erDiagram
    SUPPLIERS {
        INT supplier_id PK "AUTO_INCREMENT"
        VARCHAR supplier_name "NOT NULL"
        VARCHAR contact "NULLABLE"
    }
    ITEMS {
        INT item_id PK "AUTO_INCREMENT"
        VARCHAR item_name "NOT NULL"
        INT supplier_id FK "REFERENCES SUPPLIERS"
        DECIMAL price "NOT NULL"
        INT stock_quantity "DEFAULT 0"
    }
    TRANSACTIONS {
        INT transaction_id PK "AUTO_INCREMENT"
        INT item_id FK "REFERENCES ITEMS"
        ENUM transaction_type "IN or OUT"
        INT quantity "NOT NULL"
        TIMESTAMP date "DEFAULT CURRENT_TIMESTAMP"
    }
    
    SUPPLIERS ||--o| ITEMS : "Provides"
    ITEMS ||--o| TRANSACTIONS : "Recorded In"
```

---

## Data Dictionary

The data dictionary provides a detailed description of the tables, fields, and constraints.

### **Table: `suppliers`**
| Field Name     | Data Type       | Constraints           | Description                     |
|----------------|-----------------|-----------------------|---------------------------------|
| `supplier_id`  | INT             | PRIMARY KEY, AUTO_INCREMENT | Unique supplier identifier      |
| `supplier_name`| VARCHAR(100)    | NOT NULL              | Supplier's name                 |
| `contact`      | VARCHAR(50)     | NULLABLE              | Supplier's contact information  |

### **Table: `items`**
| Field Name        | Data Type       | Constraints                 | Description                       |
|-------------------|-----------------|-----------------------------|-----------------------------------|
| `item_id`         | INT             | PRIMARY KEY, AUTO_INCREMENT | Unique item identifier            |
| `item_name`       | VARCHAR(100)    | NOT NULL                    | Name of the item                  |
| `supplier_id`     | INT             | FOREIGN KEY REFERENCES `suppliers(supplier_id)` | Supplier providing the item       |
| `price`           | DECIMAL(10,2)   | NOT NULL                    | Price per item                    |
| `stock_quantity`  | INT             | DEFAULT 0                   | Quantity of stock available       |

### **Table: `transactions`**
| Field Name         | Data Type       | Constraints                 | Description                       |
|--------------------|-----------------|-----------------------------|-----------------------------------|
| `transaction_id`   | INT             | PRIMARY KEY, AUTO_INCREMENT | Unique transaction identifier     |
| `item_id`          | INT             | FOREIGN KEY REFERENCES `items(item_id)` | Item involved in the transaction  |
| `transaction_type` | ENUM('IN','OUT')| NOT NULL                    | Type of transaction: IN or OUT    |
| `quantity`         | INT             | NOT NULL                    | Quantity moved in transaction     |
| `date`             | TIMESTAMP       | DEFAULT CURRENT_TIMESTAMP   | Date and time of transaction      |

---

## Class Diagram

The Class Diagram describes the system's structure in object-oriented form. 

```mermaid
classDiagram
    class Supplier {
        +INT supplier_id
        +VARCHAR supplier_name
        +VARCHAR contact
        +addSupplier()
        +updateSupplier()
        +deleteSupplier()
    }

    class Item {
        +INT item_id
        +VARCHAR item_name
        +INT supplier_id
        +DECIMAL price
        +INT stock_quantity
        +addItem()
        +updateItem()
        +deleteItem()
    }

    class Transaction {
        +INT transaction_id
        +INT item_id
        +ENUM transaction_type
        +INT quantity
        +TIMESTAMP date
        +recordTransaction()
        +generateReport()
    }

    Supplier "1" --> "0..*" Item : "Provides"
    Item "1" --> "0..*" Transaction : "Has"
```

---

## Install and Use

1. Install the ff:
    - XAMPP
    - PHP
    - Composer
    - Git
2. Run XAMPP SQL/Apache Servers
3. Open a browser to `localhost/phpmyadmin` 
4. Run the script in SQL

```sql
-- Step 1: Create the database
CREATE DATABASE IF NOT EXISTS inventory_system;
USE inventory_system;

-- Step 2: Create the `suppliers` table
CREATE TABLE suppliers (
    supplier_id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(100) NOT NULL,
    contact VARCHAR(50)
);

-- Step 3: Create the `items` table
CREATE TABLE items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(100) NOT NULL,
    supplier_id INT,
    price DECIMAL(10,2) NOT NULL,
    stock_quantity INT DEFAULT 0,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(supplier_id)
        ON DELETE SET NULL
);

-- Step 4: Create the `transactions` table
CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT,
    transaction_type ENUM('IN', 'OUT') NOT NULL,
    quantity INT NOT NULL,
    date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (item_id) REFERENCES items(item_id)
        ON DELETE CASCADE
);
```

OPTIONAL: Sample data

```sql
-- Step 1: Insert sample data into the `suppliers` table
INSERT INTO suppliers (supplier_name, contact) VALUES
('Tech Supplies Co.', 'techsupplies@example.com'),
('Hardware Hub', 'hardwarehub@example.com'),
('Gadget World', 'gadgetworld@example.com');

-- Step 2: Insert sample data into the `items` table
INSERT INTO items (item_name, supplier_id, price, stock_quantity) VALUES
('USB Cable', 1, 2.50, 100),
('Wireless Mouse', 2, 15.00, 50),
('HDMI Cable', 1, 5.00, 75),
('Laptop Stand', 2, 25.00, 30),
('Power Bank', 3, 20.00, 40);

-- Step 3: Insert sample data into the `transactions` table
INSERT INTO transactions (item_id, transaction_type, quantity, date) VALUES
(1, 'IN', 50, '2024-12-01 10:00:00'),
(1, 'OUT', 20, '2024-12-02 15:30:00'),
(2, 'IN', 30, '2024-12-03 09:45:00'),
(3, 'OUT', 10, '2024-12-04 13:20:00'),
(4, 'IN', 20, '2024-12-05 14:10:00'),
(5, 'OUT', 5, '2024-12-06 11:15:00');
```
5. Navigate to `C:/xampp/htdocs`
6. Open a shell (e.g Powershell, bash) then run the script
```shell
git clone https://github.com/wukinnin/inventory_system
cd inventory_system
git checkout main
composer require tecnickcom/tcpdf
```
7. Open a browser to `localhost/inventory_system/index.php`
