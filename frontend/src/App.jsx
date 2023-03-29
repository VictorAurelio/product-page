import { Route, Routes } from 'react-router-dom';
import ProductList from './pages/ProductList';
import ProductAdd from './pages/ProductAdd';
import ProductEdit from './pages/ProductEdit';
import UserAuth from './pages/UserAuth';
import NotFound from './components/NotFound';
import './App.scss';

function App() {
    const apiUrl = process.env.REACT_APP_API_BASE_URL;
    const handleAddProductUrl = `${apiUrl}/${process.env.REACT_APP_HANDLE_ADD_PRODUCT}`;
    const showAllProductsUrl = `${apiUrl}/${process.env.REACT_APP_SHOW_ALL_PRODUCTS}`;
    const editProductUrl = `${apiUrl}${process.env.REACT_APP_EDIT_PRODUCT}`;
    
  return (
    <div className="App">
        <Routes>
          <Route path="/" element={<ProductList apiUrl={showAllProductsUrl} />} />
          <Route path="/add-product" element={<ProductAdd apiUrl={handleAddProductUrl} />} />
          <Route path="/edit-product/:id" element={<ProductEdit apiUrl={editProductUrl} />} />
          <Route path="/user" element={<UserAuth />} />
          <Route path="*" element={<NotFound />} />
        </Routes>
    </div>

  );
}

export default App;