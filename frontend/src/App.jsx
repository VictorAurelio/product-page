import { Route, Routes } from 'react-router-dom';
import ProductList from './pages/ProductList';
import ProductAdd from './pages/ProductAdd';
import UserSignUp from './pages/UserSignUp';
import './App.scss';

function App() {
    const apiUrl = process.env.REACT_APP_API_BASE_URL;
    const handleAddProductUrl = `${apiUrl}/${process.env.REACT_APP_HANDLE_ADD_PRODUCT}`;
    const handleUserSignUpUrl = `${apiUrl}/${process.env.REACT_APP_HANDLE_USER_SIGN_UP}`;
    const showAllProductsUrl = `${apiUrl}/${process.env.REACT_APP_SHOW_ALL_PRODUCTS}`;

  return (
    <div className="App">
        <Routes>
          <Route path="/" element={<ProductList apiUrl={showAllProductsUrl} />} />
          <Route path="/add-product" element={<ProductAdd apiUrl={handleAddProductUrl} />} />
          <Route path="/user/sign-up" element={<UserSignUp apiUrl={handleUserSignUpUrl} />} />
        </Routes>
    </div>

  );
}

export default App;