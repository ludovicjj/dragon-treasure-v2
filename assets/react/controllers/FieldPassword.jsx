import React, {forwardRef} from "react";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faEye, faEyeSlash } from "@fortawesome/free-solid-svg-icons";

library.add(faEye, faEyeSlash);

const FieldPassword = forwardRef(({name, type, helpText, children, handlePasswordVisibility}, ref) => {
    const onClick = (e) => {
        e.preventDefault();
        const {current} = ref
        current.value = helpText
    }

    const iconType = type === 'password' ? 'fa-eye' : 'fa-eye-slash'

    return <div className="mb-4">
        <label htmlFor={name} className="form-label">{children}</label>
        <div className="group-flex">
            <input type={type} name={name} id={name} ref={ref} className="form-control"/>
            <button type="button" onClick={handlePasswordVisibility} className="btn btn-primary">
                <FontAwesomeIcon icon={iconType} />
            </button>
        </div>
        <div className="form-text">
            Try: <span onClick={onClick} className="form-help">{helpText}</span>
        </div>
    </div>
})
FieldPassword.displayName = 'FieldPassword'
export default FieldPassword