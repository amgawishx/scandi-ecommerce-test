import React from 'react';
import { Link } from 'react-router-dom';
import { FaShoppingCart } from 'react-icons/fa';
import { CartItem } from '../products/types';
import { CartDropdown } from '../products/CartDropdown';
import { useQuery } from '@apollo/client';
import { GET_CATEGORIES } from '../../graphql/queries';
import '../../styles/_navbar.scss'

interface NavbarProps {
  currentCategory: string;
  onCategoryChange: (category: string) => void;
  cartItems: CartItem[];
  onUpdateCart: (items: CartItem[]) => void;
  isCartOpen: boolean;
  setIsCartOpen: React.Dispatch<React.SetStateAction<boolean>>
}

export const Navbar: React.FC<NavbarProps> = ({
  currentCategory,
  onCategoryChange,
  cartItems,
  onUpdateCart,
  isCartOpen,
  setIsCartOpen
}) => {
  const { loading, error, data } = useQuery(GET_CATEGORIES);

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error.message}</div>;

  const categories = data?.categories || [];

  return (
    <nav className="navbar">
      <div className="navbar-content">
        <div className="navbar-left">
          {categories.map((category: { id: string; name: string }) => (
            <button
              key={category.id}
              className={`category-button ${currentCategory === category.name ? 'active' : ''}`}
              onClick={() => onCategoryChange(category.name)}
            >
              {category.name[0].toLocaleUpperCase() + category.name.slice(1)}
            </button>
          ))}
        </div>
        <div className="navbar-center">
          <Link to="/" className="logo">
            MVP Market
          </Link>
        </div>
        <div className="navbar-right">
          <div className="cart-container">
            <button
              data-testid='cart-btn'
              className="cart-button" 
              onClick={() => setIsCartOpen(!isCartOpen)}
            >
              <FaShoppingCart className="cart-icon" />
              {cartItems.length > 0 && (
                <span className="cart-count">{cartItems.length}</span>
              )}
            </button>
            {isCartOpen && (
              <CartDropdown
                cartItems={cartItems}
                onUpdateCart={onUpdateCart}
              />
            )}
          </div>
        </div>
      </div>
    </nav>
  );
}; 