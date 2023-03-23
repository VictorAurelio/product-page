import React from 'react';

const Option = ({ value, id, title }) => (
    <option value={value} id={id}> {title} </option>
);

export default Option;
