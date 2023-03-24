import './styles.scss';

export default function Button({ type, id, onClick, title, className = "button" }) {
    return (
        <button className={className} type={type} id={id} onClick={onClick}>{title}</button>
    )
}