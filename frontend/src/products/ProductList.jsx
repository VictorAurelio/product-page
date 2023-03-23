import React, { useState, useEffect } from 'react';
import './ProductList.css';

const ProductList = (props) => {
  const [products, setProducts] = useState([]);

  useEffect(() => {
    fetch('/productpage/backend/public/product/showAllProducts')
      .then((response) => response.json())
      .then((data) => setProducts(data))
      .catch((error) => console.error('Error fetching products:', error));
  }, []);

  const handleAddProduct = () => {
    props.history.push('/productpage/backend/public/add-product');
  };

  const handleMassDelete = () => {
    const checkedProducts = products.filter((product) => product.checked);
    const productIds = checkedProducts.map((product) => product.id);

    if (productIds.length > 0) {
      fetch('/productpage/backend/public/product/massDelete', {
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
    <div>
      <h1>Product List</h1>
      <button onClick={handleAddProduct}>Add</button>
      <button onClick={handleMassDelete}>Mass Delete</button>
      <table>
        <thead>
          <tr>
            <th></th>
            <th>SKU</th>
            <th>Name</th>
            <th>Price</th>
            <th>Attribute</th>
          </tr>
        </thead>
        <tbody>
          {products.map((product) => (
            <tr key={product.id}>
              <td>
                <input
                  type="checkbox"
                  className="delete-checkbox"
                  value={product.id}
                  checked={product.checked}
                  onChange={() => toggleProductChecked(product.id)}
                />
              </td>
              <td>{product.sku}</td>
              <td>{product.product_name}</td>
              <td>${product.price}</td>
              <td>
                {product.option_name}: {product.option_value}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default ProductList;
