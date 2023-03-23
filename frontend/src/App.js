import { Route, Routes } from 'react-router-dom';
import './App.css';
import ProductList from './pages/ProductList';
import ProductAdd from './pages/ProductAdd';
import Footer from './components/Footer';

function App() {
  return (
    <div className="App">
        <Routes>
          <Route path="/" element={<ProductList />} />
          <Route path="/add-product" element={<ProductAdd />} />
        </Routes>
        <Footer />
    </div>
  );
}

export default App;
