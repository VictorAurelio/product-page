import React, { useState, useEffect } from 'react';
import { useHistory } from 'react-router-dom';
import { useNavigate } from 'react-router-dom';

const AddProduct = () => {
  const [productType, setProductType] = useState('');
  const history = useHistory();
  const navigate = useNavigate();

  useEffect(() => {
    // Substitua o fetchForm pelo fetch apropriado aqui
    fetch('http://localhost/productpage/backend/public/add-product', {
      method: 'GET',
    })
      .then((response) => {
        if (response.status === 200) {
          return response.json();
        } else {
          throw new Error(response.statusText);
        }
      })
      .then((result) => {
        if (result.message === 'Form should be displayed on frontend') {
          // Exibir o formulário
          document.querySelector('#product_form').style.display = 'block';
        }
      })
      .catch((error) => {
        console.error(error);
      });
  }, []);

  const handleProductTypeChange = (event) => {
    setProductType(event.target.value);
  };

  const handleSave = async (event) => {
    event.preventDefault();
  
    const sku = event.target.sku.value;
    const name = event.target.name.value;
    const price = event.target.price.value;
    const productType = event.target.productType.value;
    let productData = {};
  
    switch (productType) {
      case 'Dvd':
        productData.size = event.target.size.value;
        break;
      case 'Book':
        productData.weight = event.target.weight.value;
        break;
      case 'Furniture':
        const height = event.target.height.value;
        const width = event.target.width.value;
        const length = event.target.length.value;
        productData.dimensions = `${height}x${width}x${length}`;
        break;
      default:
        break;
    }
  
    const data = {
      sku,
      name,
      price,
      product_type: productType,
      ...productData,
    };
  
    const response = await fetch('http://localhost/productpage/backend/public/product/handleAddProduct', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
    });
  
    if (response.status === 200) {
      const result = await response.json();
      console.log(result.message);
      // Redirecionar para a página da lista de produtos
      navigate('/');
    } else {
      console.error(response.statusText);
    }
  };

  const handleCancel = () => {
    history.push('/productpage/backend/public/product-list');
  };

  const [notification, setNotification] = useState({ show: false, message: '' });
  const showNotification = (message) => {
    setNotification({ show: true, message });
  };

  return (
    <div>
      <h1>Product Form</h1>
      <form id="product_form" onSubmit={handleSave} style={{ display: 'none' }}>
      <div>
          <label htmlFor="sku">SKU:</label>
          <input type="text" id="sku" name="sku" required />
        </div>
        <div>
          <label htmlFor="name">Name:</label>
          <input type="text" id="name" name="name" required />
        </div>
        <div>
          <label htmlFor="price">Price:</label>
          <input type="number" id="price" name="price" step="any" required />
        </div>
        <div>
          <label htmlFor="productType">Product Type:</label>
          <select
            id="productType"
            name="productType"
            value={productType}
            onChange={handleProductTypeChange}
          >
            <option value="">Select a product type</option>
            <option value="Dvd">DVD</option>
            <option value="Book">Book</option>
            <option value="Furniture">Furniture</option>
          </select>
        </div>
        <div id="productTypeSpecific">
          {productType === 'Dvd' && (
            <div>
              <label htmlFor="size">Size (MB):</label>
              <input type="number" id="size" name="size" step="any" required />
              <span>Please, provide size</span>
            </div>
          )}
          {productType === 'Book' && (
            <div>
              <label htmlFor="weight">Weight (Kg):</label>
              <input type="number" id="weight" name="weight" step="any" required />
              <span>Please, provide weight</span>
            </div>
          )}
          {productType === 'Furniture' && (
            <div>
              <label htmlFor="height">Height (cm):</label>
              <input type="number" id="height" name="height" step="any" required />
            </div>
          )}
          {productType === 'Furniture' && (
            <div>
              <label htmlFor="width">Width (cm):</label>
              <input type="number" id="width" name="width" step="any" required />
            </div>
          )}
          {productType === 'Furniture' && (
            <div>
              <label htmlFor="length">Length (cm):</label>
              <input type="number" id="length" name="length" step="any" required />
            </div>
          )}
          {productType === 'Furniture' && <span>Please, provide dimensions (HxWxL)</span>}
        </div>
        <div>
          <button type="submit" id="saveButton">
            Save
          </button>
          <button type="button" id="cancelButton" onClick={handleCancel}>
            Cancel
          </button>
        </div>
      </form>
    </div>
  );
};

export default AddProduct;
