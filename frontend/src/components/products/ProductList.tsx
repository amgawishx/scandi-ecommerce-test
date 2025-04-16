import React from "react";
import { useQuery } from "@apollo/client";
import { useNavigate } from "react-router-dom";
import { GET_PRODUCTS } from "../../graphql/queries";
import { CartItem, ProductAttribute } from "./types";
import { FaShoppingCart } from "react-icons/fa";

interface Price {
  id: number;
  amount: number;
  currencyLabel: string;
  currencySymbol: string;
}

interface Gallery {
  id: number;
  imageUrl: string;
}

interface Product {
  id: string;
  name: string;
  description: string;
  category: string;
  inStock: boolean;
  prices: Price[];
  galleries: Gallery[];
  attributes: ProductAttribute[];
}

interface ProductListProps {
  currentCategory: string;
  cartItems: CartItem[];
  onUpdateCart: (items: CartItem[]) => void;
  cartController: (status: boolean) => void;
  cartStatus: boolean;
}

export const ProductList: React.FC<ProductListProps> = ({
  currentCategory,
  cartItems,
  onUpdateCart,
  cartStatus,
  cartController = () => {},
}) => {
  const navigate = useNavigate();
  const { loading, error, data } = useQuery(GET_PRODUCTS);

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error.message}</div>;

  const products = data?.products || [];
  const filteredProducts =
    currentCategory === "all"
      ? products
      : products.filter(
          (product: Product) => product.category === currentCategory,
        );

  const handleAddToCart = (product: Product) => {
    const defaultPrice = product.prices[0];
    if (!defaultPrice) return;

    const attributeMap = new Map<string, { id: number; value: string }>();
    for (const attr of product.attributes) {
      const attrName = attr.attributeValue.attribute.name;
      if (!attributeMap.has(attrName)) {
        attributeMap.set(attrName, {
          id: attr.attributeValue.id,
          value: attr.attributeValue.value,
        });
      }
    }
    const selectedAttributes = Object.fromEntries(attributeMap);

    const existingItem = cartItems.find(
      (item) =>
        item.id === product.id &&
        Object.keys(selectedAttributes).every(
          (key) =>
            item.selectedAttributes[key]?.id === selectedAttributes[key]?.id &&
            item.selectedAttributes[key]?.value ===
              selectedAttributes[key]?.value,
        ),
    );

    if (existingItem) {
      const updatedItems = cartItems.map((item) =>
        item.id === product.id &&
        Object.keys(selectedAttributes).every(
          (key) =>
            item.selectedAttributes[key]?.id === selectedAttributes[key]?.id &&
            item.selectedAttributes[key]?.value ===
              selectedAttributes[key]?.value,
        )
          ? { ...item, quantity: item.quantity + 1 }
          : item,
      );
      onUpdateCart(updatedItems);
    } else {
      const availableAttributes: { [key: string]: string[] } = {};
      for (const attr of product.attributes) {
        const name = attr.attributeValue.attribute.name;
        const value = attr.attributeValue.value;

        if (!availableAttributes[name]) {
          availableAttributes[name] = [];
        }
        if (!availableAttributes[name].includes(value)) {
          availableAttributes[name].push(value);
        }
      }

      onUpdateCart([
        ...cartItems,
        {
          id: product.id,
          name: product.name,
          price: defaultPrice.amount,
          quantity: 1,
          image: product.galleries[0]?.imageUrl || "",
          selectedAttributes,
          availableAttributes,
        },
      ]);
    }
    cartController(true);
  };

  return (
    <div className="product-list-wrapper">
      {cartStatus && <div className="grey-out-overlay" />}

      <div className="product-list-content">
        <h2>
          {currentCategory[0].toLocaleUpperCase() +
            currentCategory.slice(1) +
            " Products"}
        </h2>
        <div className="products-grid">
          {filteredProducts.map((product: Product) => {
            const defaultPrice = product.prices[0];
            const imageUrl = product.galleries[0]?.imageUrl;

            return (
              <div
                key={product.id}
                className="product-card"
                data-testid={`product-${product.name.replaceAll(" ", "-").toLowerCase()}`}
              >
                <div
                  className="product-image-container"
                  onClick={() => navigate(`/product/${product.id}`)}
                >
                  <img
                    src={imageUrl}
                    alt={product.name}
                    className="product-image"
                  />
                  {!product.inStock && (
                    <div className="out-of-stock-overlay">OUT OF STOCK</div>
                  )}
                  {product.inStock && (
                    <button
                      className="add-to-cart-icon-button"
                      onClick={(e) => {
                        e.stopPropagation();
                        handleAddToCart(product);
                      }}
                    >
                      <FaShoppingCart />
                    </button>
                  )}
                </div>
                <div className="product-content">
                  <h3 className="product-name">{product.name}</h3>
                  {defaultPrice && (
                    <p className="product-price">
                      {defaultPrice.currencySymbol}
                      {defaultPrice.amount.toFixed(2)}
                    </p>
                  )}
                </div>
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
};
