import React, { useEffect, useState } from 'react';
import { ApolloProvider } from '@apollo/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { client } from './graphql/queries';
import { Navbar } from './components/layout/Navbar';
import { ProductList } from './components/products/ProductList';
import { ProductDetails } from './components/products/ProductDetails';
import { CartItem } from './components/products/types';
import './styles/main.scss';

const App: React.FC = () => {
  const [currentCategory, setCurrentCategory] = useState('all');
  const [isCartOpen, setIsCartOpen] = useState(false);
  const [cartItems, setCartItems] = useState<CartItem[]>(() => {
    const stored = localStorage.getItem('cart');
    if (stored != null) {
      return JSON.parse(stored) as CartItem[];
    } else {
      return [];
    }
  });

  useEffect(() => {
    localStorage.setItem('cart', JSON.stringify(cartItems));
  }, [cartItems]);

  const handleUpdateCart = (items: CartItem[]) => {
    setCartItems(items);
  };

  return (
    <ApolloProvider client={client}>
      <BrowserRouter>
        <div className="app">
          <Navbar
            cartItems={cartItems}
            onUpdateCart={handleUpdateCart}
            onCategoryChange={setCurrentCategory}
            currentCategory={currentCategory}
            isCartOpen={isCartOpen}
            setIsCartOpen={setIsCartOpen}
          />
          <main className="main-content">
            <Routes>
              <Route
                path="/"
                element={
                  <ProductList
                    currentCategory={currentCategory}
                    cartItems={cartItems}
                    onUpdateCart={handleUpdateCart}
                    cartStatus={isCartOpen}
                  />
                }
              />
              <Route
                path="/product/:id"
                element={
                  <ProductDetails
                    cartItems={cartItems}
                    onUpdateCart={handleUpdateCart}
                    cartStatus={isCartOpen}
                  />
                }
              />
            </Routes>
          </main>
        </div>
      </BrowserRouter>
    </ApolloProvider>
  );
};

export default App;
