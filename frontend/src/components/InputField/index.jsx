import React from 'react';

const InputField = ({ label, id, name, type, step, required, pattern }) => (
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
          e.target.setCustomValidity(
            !e.target.validity.valid ? 'Please, provide the data of indicated type' : ''
          )
        }
      />
    </div>
  );

export default InputField;
