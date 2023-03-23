export default function Button({ type, id, onClick, title, color }) {
    return (
        <button type={type} id={id} style={{ "background": color, }} onClick={onClick}>{title}</button>
    )
}