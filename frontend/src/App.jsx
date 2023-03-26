import { Route, Routes } from 'react-router-dom';
import ProductList from './pages/ProductList';
import ProductAdd from './pages/ProductAdd';
import ProductEdit from './pages/ProductEdit';
import UserAuth from './pages/UserAuth';
import './App.scss';

function App() {
    const apiUrl = process.env.REACT_APP_API_BASE_URL;
    const handleAddProductUrl = `${apiUrl}/${process.env.REACT_APP_HANDLE_ADD_PRODUCT}`;
    const showAllProductsUrl = `${apiUrl}/${process.env.REACT_APP_SHOW_ALL_PRODUCTS}`;
    const editProductUrl = `${apiUrl}${process.env.REACT_APP_EDIT_PRODUCT}`;

    // const handleUserSignUpUrl = `${apiUrl}/${process.env.REACT_APP_HANDLE_USER_SIGN_UP}`;

  return (
    <div className="App">
        <Routes>
          <Route path="/" element={<ProductList apiUrl={showAllProductsUrl} />} />
          <Route path="/add-product" element={<ProductAdd apiUrl={handleAddProductUrl} />} />
          <Route path="/edit-product/:id" element={<ProductEdit />} />
          <Route path="/user" element={<UserAuth />} />
        </Routes>
    </div>

  );
}

export default App;