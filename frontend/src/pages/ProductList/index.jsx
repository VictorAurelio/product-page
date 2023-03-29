import React, { useState, useEffect, useCallback } from 'react';
import { Toast, clearToasts } from '../../components/Toast';
import { ToastContainer } from 'react-toastify';
import { useNavigate } from 'react-router-dom';
import Header from '../../components/Header';
import Card from '../../components/Card';
import './styles.scss';

const ProductList = () => {
    const navigate = useNavigate();
    const [products, setProducts] = useState([]);
    const [filteredProducts, setFilteredProducts] = useState([]);

    const fetchProducts = useCallback(async () => {
        try {
            const response = await fetch(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_SHOW_ALL_PRODUCTS}`);
            const data = await response.json();
            setProducts(data);
        } catch (error) {
            console.error('Error fetching products:', error);
        }
    }, []);

    useEffect(() => {
        fetchProducts();
    }, [fetchProducts]);

    const handleAddProduct = () => {
        // Clear existing toasts before redirecting
        clearToasts();
        navigate(`${process.env.REACT_APP_ADD_PRODUCT}`);
    };

    const handleMassDelete = async () => {
        const checkedProducts = products.filter((product) => product.checked);
        const productIds = checkedProducts.map((product) => product.id);

        if (productIds.length > 0) {
            try {
                const response = await fetch(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_MASS_DELETE}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ _method: 'DELETE', product_ids: productIds }),
                });

                const data = await response.json();

                if (data.status === 201) {
                    Toast({ message: data.message, type: 'success' });
                    setProducts(products.filter((product) => !product.checked));
                } else {
                    Toast({ message: 'An error occurred while deleting the product', type: 'error' });
                }
            } catch (error) {
                console.error('Error deleting products:', error);
            }
        } else {
            Toast({ message: 'No products selected for deletion', type: 'warning', willClose: 1000 });
        }
    };

    const toggleProductChecked = (productId) => {
        setProducts(
            products.map((product) =>
                product.id === productId ? { ...product, checked: !product.checked } : product
            )
        );
    };

    const handleSearch = (searchTerm) => {
        const filtered = products.filter((product) =>
            product.product_name.toLowerCase().includes(searchTerm.toLowerCase())
        );
        setFilteredProducts(filtered);
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
                        { id: 'add-product-btn', onClick: handleAddProduct, title: 'ADD' },
                        { id: 'delete-product-btn', onClick: handleMassDelete, title: 'MASS DELETE' }
                    ]}
                    showSearchBar={true}
                    onSearch={handleSearch}
                />
                <div id="product-list-items">
                    {filteredProducts.length > 0 ? (
                        filteredProducts.map((product) => (
                            <Card
                                key={product.id}
                                product={product}
                                toggleProductChecked={toggleProductChecked}
                                isLoggedIn={isLoggedIn()}
                            />
                        ))
                    ) : (
                        products.map((product) => (
                            <Card
                                key={product.id}
                                product={product}
                                toggleProductChecked={toggleProductChecked}
                                isLoggedIn={isLoggedIn()}
                            />
                        ))
                    )}
                </div>
            </div>
        </div>
    );
};

export default ProductList;
