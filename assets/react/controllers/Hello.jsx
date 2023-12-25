import React, {useRef, Component, forwardRef} from 'react';

const Field = forwardRef(({name, type, helpText, children}, ref) => {
    const onClick = (e) => {
        e.preventDefault();
        const {current} = ref
        current.value = helpText
    }

    return <div>
        <label htmlFor={name}>{children}</label>
        <input type={type} name={name} id={name} ref={ref}/>
        <div className="form-text">
            Try: <a href="#" onClick={onClick}>{helpText}</a>
        </div>
    </div>
})

Field.displayName = 'Field'

function Form() {
    const emailRef = useRef(null)
    const passwordRef = useRef(null)

    const handleSubmit = (e) => {
        e.preventDefault();
        console.log(emailRef.current.value, passwordRef.current.value)
    }

    return <form onSubmit={handleSubmit}>
        <Field ref={emailRef} name={"email"} type={'text'} helpText={'johndoe@contact.com'}>Email</Field>
        <Field ref={passwordRef} name={"password"} type={'password'} helpText={'password'}>Mot de passe</Field>

        <button type="submit">Connexion</button>
    </form>;
}

export default Form
