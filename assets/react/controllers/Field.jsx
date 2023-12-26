import React, {forwardRef} from "react";

const Field = forwardRef(({name, type, helpText, children}, ref) => {
    const onClick = (e) => {
        e.preventDefault();
        const {current} = ref
        current.value = helpText
    }

    return <div className="mb-4">
        <label htmlFor={name} className="form-label">{children}</label>
        <input type={type} name={name} id={name} ref={ref} className="form-control"/>
        <div className="form-text">
            Try: <span onClick={onClick} className="form-help">{helpText}</span>
        </div>
    </div>
})
Field.displayName = 'Field'
export default Field