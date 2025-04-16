import { gql } from "@apollo/client";
import { ApolloClient, InMemoryCache } from "@apollo/client";

export const GET_PRODUCTS = gql`
  query GetProducts {
    products {
      id
      name
      inStock
      description
      category
      brand
      prices {
        id
        amount
        currencyLabel
        currencySymbol
      }
      galleries {
        id
        imageUrl
      }
      attributes {
        attributeValue {
          id
          value
          displayValue
          attribute {
            id
            name
            type
          }
        }
      }
    }
  }
`;

export const GET_PRODUCT = gql`
  query GetProduct($productId: String!) {
    product(id: $productId) {
      id
      name
      inStock
      description
      category
      brand
      galleries {
        id
        imageUrl
      }
      prices {
        id
        amount
        currencyLabel
        currencySymbol
      }
      attributes {
        attributeValue {
          id
          value
          displayValue
          attribute {
            id
            name
            type
          }
        }
      }
    }
  }
`;

export const GET_CATEGORIES = gql`
  query GetCategories {
    categories {
      id
      name
    }
  }
`;

export const PLACE_ORDER = gql`
  mutation PlaceOrder($items: [OrderItemInput!]!) {
    placeOrder(items: $items)
  }
`;

export const client = new ApolloClient({
  uri: "https://e34efc0b-ba48-46ab-b4d2-e29c5c47d944-00-3nm7uc472x0t8.worf.replit.dev/graphql",
  cache: new InMemoryCache(),
});
