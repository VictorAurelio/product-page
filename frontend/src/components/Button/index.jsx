import './styles.scss';

export default function Button({
    type,
    id,
    onClick,
    title,
    className = 'button',
    formRef,
    onSubmit,
    handleValidation,
}) {
    const handleClick = (event) => {
        if (type === 'submit' && formRef && (onSubmit || handleValidation)) {
            event.preventDefault();
            onSubmit ? onSubmit(event) : handleValidation(event);
        } else if (onClick) {
            onClick(event);
        }
    };

    return (
        <button className={className} type={type} id={id} onClick={handleClick}>
            {title}
        </button>
    );
}