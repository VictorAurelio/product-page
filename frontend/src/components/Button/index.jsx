export default function Button({ type, id, onClick, title }) {
    return (
        <button type={type} id={id} onClick={onClick}>{title}</button>
    )
}