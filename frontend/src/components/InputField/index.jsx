import React from 'react';

const InputField = ({ label, id, name, type, step, required, pattern }) => (
    <div>
      <label htmlFor={id}>{label}:</label>
      <input
        type={type}
        id={id}
        name={name}
        step={step}
        required={required}
        pattern={pattern}
        // onInvalid={(e) => e.target.setCustomValidity('Please insert a valid value.')}
        onChange={(e) => e.target.setCustomValidity('')}
      />
    </div>
  );

export default InputField;
