import useNotifications from '../../hooks/useNotifications';
import React, { useState, useEffect } from 'react';
import { ToastContainer } from 'react-toastify';
import { useNavigate } from 'react-router-dom';
import 'react-toastify/dist/ReactToastify.css';
// import Button from '../../components/Button';
import Header from '../../components/Header';
import Card from '../../components/Card';
import './styles.scss';

const ProductList = () => {
    const navigate = useNavigate();
    const [products, setProducts] = useState([]);

    useEffect(() => {
        fetch(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_SHOW_ALL_PRODUCTS}`)
        .then((response) => response.json())
        .then((data) => setProducts(data))
        .catch((error) => console.error('Error fetching products:', error));
    }, []);

    const handleAddProduct = () => {
        navigate(`${process.env.REACT_APP_ADD_PRODUCT}`);
    };

    const handleMassDelete = () => {
        const checkedProducts = products.filter((product) => product.checked);
        const productIds = checkedProducts.map((product) => product.id);

        if (productIds.length > 0) {
        fetch(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_MASS_DELETE}`, {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json',
            },
            body: JSON.stringify({ _method: 'DELETE', product_ids: productIds }),
        })
            .then((response) => response.json())
            .then((data) => {
            if (data.status === 201) {
                showNotification(data.message, 'success');
                // alert(data.message);
                setProducts(products.filter((product) => !product.checked));
            } else {
                showNotification('Error deleting products', 'error', null);
            }
            })
            .catch((error) => {
            console.error('Error deleting products:', error);
            });
        } else {
            showNotification('No products selected for deletion', 'warning', null, 1500);
        }
};

  const toggleProductChecked = (productId) => {
    setProducts(
      products.map((product) =>
        product.id === productId ? { ...product, checked: !product.checked } : product
      )
    );
  };

  const { showNotification } = useNotifications();
  
  return (
    <div>
    <ToastContainer />
        <div id="product-list-container">
            <Header 
                title="Product List"
                buttons={[
                    { id: 'add-product-btn', onClick: handleAddProduct, title: 'Add'},
                    { id: 'delete-product-btn', onClick: handleMassDelete, title: 'Mass Delete'}
                ]}
            />
            <div id="product-list-items">
                {products.map((product) => (
                    <Card key={product.id} product={product} toggleProductChecked={toggleProductChecked} />
                    ))}
            </div>
        </div>
    </div>
  );
};

export default ProductList;
