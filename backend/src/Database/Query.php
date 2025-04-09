<?php

namespace MvpMarket\Database;

use MvpMarket\Database\Database;
use PDO, PDOException;


class Query extends Database
{
    public function getCategories()
    {
        try {
            $connection = $this->getConnection();
            $sql = "SELECT id, name FROM categories;";
            $stmt = $connection->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }
    }

    public function getAllProducts()
    {
        try {
            $connection = $this->getConnection();
            $sql = "
                SELECT 
                    p.id AS product_id,
                    p.name,
                    p.in_stock,
                    p.description,
                    p.category,
                    p.brand,

                    pr.id AS price_id,
                    pr.amount,
                    pr.currency_label,
                    pr.currency_symbol,

                    g.id AS gallery_id,
                    g.image_url,

                    av.id AS attribute_value_id,
                    av.value,
                    av.display_value,

                    a.id AS attribute_id,
                    a.name AS attribute_name,
                    a.type AS attribute_type

                FROM products p
                LEFT JOIN prices pr ON pr.product_id = p.id
                LEFT JOIN galleries g ON g.product_id = p.id
                LEFT JOIN product_attribute_values pav ON pav.product_id = p.id
                LEFT JOIN attribute_values av ON pav.attribute_value_id = av.id
                LEFT JOIN attributes a ON av.attribute_id = a.id
                ORDER BY p.id;
            ";
            $stmt = $connection->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function getProductByID(string $productId)
    {
        try {
            $connection = $this->getConnection();
            $sql = "
                SELECT 
                    p.id AS product_id,
                    p.name,
                    p.in_stock,
                    p.description,
                    p.category,
                    p.brand,

                    pr.id AS price_id,
                    pr.amount,
                    pr.currency_label,
                    pr.currency_symbol,

                    g.id AS gallery_id,
                    g.image_url,

                    av.id AS attribute_value_id,
                    av.value,
                    av.display_value,

                    a.id AS attribute_id,
                    a.name AS attribute_name,
                    a.type AS attribute_type

                FROM products p
                LEFT JOIN prices pr ON pr.product_id = p.id
                LEFT JOIN galleries g ON g.product_id = p.id
                LEFT JOIN product_attribute_values pav ON pav.product_id = p.id
                LEFT JOIN attribute_values av ON pav.attribute_value_id = av.id
                LEFT JOIN attributes a ON av.attribute_id = a.id
                WHERE p.id = :productId;
            ";
            $stmt = $connection->prepare($sql);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

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
