export default function Button({id, onClick, title, color}) {
    return (
        <button id={id} style={{"background" : color, }}  onClick={onClick}>{title}</button>
    )
}