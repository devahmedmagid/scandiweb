import { Routes, Route } from 'react-router-dom';
import { useEffect, useState } from 'react';
import ProductList from './pages/ProductList';
import ProductAdd from './pages/ProductAdd';
import axios from 'axios';
import './styles/Style.min.css';

function App() {
  const [products, setProducts] = useState([]);
  useEffect(() => {
    getProducts();
  }, []);

  const getProducts = () => {
    axios.get('http://localhost/backend/').then((response) => {
      setProducts(response.data);
    });
  };

  const handleChange = (id) => {
    const copyProducts = [...products];
    const modifiedProducts = copyProducts.map((product) => {
      if (id === product.id) {
        product.checked = !product.checked;
        axios.put('http://localhost/backend/', product);
      }

      return product;
    });

    setProducts(modifiedProducts);
  };

  const handleRemove = () => {
    const copyProducts = [...products];
    const modifiedProducts = copyProducts.filter(
      (product) => product.checked !== true
    );

    setProducts(modifiedProducts);
    axios.delete(`http://localhost/backend/`, products);
  };

  return (
    <div className='container'>
      <Routes>
        <Route
          path='/'
          element={
            <ProductList
              products={products}
              handleChange={handleChange}
              handleRemove={handleRemove}
            />
          }
        />
        <Route path='/add-product' element={<ProductAdd />} />
      </Routes>
    </div>
  );
}

export default App;
