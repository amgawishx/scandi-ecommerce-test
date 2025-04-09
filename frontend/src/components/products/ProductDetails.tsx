import React, { useState, useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { useQuery } from '@apollo/client';
import { GET_PRODUCT } from '../../graphql/queries';
import { CartItem, Product } from './types';

interface ProductDetailsProps {
  onUpdateCart?: (items: CartItem[]) => void;
  cartItems?: CartItem[];
  cartStatus?: boolean;
}

interface SelectedAttributes {
  [key: string]: string;
}

export const ProductDetails: React.FC<ProductDetailsProps> = ({
  onUpdateCart = () => { },
  cartItems = [],
  cartStatus = false
}) => {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const [selectedAttributes, setSelectedAttributes] = useState<SelectedAttributes>({});
  const [selectedImageIndex, setSelectedImageIndex] = useState(0);
  const { loading, error, data } = useQuery(GET_PRODUCT, {
    variables: { productId: id },
    skip: !id
  });

  useEffect(() => {
    if (data?.product?.attributes) {
      setSelectedAttributes({});
    }
  }, [data]);

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error.message}</div>;
  if (!data?.product) return <div>Product not found</div>;

  const product: Product = data.product;
  const defaultPrice = product.prices[0];

  const groupedAttributes = (product.attributes || []).reduce((acc, attr) => {
    const attrName = attr.attributeValue.attribute.name;
    if (!acc[attrName]) {
      acc[attrName] = {
        name: attrName,
        type: attr.attributeValue.attribute.type,
        values: []
      };
    }
    acc[attrName].values.push({
      id: attr.attributeValue.id,
      value: attr.attributeValue.value,
      displayValue: attr.attributeValue.displayValue
    });
    return acc;
  }, {} as { [key: string]: { name: string; type: string; values: { id: number; value: string; displayValue: string; }[] } });

  const handleAttributeSelect = (attributeName: string, value: string) => {
    setSelectedAttributes(prev => ({
      ...prev,
      [attributeName]: value
    }));
  };

  const handleAddToCart = () => {
    if (!onUpdateCart || !defaultPrice) return;

    const hasAllAttributes = Object.keys(groupedAttributes).every(
      attrName => selectedAttributes[attrName]
    );

    if (!hasAllAttributes) {
      alert('Please select all options before adding to cart');
      return;
    }

    const mainImage = product.galleries[selectedImageIndex]?.imageUrl || '';

    const cartItem = {
      id: product.id,
      name: product.name,
      price: defaultPrice.amount,
      quantity: 1,
      image: mainImage,
      selectedAttributes
    };

    const existingItemIndex = cartItems.findIndex(item =>
      item.id === product.id &&
      Object.entries(item.selectedAttributes).every(
        ([key, value]) => selectedAttributes[key] === value
      )
    );

    if (existingItemIndex >= 0) {
      const updatedItems = cartItems.map((item, index) =>
        index === existingItemIndex
          ? { ...item, quantity: item.quantity + 1 }
          : item
      );
      onUpdateCart(updatedItems);
    } else {
      onUpdateCart([...cartItems, cartItem]);
    }
  };

  const hasAllAttributesSelected = Object.keys(groupedAttributes).every(
    attrName => selectedAttributes[attrName]
  );

  const renderAttributeOptions = (
    name: string,
    type: string,
    values: { id: number; value: string; displayValue: string }[]
  ) => (
    <div key={name} className="attribute-section">
      <h3 className="attribute-name">{name}:</h3>
      <div className="attribute-values">
        {values.map(({ id, value, displayValue }) => {
          const isSelected = selectedAttributes[name] === value;
          return (
            <button
              key={id}
              className={`attribute-button ${type === 'swatch' ? 'swatch' : ''} ${isSelected ? 'selected' : ''}`}
              onClick={() => handleAttributeSelect(name, value)}
              style={type === 'swatch' ? { backgroundColor: value } : undefined}
              title={type === 'swatch' ? displayValue : undefined}
            >
              {type === 'swatch' ? '' : displayValue}
            </button>
          );
        })}
      </div>
    </div>
  );

  return (
    <div className="product-details-wrapper">
      {cartStatus && <div className="grey-out-overlay" />}

      <div className="product-details">
        <button className="back-button" onClick={() => navigate('/')}>← Back to Products</button>
        <div className="product-details-content">
          <div className="product-gallery-wrapper">
            <div className="thumbnail-list">
              {product.galleries.map((gallery, index) => (
                <img
                  key={gallery.id}
                  src={gallery.imageUrl}
                  alt={`Thumbnail ${index}`}
                  className={`thumbnail ${index === selectedImageIndex ? 'selected' : ''}`}
                  onClick={() => setSelectedImageIndex(index)}
                />
              ))}
            </div>
            <div className="main-image-wrapper">
              <button className="nav-arrow left" onClick={() =>
                setSelectedImageIndex(prev =>
                  prev === 0 ? product.galleries.length - 1 : prev - 1
                )
              }>‹</button>
              <img
                src={product.galleries[selectedImageIndex]?.imageUrl}
                alt={product.name}
                className="main-image"
              />
              <button className="nav-arrow right" onClick={() =>
                setSelectedImageIndex(prev =>
                  (prev + 1) % product.galleries.length
                )
              }>›</button>
            </div>
          </div>

          <div className="product-info">
            <h1 className="product-name">{product.name}</h1>
            {product.brand && <p className="product-brand">Brand: {product.brand}</p>}
            {Object.entries(groupedAttributes).map(([name, { type, values }]) =>
              renderAttributeOptions(name, type, values)
            )}
            {defaultPrice && (
              <div className="product-price">
                <p>{defaultPrice.currencySymbol}{defaultPrice.amount.toFixed(2)}</p>
              </div>
            )}
            <div className="product-description" dangerouslySetInnerHTML={{ __html: product.description }} />
            <button
              className={`add-to-cart-button ${(!product.inStock || !hasAllAttributesSelected) ? 'out-of-stock' : 'in-stock'}`}
              onClick={() => product.inStock && hasAllAttributesSelected && handleAddToCart()}
              disabled={!product.inStock || !hasAllAttributesSelected}
            >
              {product.inStock ? 'Add to Cart' : 'Out of Stock'}
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};
