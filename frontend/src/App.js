import { Route, Routes } from 'react-router-dom';
import './App.css';
import ProductList from './pages/ProductList';
import ProductAdd from './pages/ProductAdd';
import Footer from './components/Footer';

function App() {
    const apiUrl = process.env.REACT_APP_API_BASE_URL;
    const handleAddProductUrl = `${apiUrl}/${process.env.REACT_APP_HANDLE_ADD_PRODUCT}`;
    const showAllProductsUrl = `${apiUrl}/${process.env.REACT_APP_SHOW_ALL_PRODUCTS}`;
  return (
    <div className="App">
        <Routes>
          <Route path="/" element={<ProductList apiUrl={showAllProductsUrl} />} />
          <Route path="/add-product" element={<ProductAdd apiUrl={handleAddProductUrl} />} />
        </Routes>
        <Footer />
    </div>
  );
}

export default App;
