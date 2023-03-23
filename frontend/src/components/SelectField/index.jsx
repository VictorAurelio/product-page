import React from 'react';

const SelectField = ({ label, id, name, value, onChange, children }) => (
  <div>
    <label htmlFor={id}>{label}:</label>
    <select id={id} name={name} value={value} onChange={onChange}>
      {children}
    </select>
  </div>
);

export default SelectField;
