import React, {useRef, useState} from 'react';
import Field from "./Field";
import FieldPassword from "./FieldPassword";

function LoginForm(props) {
    const emailRef = useRef(null)
    const passwordRef = useRef(null)

    const [state, setState] = useState({error: ''})
    const [showPassword, setShowPassword] = useState(false);

    /**
     * Handle toggle password type
     */
    const handlePasswordVisibility = () => {
        setShowPassword(!showPassword);
    };

    /**
     * Handle form submit
     */
    const handleSubmit = async (e) => {
        e.preventDefault();
        const response = await fetch('/login', {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                email: emailRef.current.value,
                password: passwordRef.current.value
            })
        })
        if (!response.ok) {
            const data = await response.json()
            setState({error: data.error})
        } else {
            setState({error: ''})
            emailRef.current.value = ''
            passwordRef.current.value = ''
            props.userAuthenticated(response.headers.get('location'))
        }
    }

    return <form onSubmit={handleSubmit}>
        {state.error && (
            <div className="alert alert-danger">{state.error}</div>
        )}
        <Field
            ref={emailRef}
            name={"email"}
            type={'text'}
            helpText={'bernie@dragonmail.com'}
        >
            Email
        </Field>
        <FieldPassword
            ref={passwordRef}
            name={"password"}
            type={showPassword ? 'text' : 'password'}
            helpText={'roar'}
            handlePasswordVisibility={handlePasswordVisibility}
        >
            Mot de passe
        </FieldPassword>
        <button type="submit" className="btn btn-primary">Connexion</button>
    </form>;
}

export default LoginForm
