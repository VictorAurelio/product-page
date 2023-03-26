import React from 'react';

const InputField = ({ label, id, name, type, step, required, pattern, placeholder }) => (
    <div>
      <label htmlFor={id}>{label}:</label>
      <input
        type={type}
        id={id}
        name={name}
        step={step}
        pattern={pattern}
        data-required={required || null}
        onChange={(e) => e.target.setCustomValidity('')}
        onInvalid={(e) =>
          required && !e.target.validity.valid
            ? e.target.setCustomValidity('Please, provide the data of indicated type')
            : e.target.setCustomValidity('')
        }
        placeholder={placeholder}
      />
    </div>
);

export default InputField;
