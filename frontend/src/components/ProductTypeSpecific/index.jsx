import React, { useState, useEffect } from 'react';
import InputField from '../InputField';

const ProductTypeSpecific = ({ productType, height, width, length  }) => {
    const [isOpen, setIsOpen] = useState(false);

    useEffect(() => {
        setIsOpen(!!productType);
    }, [productType]);

    const className = `product-type-specific${isOpen ? ' open' : ''}`;

    return (
        <div className={className}>
            {productType === 'Dvd' && (
                <div>
                    <InputField
                        label="Size (MB)"
                        id="size"
                        name="size"
                        step="any"
                        type="number"
                        pattern="^\\d+(\\.\\d{1,2})?$"
                        required
                        min="0.01"
                    />
                    <span>Please, provide size</span>
                </div>
            )}
            {productType === 'Book' && (
                <div>
                    <InputField
                        label="Weight (Kg)"
                        id="weight"
                        name="weight"
                        type="number"
                        step="any"
                        pattern="^\\d+(\\.\\d{1,2})?$"
                        required
                        min="0.01"
                    />
                    <span>Please, provide weight</span>
                </div>
            )}
            {productType === 'Furniture' && (
                <div>
                    <InputField
                        label="Height (cm)"
                        id="height"
                        name="height"
                        step="any"
                        type="number"
                        pattern="^\\d+(\\.\\d{1,2})?$"
                        required
                        min="0.01"
                        defaultValue={height}
                    />
                    <InputField
                        label="Width (cm)"
                        id="width"
                        name="width"
                        step="any"
                        type="number"
                        pattern="^\\d+(\\.\\d{1,2})?$"
                        required
                        min="0.01"
                        defaultValue={width}
                    />
                    <InputField
                        label="Length (cm)"
                        id="length"
                        name="length"
                        step="any"
                        type="number"
                        pattern="^\\d+(\\.\\d{1,2})?$"
                        required
                        min="0.01"
                        defaultValue={length}
                    />
                    <span>Please, provide dimensions</span>
                </div>
            )}
        </div>
    );
};

export default ProductTypeSpecific;