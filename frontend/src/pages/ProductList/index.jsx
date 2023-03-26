import React, { useState, useEffect } from 'react';
import { Toast, clearToasts } from '../../components/Toast';
import { ToastContainer } from 'react-toastify';
import { useNavigate } from 'react-router-dom';
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
        // Clear existing toasts before redirecting
        clearToasts();
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
                        Toast({ message: data.message, type: 'success' });
                        // alert(data.message);
                        setProducts(products.filter((product) => !product.checked));
                    } else {
                        Toast({ message: 'An error occurred while deleting the product', type: 'error' });
                    }
                })
                .catch((error) => {
                    console.error('Error deleting products:', error);
                });
        } else {
            Toast({ message: 'No products selected for deletion', type: 'warning', willClose: 1000 });
        }
        clearToasts();
    };

    const toggleProductChecked = (productId) => {
        setProducts(
            products.map((product) =>
                product.id === productId ? { ...product, checked: !product.checked } : product
            )
        );
    };

    const isLoggedIn = () => {
        const jwt = localStorage.getItem("jwt");
        return jwt !== null;
    };

    return (
        <div>
            <ToastContainer />
            <div id="product-list-container">
                <Header
                    title="Product List"
                    buttons={[
                        { id: 'add-product-btn', onClick: handleAddProduct, title: 'Add' },
                        { id: 'delete-product-btn', onClick: handleMassDelete, title: 'Mass Delete' }
                    ]}
                />
                <div id="product-list-items">
                    {products.map((product) => (
                        <Card
                            key={product.id}
                            product={product}
                            toggleProductChecked={toggleProductChecked}
                            isLoggedIn={isLoggedIn()}
                        />
                    ))}
                </div>
            </div>
        </div>
    );
};

export default ProductList;
