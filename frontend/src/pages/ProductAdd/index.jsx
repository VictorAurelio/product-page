import ProductTypeSpecific from '../../components/ProductTypeSpecific';
import SelectField from '../../components/SelectField';
import useNotifications from '../../hooks/useNotifications';
import { ToastContainer } from 'react-toastify';
import InputField from '../../components/InputField';
import React, { useState, useEffect } from 'react';
import validateForm from '../../utils/validateForm';
import 'react-toastify/dist/ReactToastify.css';
import { useNavigate } from 'react-router-dom';
import Button from '../../components/Button';
import Option from '../../components/Option';
import './styles.css';

const AddProduct = () => {
    const navigate = useNavigate();
    const [productType, setProductType] = useState('');

    useEffect(() => {
        fetch(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_ADD_PRODUCT}`, {
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
                    // Show form
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
        // event.preventDefault();
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

        const response = await fetch(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_HANDLE_ADD_PRODUCT}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (response.status === 201) {
            const result = await response.json();
            console.log(result.message);
            // Redirect to products list
            navigate(`${process.env.REACT_APP_BASE_URL}`);
        } else {
            // In case of error, get the error message from the backend
            const errorResult = await response.json();
            console.error(response.statusText);

            // Check if the backend returned that the sku already exists
            if (errorResult.sku && errorResult.sku.length > 0) {
                const errorMessage = JSON.parse(errorResult.sku[0]).message;
                const capitalized = errorMessage[0].toUpperCase() + errorMessage.substr(1);
                // Display the notification if the SKU already exists in the database
                showNotification(`An error occurred: ${capitalized}`);
            } else {
                // Display a generic error notification
                showNotification('An error occurred while adding the product');
            }
        }
    };

    const handleValidation = (event) => {
        validateForm(event, showNotification, handleSave);
    };

    const handleCancel = () => {
        navigate(`${process.env.REACT_APP_BASE_URL}`);
    };

    // show notification "Please, submit required data" and "Please, provide the data of indicated type"
    const { showNotification } = useNotifications();

    return (
        <div>
            <ToastContainer />
            <h1>Product Form</h1>
            <form id="product_form" onSubmit={handleValidation}>
                <InputField
                    label="SKU"
                    id="sku"
                    name="sku"
                    type="text"
                    required
                    pattern="[a-zA-Z0-9]+[-_a-zA-Z0-9]*"
                />
                <InputField label="Name" id="name" name="name" type="text" required />
                <InputField
                    label="Price"
                    id="price"
                    name="price"
                    type="number"
                    step="any"
                    required
                />
                <SelectField
                    label="Product Type"
                    id="productType"
                    name="productType"
                    value={productType}
                    onChange={handleProductTypeChange}
                    required
                >
                <Option value="" title="Select a product type"/>
                <Option id="DVD" value="Dvd" title="DVD"/>
                <Option id="Book" value="Book" title="Book"/>
                <Option id="Furniture" value="Furniture" title="Furniture"/>
                </SelectField>
                <ProductTypeSpecific productType={productType} />
                <div>
                    <Button type="submit" id="saveButton" title="Save" />
                    <Button type="button" id="cancelButton" title="Cancel" onClick={handleCancel} />
                </div>
            </form>
        </div>
    );
};

export default AddProduct;
