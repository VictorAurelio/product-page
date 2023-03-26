import React from 'react';
import { useNavigate } from "react-router-dom";
import Button from "../Button";
import './styles.scss';

const Card = ({ product, toggleProductChecked, isLoggedIn }) => {
    const navigate = useNavigate();
    const { id, checked, sku, product_name, price, option_name, option_value } = product;

    const handleEditProduct = (productId) => {
        // Redirect do edit product page
        console.log(productId);
        navigate(`/edit-product/${productId}`);
    };

    return (
        <div key={id} className="product-item">
            <div className="product-item-checkbox">
                <input
                    type="checkbox"
                    className="delete-checkbox"
                    value={id}
                    checked={checked}
                    onChange={() => toggleProductChecked(id)}
                    id={`product-${id}`}
                />
                <label htmlFor={`product-${id}`} className="checkbox-label" />
            </div>
            <div className="product-item-info">
                <div className="product-sku">{sku}</div>
                <div className="product-name">{product_name}</div>
                <div className="product-price">{price} $</div>
                <div className="product-attribute">
                    {option_name}: {option_value}
                </div>
            </div>
            {isLoggedIn && (
                <Button
                    className="edit-product-btn"
                    onClick={() => handleEditProduct(id)}
                    title="Edit"
                />
            )}
        </div>
    );
};

export default Card;