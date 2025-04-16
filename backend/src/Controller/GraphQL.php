<?php

namespace MvpMarket\Controller;

use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use MvpMarket\Models\OrderModel;
use MvpMarket\Models\CategoryModel;
use MvpMarket\Models\ProductModel;
use MvpMarket\GraphQL\Types\TypesRegistry;
use RuntimeException;
use Throwable;

class GraphQL
{
    public static function handle()
    {
        // CORS Headers
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        ini_set('display_errors', '1');
        error_reporting(E_ALL);

        try {
            error_log("[DEBUG] Entering GraphQL::handle");

            // Validate that all Types exist
            error_log("[DEBUG] Validating TypesRegistry");
            $productType = TypesRegistry::product();
            $categoryType = TypesRegistry::category();
            $orderInputType = TypesRegistry::order();

            if (!$productType || !$categoryType || !$orderInputType) {
                throw new RuntimeException("One or more GraphQL types are null (check TypesRegistry::product/category/order)");
            }

            // Define GraphQL Query Type
            error_log("[DEBUG] Defining Query type");
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'products' => [
                        'type' => Type::listOf($productType),
                        'resolve' => function () {
                            error_log("[DEBUG] Resolving 'products'");
                            return (new ProductModel())->getAll();
                        },
                    ],
                    'product' => [
                        'type' => $productType,
                        'args' => [
                            'id' => Type::nonNull(Type::string()),
                        ],
                        'resolve' => function ($root, array $args) {
                            error_log("[DEBUG] Resolving 'product' with ID: " . $args['id']);
                            return (new ProductModel())->getOne($args['id']);
                        },
                    ],
                    'categories' => [
                        'type' => Type::listOf($categoryType),
                        'resolve' => function (): array {
                            error_log("[DEBUG] Resolving 'categories'");
                            return (new CategoryModel())->getAll();
                        },
                    ],
                ],
            ]);

            // Define Mutation Type
            error_log("[DEBUG] Defining Mutation type");
            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'placeOrder' => [
                        'type' => Type::boolean(),
                        'args' => [
                            'items' => Type::nonNull(Type::listOf($orderInputType)),
                        ],
                        'resolve' => function ($root, array $args) {
                            error_log("[DEBUG] Resolving 'placeOrder'");
                            return (new OrderModel())->placeOrder($args['items']);
                        },
                    ],
                ],
            ]);

            // Build schema
            error_log("[DEBUG] Building Schema");
            $schema = new Schema(
                (new SchemaConfig())
                    ->setQuery($queryType)
                    ->setMutation($mutationType)
            );

            // Read and parse input
            error_log("[DEBUG] Reading input from php://input");
            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to read raw input from php://input');
            }

            error_log("[DEBUG] Decoding JSON input: $rawInput");
            $input = json_decode($rawInput, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Invalid JSON: ' . json_last_error_msg());
            }

            $query = $input['query'] ?? null;
            $variableValues = $input['variables'] ?? null;

            if (!$query) {
                throw new RuntimeException('Missing query in input');
            }

            // Execute the GraphQL query
            error_log("[DEBUG] Executing GraphQL query");
            $result = GraphQLBase::executeQuery($schema, $query, null, null, $variableValues);
            $output = $result->toArray();

            error_log("[DEBUG] Query executed successfully");

        } catch (Throwable $e) {
            error_log("[GraphQL ERROR] " . $e->getMessage());
            $output = [
                'errors' => [
                    [
                        'message' => 'Internal server error',
                        'debug' => $e->getMessage(), // optional: remove in prod
                    ]
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($output, JSON_PRETTY_PRINT);
    }
}
