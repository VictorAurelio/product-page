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
        fetch('add-product', {
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

        if (!sku || !name || !price || !productType) {
            showNotification('Please, submit required data');
            return;
        }

        switch (productType) {
            case 'Dvd':
                productData.size = event.target.size.value;
                if (!productData.size) {
                    showNotification('Please, provide the data of indicated type');
                    return;
                }
                break;
            case 'Book':
                productData.weight = event.target.weight.value;
                if (!productData.weight) {
                    showNotification('Please, provide the data of indicated type');
                    return;
                }
                break;
            case 'Furniture':
                const height = event.target.height.value;
                const width = event.target.width.value;
                const length = event.target.length.value;

                if (!height || !width || !length) {
                    showNotification('Please, provide the data of indicated type');
                    return;
                }
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

        const response = await fetch('product/handleAddProduct', {
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
            navigate('/');
        } else {
            console.error(response.statusText);
        }
    };

    const handleValidation = (event) => {
        validateForm(event, showNotification, handleSave);
    };

    const handleCancel = () => {
        navigate('/');
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
