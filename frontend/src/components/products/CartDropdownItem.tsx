import React from 'react';
import { useQuery } from '@apollo/client';
import { GET_PRODUCT } from '../../graphql/queries';
import { CartItem } from './types';

interface CartDropdownItemProps {
  item: CartItem;
  onUpdateCart: (updatedItem: CartItem | null, removeId?: string) => void;
}

export const CartDropdownItem: React.FC<CartDropdownItemProps> = ({ item, onUpdateCart }) => {
  const { data, loading } = useQuery(GET_PRODUCT, {
    variables: { productId: item.id },
  });

  const handleQuantityChange = (change: number) => {
    const newQuantity = item.quantity + change;
    if (newQuantity <= 0) {
      onUpdateCart(null, item.id);
    } else {
      onUpdateCart({ ...item, quantity: newQuantity });
    }
  };

  if (loading) return <div>Loading...</div>;

  const groupedAttributes = (data?.product?.attributes || []).reduce((acc: any, attr: any) => {
    const attrName = attr.attributeValue.attribute.name;
    if (!acc[attrName]) {
      acc[attrName] = {
        name: attrName,
        type: attr.attributeValue.attribute.type,
        values: [],
      };
    }
    acc[attrName].values.push({
      id: attr.attributeValue.id,
      value: attr.attributeValue.value,
      displayValue: attr.attributeValue.displayValue,
    });
    return acc;
  }, {});

  return (
    <div className="cart-item">
      <div className="cart-item-image">
        <img src={item.image} alt={item.name} />
      </div>
      <div className="cart-item-details">
        <h4 className="cart-item-name">{item.name}</h4>

        {Object.entries(groupedAttributes).map(([name, { type, values }]: any) => (
          <div key={name} className="cart-attribute-section" data-testid={`cart-item-attribute-${name.replace(' ', '-').toLowerCase()}`}>
            <h4 className="cart-attribute-name">{name}:</h4>
            <div className="cart-attribute-values">
              {values.map(({ id, value, displayValue }: any) => {
                const isSelected = item.selectedAttributes[name]?.value === value;

                return (
                  <div
                    key={id}
                    className={`cart-attribute-button ${type === 'swatch' ? 'swatch' : ''} ${isSelected ? 'selected' : ''}`}
                    data-testid={`cart-item-attribute-${name.replace(' ', '-').toLowerCase()}-${displayValue.replace(' ', '-').toLowerCase()}-${isSelected ? 'selected' : ''}`}
                    style={type === 'swatch' ? { backgroundColor: value } : undefined}
                    title={type === 'swatch' ? displayValue : undefined}
                  >
                    {type === 'swatch' ? '' : displayValue}
                  </div>
                );
              })}
            </div>
          </div>
        ))}

        <div className="cart-item-price">${item.price.toFixed(2)}</div>
        <div className="quantity-controls">
          <button onClick={() => handleQuantityChange(-1)} className="quantity-button" data-testid='cart-item-amount-decrease'>-</button>
          <span className="quantity" data-testid='cart-item-amount'>{item.quantity}</span>
          <button onClick={() => handleQuantityChange(1)} className="quantity-button" data-testid='cart-item-amount-increase'>+</button>
        </div>
      </div>
    </div>
  );
};
