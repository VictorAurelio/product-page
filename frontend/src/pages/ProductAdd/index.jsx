import ProductTypeSpecific from '../../components/ProductTypeSpecific';
import React, { useState, useEffect, useRef } from 'react';
import SelectField from '../../components/SelectField';
import InputField from '../../components/InputField';
import validateForm from '../../utils/validateForm';
import { Toast, clearToasts } from '../../components/Toast';
import { ToastContainer } from 'react-toastify';
import { useNavigate } from 'react-router-dom';
import Option from '../../components/Option';
import Header from '../../components/Header';
import './styles.scss';

const AddProduct = () => {
    const navigate = useNavigate();
    const formRef = useRef(null);
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
        const sku = formRef.current.sku.value;
        const name = formRef.current.name.value;
        const price = formRef.current.price.value;
        const productType = formRef.current.productType.value;
        let productData = {};

        switch (productType) {
            case 'Dvd':
                if (isNumeric(formRef.current.size.value)) {
                    productData.size = parseFloat(formRef.current.size.value);
                } else {
                    Toast({ message: 'Size must be numeric.', type: 'error' });
                }
                break;
            case 'Book':
                if (isNumeric(formRef.current.weight.value)) {
                    productData.weight = parseFloat(formRef.current.weight.value);
                } else {
                    Toast({ message: 'Weight must be numeric.', type: 'error' });
                }
                break;
            case 'Furniture':
                const height = formRef.current.height.value;
                const width = formRef.current.width.value;
                const length = formRef.current.length.value;

                if (isNumeric(height) && isNumeric(width) && isNumeric(length)) {
                    if (isValidDimensionsFormat(height, width, length)) {
                        productData.dimensions = `${parseFloat(height)}x${parseFloat(width)}x${parseFloat(length)}`;
                    } else {
                        Toast({ message: 'Dimensions format is incorrect.', type: 'error' });
                        return; // Prevent the form submission
                    }
                } else {
                    Toast({ message: 'Dimensions must be numeric.', type: 'error' });
                    return; // Prevent the form submission
                }
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

            // Clear existing toasts before redirecting
            clearToasts();
            
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
                Toast({ message: `An error occurred: ${capitalized}`, type: 'error' });

            } else {
                // Display a generic error notification
                Toast({ message: 'An error occurred while adding the product', type: 'error' });
            }
        }
    };

    const isNumeric = (value) => {
        return !isNaN(parseFloat(value)) && isFinite(value);
    };

    const isValidDimensionsFormat = (height, width, length) => {
        const regex = /^\d+(\.\d+)?x\d+(\.\d+)?x\d+(\.\d+)?$/;
        const dimensionsString = `${height}x${width}x${length}`;
        return regex.test(dimensionsString);
    };

    const handleValidation = (event) => {
        try {
            validateForm(event, formRef.current, handleSave);
        } catch (error) {
            // Handle any error that might be thrown in the validateForm function
            console.error(error);
            Toast({ message: 'An error occurred during validation', type: 'error' });
        }
    };

    const handleCancel = () => {
        navigate(`${process.env.REACT_APP_BASE_URL}`);
    };

    return (
        <div>
            <ToastContainer />
            <Header
                title="Product Add"
                formRef={formRef}
                handleValidation={handleValidation}
                buttons={[
                    { id: 'saveButton', type: 'submit', title: 'Save' },
                    { id: 'delete-product-btn', type: 'button', onClick: handleCancel, title: 'Cancel' }
                ]}
            />
            <div className="container">
                <form id="product_form" ref={formRef} onSubmit={handleValidation}>
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
                        min="0.01"
                    />
                    <SelectField
                        label="Product Type"
                        id="productType"
                        name="productType"
                        value={productType}
                        onChange={handleProductTypeChange}
                        required
                    >
                        <Option value="" title="Select a product type" />
                        <Option id="DVD" value="Dvd" title="DVD" />
                        <Option id="Book" value="Book" title="Book" />
                        <Option id="Furniture" value="Furniture" title="Furniture" />
                    </SelectField>
                    <ProductTypeSpecific productType={productType} />
                </form>
            </div>
        </div>
    );
};

export default AddProduct;
