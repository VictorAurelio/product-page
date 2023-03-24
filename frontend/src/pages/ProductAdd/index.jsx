import ProductTypeSpecific from '../../components/ProductTypeSpecific';
import useNotifications from '../../hooks/useNotifications';
import React, { useState, useEffect, useRef } from 'react';
import SelectField from '../../components/SelectField';
import InputField from '../../components/InputField';
import validateForm from '../../utils/validateForm';
import { ToastContainer } from 'react-toastify';
import { useNavigate } from 'react-router-dom';
import 'react-toastify/dist/ReactToastify.css';
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

    const handleValidation = (event, form) => {
        const newEvent = { ...event, target: form };
        validateForm(event, form, showNotification, handleSave.bind(null, newEvent));
    };

    const handleCancel = () => {
        navigate(`${process.env.REACT_APP_BASE_URL}`);
    };

    const { showNotification } = useNotifications();

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
