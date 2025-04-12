<?php

namespace MvpMarket\Database;

use MvpMarket\Database\Database;
use PDO, PDOException;


class Query extends Database
{
    public function placeOrder(array $items): bool
    {
        try {
            $connection = $this->getConnection();
            $connection->beginTransaction();

            $orderSQL = "
            INSERT INTO orders (product_id, quantity, price, image, name, created_at)
            VALUES (:product_id, :quantity, :price, :image, :name, :created_at)
        ";

            $attributeSQL = "
            INSERT INTO order_attribute_values (order_id, attribute_value_id)
            VALUES (:order_id, :attribute_value_id)
        ";

            $orderStmt = $connection->prepare($orderSQL);
            $attributeStmt = $connection->prepare($attributeSQL);

            foreach ($items as $item) {
                $orderStmt->execute([
                    ':product_id' => $item['productId'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price'],
                    ':image' => $item['image'],
                    ':name' => $item['name'],
                    ':created_at' => date('Y-m-d H:i:s'),
                ]);

                $orderId = $connection->lastInsertId();

                if (!empty($item['selectedAttributes']) && is_array($item['selectedAttributes'])) {
                    foreach ($item['selectedAttributes'] as $attributeValueId) {
                        $attributeStmt->execute([
                            ':order_id' => $orderId,
                            ':attribute_value_id' => $attributeValueId,
                        ]);
                    }
                }
            }

            $connection->commit();
            return true;
            
        } catch (PDOException $e) {
            if (!empty($connection)) {
                $connection->rollBack();
            }
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

}
