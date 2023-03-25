import React from 'react';
import Button from '../Button';
import './styles.scss';
import AuthButton from '../AuthButton';

const Header = ({ title, buttons, formRef, handleValidation, onSubmit }) => {
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
                      event.preventDefault();
                      if (formRef.current) {
                        onSubmit ? onSubmit(event) : handleValidation(event);
                      }
                    }
                  : button.onClick
              }
              title={button.title}
            />
          ))}
          <AuthButton />
        </div>
      </div>
    );
  };
  
  export default Header;
  
