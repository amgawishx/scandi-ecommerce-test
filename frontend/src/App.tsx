import React, { useEffect, useState } from 'react';
import { ApolloProvider } from '@apollo/client';
import {
  BrowserRouter,
  Routes,
  Route,
  Navigate,
  useParams,
} from 'react-router-dom';
import { client } from './graphql/queries';
import { Navbar } from './components/layout/Navbar';
import { ProductList } from './components/products/ProductList';
import { ProductDetails } from './components/products/ProductDetails';
import { CartItem } from './components/products/types';
import './styles/main.scss';

const AppWrapper: React.FC = () => (
  <ApolloProvider client={client}>
    <BrowserRouter>
      <App />
    </BrowserRouter>
  </ApolloProvider>
);

const App: React.FC = () => {
  const { category = 'all' } = useParams<{ category?: string }>();
  const [currentCategory, setCurrentCategory] = useState(category);

  const [isCartOpen, setIsCartOpen] = useState(false);
  const [cartItems, setCartItems] = useState<CartItem[]>(() => {
    const stored = localStorage.getItem('cart');
    return stored ? JSON.parse(stored) : [];
  });

  useEffect(() => {
    setCurrentCategory(category);
  }, [category]);

  useEffect(() => {
    localStorage.setItem('cart', JSON.stringify(cartItems));
  }, [cartItems]);

  const handleUpdateCart = (items: CartItem[]) => {
    setCartItems(items);
  };

  return (
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
          <Route path="/" element={<Navigate to="/all" replace />} />
          <Route
            path="/:category"
            element={
              <ProductList
                currentCategory={currentCategory}
                cartItems={cartItems}
                onUpdateCart={handleUpdateCart}
                cartStatus={isCartOpen}
                cartController={setIsCartOpen}
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
                cartController={setIsCartOpen}
              />
            }
          />
        </Routes>
      </main>
    </div>
  );
};

export default AppWrapper;
