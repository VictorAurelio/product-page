import React from 'react';
import Button from '../Button';
import './styles.scss';

const Header = ({ title, buttons, formRef, handleValidation }) => {
  return (
    <div id="page-header">
      <h1 id="page-title">{title}</h1>
      <div id="page-buttons">
        {buttons.map((button, index) => (
          <Button
            key={index}
            id={button.id}
            type={button.type}
            onClick={
                button.type === 'submit'
                ? (event) => {
                    if (formRef.current) {
                        handleValidation(event, formRef.current);
                    }
                  }
                : button.onClick
            }
            title={button.title}
          />
        ))}
      </div>
    </div>
  );
};

export default Header;
