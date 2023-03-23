import React from 'react';
import InputField from '../InputField';


const ProductTypeSpecific = ({ productType }) => {
    if (productType === 'Dvd') {
      return (
        <div>
          <InputField
            label="Size (MB)"
            id="size"
            name="size"
            step="any"
            pattern="^\d+(\.\d{1,2})?$"
            required
          />
          <span>Please, provide size</span>
        </div>
      );
    }
    if (productType === 'Book') {
      return (
        <div>
          <InputField
            label="Weight (Kg)"
            id="weight"
            name="weight"
            type="number"
            step="any"
            pattern="^\d+(\.\d{1,2})?$"
            required
          />
          <span>Please, provide weight</span>
        </div>
      );
    }
    if (productType === 'Furniture') {
      return (
        <div>
          <InputField
            label="Height (cm)"
            id="height"
            name="height"
            step="any"
            pattern="^\d+(\.\d{1,2})?$"
            required
          />
          <InputField
            label="Width (cm)"
            id="width"
            name="width"
            step="any"
            pattern="^\d+(\.\d{1,2})?$"
            required
          />
          <InputField
            label="Length (cm)"
            id="length"
            name="length"
            step="any"
            pattern="^\d+(\.\d{1,2})?$"
            required
          />
          <span>Please, provide dimensions (HxWxL)</span>
        </div>
      );
    }
    return null;
  };
  
  export default ProductTypeSpecific;
