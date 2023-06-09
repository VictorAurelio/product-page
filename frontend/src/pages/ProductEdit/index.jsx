import React, { useState, useEffect, useRef } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import InputField from '../../components/InputField';
import Header from '../../components/Header';
import validateUpdateForm from '../../utils/validateUpdateForm';
import { Toast, clearToasts } from '../../components/Toast';
import { ToastContainer } from 'react-toastify';
import ProductTypeSpecific from '../../components/ProductTypeSpecific';
import './styles.scss';

const EditProduct = () => {
    const { id: productId } = useParams();
    const navigate = useNavigate();
    const formRef = useRef(null);
    const [product, setProduct] = useState({});
    const [categoryId, setCategoryId] = useState('');
    const [weight, setWeight] = useState('');
    const [size, setSize] = useState('');

    useEffect(() => {
        const fetchProduct = async () => {
            try {
                const response = await fetch(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_EDIT_PRODUCT}${productId}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
                    },
                });
                if (response.status === 200) {
                    const data = await response.json();
                    setProduct(data);
                    setCategoryId(data.category_id);
                    const optionName = data.option_name.charAt(0).toUpperCase() + data.option_name.slice(1);
                    setProduct(prevState => {
                        const updatedProduct = {
                            ...prevState,
                            ...data,
                            option_name: optionName,
                            productType: data.category_id === 3 ? 'Furniture' : data.category_id === 1 ? 'Book' : 'Dvd',
                        };
                        if (data.category_id === 3) { // Compare with the value corresponding to 'Furniture'
                            extractDimensions(data.option_value);
                        } else if (data.category_id === 1) {
                            setWeight(data.option_value);

                        } else if (data.category_id === 2) {
                            setSize(data.option_value);
                        }
                        return updatedProduct;
                    });
                } else if (response.status === 401) {
                    // Redirect the user to sign-in/sign-up page if it's token isn't authenticated
                    clearToasts();
                    navigate('/user');
                } else if (response.status === 404) {
                    // Redirect the user to 404 if trying to access/edit a product that doesn't exist
                    clearToasts();
                    navigate('*');
                }
                else {
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
            sku: formRef.current.sku.value !== "" ? formRef.current.sku.value : product.sku,
            name: formRef.current.product_name.value !== "" ? formRef.current.product_name.value : product.product_name,
            price: formRef.current.price.value !== "" ? formRef.current.price.value : product.price,
            category_id: categoryId,
            product_type: product.productType,
        };

        if (product.productType === 'Dvd') {
            const newSize = formRef.current.size.value;
            updatedProduct.size = newSize !== "" ? newSize : size;
        } else if (product.productType === 'Book') {
            const newWeight = formRef.current.weight.value;
            updatedProduct.weight = newWeight !== "" ? newWeight : weight;
        } else if (product.productType === 'Furniture') {
            const height = formRef.current.height.value !== "" ? formRef.current.height.value : product.height;
            const width = formRef.current.width.value !== "" ? formRef.current.width.value : product.width;
            const length = formRef.current.length.value !== "" ? formRef.current.length.value : product.length;

            if (isNumeric(height) && isNumeric(width) && isNumeric(length)) {
                if (isValidDimensionsFormat(height, width, length)) {
                    updatedProduct.dimensions = `${parseFloat(height)}x${parseFloat(width)}x${parseFloat(length)}`;
                } else {
                    Toast({ message: 'Dimensions format is incorrect.', type: 'error' });
                    return;
                }
            } else {
                Toast({ message: 'Dimensions must be numeric.', type: 'error' });
                return;
            }
        }

        const response = await fetch(`${process.env.REACT_APP_API_BASE_URL}${process.env.REACT_APP_EDIT_PRODUCT}${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('jwt')}`,
            },
            body: JSON.stringify({ _method: 'PUT', ...updatedProduct }),
        });

        if (response.status === 201) {
            const result = await response.json();
            console.log(result.message);
            // Clear existing toasts before redirecting
            clearToasts();
            navigate(`${process.env.REACT_APP_BASE_URL}`);
        } else {
            const errorResult = await response.json();
            console.error(response.statusText);
            console.log(errorResult);

            let errorMessage = '';
            const keys = Object.keys(errorResult);

            for (let i = 0; i < keys.length; i++) {
                const key = keys[i];
                const errors = JSON.parse(errorResult[key][0]);
                if (errors && errors.message) {
                    errorMessage = errors.message;
                    break;
                }
            }
            console.log(errorMessage);
            // Display the error notification
            Toast({ message: errorMessage, type: 'error' });
        }
    };

    const extractDimensions = (dimensions) => {
        const [height, width, length] = dimensions.split('x');
        setProduct((prevState) => ({
            ...prevState,
            height: parseFloat(height),
            width: parseFloat(width),
            length: parseFloat(length),
        }));
    };

    const isNumeric = (value) => {
        return !isNaN(parseFloat(value)) && isFinite(value);
    };

    const hasOnlyNumbers = (value) => {
        const regex = /^\d+(\.\d{1,2})?$/;
        return regex.test(value);
    };
    
    const isValidDimensionsFormat = (height, width, length) => {
        const regex = /^\d+(\.\d+)?x\d+(\.\d+)?x\d+(\.\d+)?$/;
        const dimensionsString = `${height}x${width}x${length}`;
        return regex.test(dimensionsString);
    };

    const handleValidation = (event) => {
        try {
            validateUpdateForm(event, formRef.current, handleSave);
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
                title="Product Edit"
                formRef={formRef}
                handleValidation={handleValidation}
                buttons={[
                    { id: 'saveButton', type: 'submit', title: 'Save' },
                    { id: 'cancel-edit-btn', type: 'button', onClick: handleCancel, title: 'Cancel' }
                ]}
            />
            <div className="product-edit-form">
                <form id="edit-product-form" ref={formRef} onSubmit={handleValidation}>
                    <InputField
                        label="SKU"
                        id="sku"
                        name="sku"
                        type="text"
                        defaultValue={product.sku}
                        placeholder={product.sku}
                    />
                    <InputField
                        label="Product Name"
                        id="product_name"
                        name="product_name"
                        type="text"
                        defaultValue={product.product_name}
                        placeholder={product.product_name}
                    />
                    <InputField
                        label="Price"
                        id="price"
                        name="price"
                        type="number"
                        step="any"
                        pattern="^\d+(\.\d{1,2})?$"
                        defaultValue={product.price}
                        placeholder={product.price}
                    />
                    <ProductTypeSpecific
                        productType={product.productType}
                        height={product.height}
                        width={product.width}
                        length={product.length}
                        weight={weight}
                        size={size}
                    />
                </form>
            </div>
        </div>
    );
};

export default EditProduct;
