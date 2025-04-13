import React from 'react';
import { CartItem } from './types';
import { CartDropdownItem } from './CartDropdownItem';
import { client, PLACE_ORDER } from '../../graphql/queries';

interface CartDropdownProps {
  cartItems: CartItem[];
  onUpdateCart: (items: CartItem[]) => void;
}

export const CartDropdown: React.FC<CartDropdownProps> = ({ cartItems, onUpdateCart }) => {
  const placeOrder = async (cartItems: CartItem[]) => {
    const orderItems = cartItems.map((item) => ({
      productId: item.id,
      name: item.name,
      price: item.price,
      quantity: item.quantity,
      image: item.image,
      selectedAttributes: Object.entries(item.selectedAttributes).map(
        ([name, { id, value }]) => ({ name, id, value })
      ),
    }));

    try {
      const { data } = await client.mutate({
        mutation: PLACE_ORDER,
        variables: { items: orderItems },
      });

      console.log('Order placed:', data.placeOrder);
      return data.placeOrder;
    } catch (error) {
      console.error('Error placing order:', error);
      throw error;
    }
  };

  const handleItemUpdate = (updatedItem: CartItem | null, removeId?: string) => {
    if (updatedItem === null) {
      onUpdateCart(cartItems.filter(i => i.id !== removeId));
    } else {
      onUpdateCart(
        cartItems.map(i => (i.id === updatedItem.id ? updatedItem : i))
      );
    }
  };

  const totalPrice = cartItems.reduce(
    (sum, item) => sum + item.price * item.quantity,
    0
  );

  return (
    <div className="cart-dropdown">
      <div className="cart-dropdown-header">
        <h3>My Bag</h3>
        <span className="cart-item-count">
          {cartItems.length} {cartItems.length > 1 ? 'Items' : 'Item'}
        </span>
      </div>

      {cartItems.length === 0 ? (
        <div className="cart-footer">
          <div className="empty-cart">Your cart is empty</div>
          <div className="cart-total" data-testid='cart-total'>
            <span>Total: </span>
            <span>$0</span>
          </div>
          <button onClick={() => onUpdateCart([])} className="checkout-button" disabled>
            Checkout
          </button>
        </div>
      ) : (
        <>
          <div className="cart-items">
            {cartItems.map((item, index) => (
              <CartDropdownItem
                key={`${item.id}-${index}`}
                item={item}
                onUpdateCart={handleItemUpdate}
              />
            ))}
          </div>
          <div className="cart-footer">
            <div className="cart-total" data-testid='cart-total'>
              <span>Total:</span>
              <span>${totalPrice.toFixed(2)}</span>
            </div>
            <button
              onClick={async () => {
                try {
                  await placeOrder(cartItems);
                  onUpdateCart([]);
                } catch (error) {
                  console.error('Failed to place order:', error);
                }
              }}
              className="checkout-button"
            >
              Checkout
            </button>
          </div>
        </>
      )}
    </div>
  );
};
