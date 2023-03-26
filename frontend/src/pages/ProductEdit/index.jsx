import React, { useState, useEffect, useRef } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import InputField from '../../components/InputField';
import Header from '../../components/Header';
import validateForm from '../../utils/validateForm';
import { Toast } from '../../components/Toast';
import { ToastContainer } from 'react-toastify';
import ProductTypeSpecific from '../../components/ProductTypeSpecific';
import './styles.scss';

const EditProduct = () => {
    const { id: productId } = useParams();
    const navigate = useNavigate();
    const formRef = useRef(null);
    const [product, setProduct] = useState({});

    useEffect(() => {
        const fetchProduct = async () => {
            try {
                const response = await fetch(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_EDIT_PRODUCT}${productId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });
                if (response.status === 200) {
                    const data = await response.json();
                    setProduct(data);
                    const optionName = data.option_name.charAt(0).toUpperCase() + data.option_name.slice(1);
                    // Update the product state to include the capitalized option name
                    setProduct(prevState => ({...prevState, option_name: optionName}));
                } else {
                    throw new Error(response.statusText);
                }
            } catch (error) {
                console.error(error);
            }
        };       

        fetchProduct();
    }, [productId]);

    const handleSave = async (event) => {
        event.preventDefault();

        const updatedProduct = {
            id: productId,
            sku: formRef.current.sku.value,
            product_name: formRef.current.product_name.value,
            price: formRef.current.price.value,
        };

        if (product.productType === 'Dvd') {
            updatedProduct.size = formRef.current.size.value;
        } else if (product.productType === 'Book') {
            updatedProduct.weight = formRef.current.weight.value;
        } else if (product.productType === 'Furniture') {
            updatedProduct.height = formRef.current.height.value;
            updatedProduct.width = formRef.current.width.value;
            updatedProduct.length = formRef.current.length.value;
        }

        const response = await fetch(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_EDIT_PRODUCT}/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ _method: 'PUT', ...updatedProduct }),
        });

        if (response.status === 201) {
            const result = await response.json();
            console.log(result.message);
            navigate(`${process.env.REACT_APP_BASE_URL}`);
        } else {
            //   const errorResult = await response.json();
            console.error(response.statusText);
            Toast({ message: 'An error occurred while updating the product', type: 'error' });
        }
    };

    const handleValidation = (event, form) => { // try catch
        const newEvent = { ...event, target: form };
        validateForm(event, form, handleSave.bind(null, newEvent));
    };

    const handleCancel = () => {
        navigate(`${process.env.REACT_APP_BASE_URL}`);
    };
    return (
        <div>
            <ToastContainer />
            <Header
                title="Product Edit"
                formRef={formRef}
                handleValidation={handleValidation}
                buttons={[
                    { id: 'saveButton', type: 'submit', title: 'Save' },
                    { id: 'cancel-edit-btn', type: 'button', onClick: handleCancel, title: 'Cancel' }
                ]}
            />
            <div  className="product-edit-form">
                <form id="edit-product-form" ref={formRef} onSubmit={handleSave}>
                    <InputField
                        label="SKU"
                        id="sku"
                        name="sku"
                        type="text"
                        placeholder={product.sku}
                        required
                    />
                    <InputField
                        label="Product Name"
                        id="product_name"
                        name="product_name"
                        type="text"
                        placeholder={product.product_name}
                        required
                    />
                    <InputField
                        label="Price"
                        id="price"
                        name="price"
                        type="number"
                        step="any"
                        pattern="^\d+(\.\d{1,2})?$"
                        placeholder={product.price}
                        required
                    />
                    <InputField
                        label="Option Value"
                        id="option_value"
                        name="option_value"
                        type="text"
                        placeholder={product.option_value}
                        required
                    /> {product.option_name && <div>{product.option_name}</div>}
                    <ProductTypeSpecific productType={product.productType} />
                </form>
            </div>
        </div>
    );
};

export default EditProduct;
