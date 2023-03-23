import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import './styles.css';
import Button from '../../components/Button';
import Card from '../../components/Card';

const ProductList = () => {
    const navigate = useNavigate();
    const [products, setProducts] = useState([]);

    useEffect(() => {
        fetch('product/showAllProducts')
        .then((response) => response.json())
        .then((data) => setProducts(data))
        .catch((error) => console.error('Error fetching products:', error));
    }, []);

    const handleAddProduct = () => {
        navigate('/add-product');
    };

    const handleMassDelete = () => {
        const checkedProducts = products.filter((product) => product.checked);
        const productIds = checkedProducts.map((product) => product.id);

        if (productIds.length > 0) {
        fetch('product/massDelete', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json',
            },
            body: JSON.stringify({ _method: 'DELETE', product_ids: productIds }),
        })
            .then((response) => response.json())
            .then((data) => {
            if (data.status === 201) {
                alert(data.message);
                setProducts(products.filter((product) => !product.checked));
            } else {
                alert('Error deleting products.');
            }
            })
            .catch((error) => {
            console.error('Error deleting products:', error);
            });
        } else {
        alert('No products selected for deletion.');
        }
};

  const toggleProductChecked = (productId) => {
    setProducts(
      products.map((product) =>
        product.id === productId ? { ...product, checked: !product.checked } : product
      )
    );
  };

  return (
    <div id="product-list-container">
      <div id="product-list-header">
        <h1 id="product-list-title">Product List</h1>
        <div id="product-list-buttons">
          <Button id="add-product-btn" onClick={handleAddProduct} title="Add" />
          <Button id="delete-product-btn" onClick={handleMassDelete} title="Mass Delete" />
        </div>
      </div>
      <div id="product-list-items">
        {products.map((product) => (
          <Card key={product.id} product={product} toggleProductChecked={toggleProductChecked} />
        ))}
      </div>
    </div>
  ); 
};

export default ProductList;
