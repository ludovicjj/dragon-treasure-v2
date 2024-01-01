import React, {useState} from 'react';
import LoginForm from "./LoginForm";

export default function TreasureConnect({user}) {
    const [state, setState] = useState({user: user})

    const onUserAuthenticated = async (uri) => {
        const response = await fetch(uri);
        const user = await response.json();
        setState({user: user})
    }

    return <div className="card-wrapper">
        <div className="card">
            <LoginForm userAuthenticated={onUserAuthenticated}/>
        </div>
        <div className="card">
            {state.user ? (
                <div>
                    Authenticated as <strong>{state.user.username}</strong>
                    | <a href="/logout" className="underline">Log out</a>
                </div>
            ) : (
                <div className="text-center">
                    Not Authenticated
                    <hr className="my-5 mx-auto separator"/>
                    <p>Check out the <a href="/api" className="underline">API Docs</a></p>
                </div>
            )}
        </div>
    </div>
}