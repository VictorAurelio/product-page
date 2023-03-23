import React from 'react';

const Card = ({ product, toggleProductChecked }) => {
  const { id, checked, sku, product_name, price, option_name, option_value } = product;

  return (
    <div key={id} className="product-item">
      <div className="product-checkbox">
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
      <div className="product-info">
        <div className="product-sku">{sku}</div>
        <div className="product-name">{product_name}</div>
        <div className="product-price">{price} $</div>
        <div className="product-attribute">
          {option_name}: {option_value}
        </div>
      </div>
    </div>
  );
};

export default Card;