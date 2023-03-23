import { Route, Routes } from 'react-router-dom';
import './App.css';
import ProductList from './products/ProductList';
import AddProduct from './products/AddProduct';

function App() {
  return (
    <div className="App">
        <Routes>
          <Route path="/" element={<ProductList />} />
          <Route path="/add-product" element={<AddProduct />} />
        </Routes>
    </div>
  );
}

export default App;
