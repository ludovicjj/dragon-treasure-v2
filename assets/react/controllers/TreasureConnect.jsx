import React, {useState} from 'react';
import LoginForm from "./LoginForm";

export default function TreasureConnect({user, tokens}) {
    const [state, setState] = useState({user: user})

    const onUserAuthenticated = async (uri) => {
        const response = await fetch(uri);
        const user = await response.json();
        setState({user: user})
    }

    console.log(tokens, user, state.user)
    return <div className="card-wrapper">
        <div className="card">
            <LoginForm userAuthenticated={onUserAuthenticated}/>
        </div>
        <div className="card">
            {state.user ? (
                <div>
                    Authenticated as <strong>{state.user.username}</strong>
                    | <a href="/logout" className="underline">Log out</a>
                    <h3 className="my-2">Tokens</h3>
                    {tokens ? (
                        <div>
                            <ul className="list-group list-unstyled">
                                {tokens.map(token => <li key={token} className="list-group-item mb-1">{token}</li>)}
                            </ul>
                        </div>
                    ) : (
                        <div>Refresh to see tokens...</div>
                    )}
                </div>
            ) : (
                <div className="text-center">
                    Not Authenticated
                </div>
            )}
            <hr className="my-5 mx-auto separator"/>
            <p className="text-center">Check out the <a href="/api" className="underline" target="_blank">API Docs</a></p>
        </div>
    </div>
}